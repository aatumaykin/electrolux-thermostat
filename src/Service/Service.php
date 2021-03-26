<?php

declare(strict_types=1);

namespace App\Service;

use App\Domain\Electrolux\Builder\TcpResponseBuilder;
use App\Domain\Electrolux\Config;
use App\Domain\Electrolux\Dto\Http\AuthResultDto;
use App\Domain\Electrolux\Dto\Http\Request\LoginDto;
use App\Domain\Electrolux\Dto\Tcp\Response\AscDto;
use App\Domain\Electrolux\Dto\Tcp\Response\GetDevicesDto;
use App\Domain\Electrolux\Dto\Tcp\Response\SetDeviceParamsDto;
use App\Domain\Electrolux\Dto\Tcp\Response\TokenDto;
use App\Domain\Electrolux\Helper\CleanHelper;
use App\Domain\Electrolux\Helper\Json;
use App\Domain\Electrolux\HttpClient;
use App\Domain\Electrolux\Service\CryptService;
use App\Domain\Electrolux\TcpClient;
use JetBrains\PhpStorm\ArrayShape;
use JsonException;
use RuntimeException;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Throwable;
use function file_get_contents;
use function sprintf;
use function strlen;
use function usleep;

class Service
{
    private const TIMEOUT = 180;

    private FilesystemAdapter $cache;

    public function __construct(
        private Config $config,
        private HttpClient $httpClient,
        private TcpClient $tcpClient,
        private CryptService $cryptService,
    ) {
        $this->cache = new FilesystemAdapter();
    }

    public function login(): AuthResultDto
    {
        $loginDto = new LoginDto(
            $this->config->getLogin(),
            $this->config->getPassword(),
            $this->config->getAppCode()
        );

        return $this->httpClient->login($loginDto);
    }

    /**
     * @throws JsonException
     */
    public function getDevices(): array
    {
        [$key, $token, $host] = $this->loadCache();

        $this->tcpClient->connect($host);
        $this->cryptService->setKey($key);

        $result = [];
        $time = microtime(true);
        $continue = true;
        do {
            $message = $this->tcpClient->read();

            if ('' === $message) {
                continue;
            }

            if (!str_starts_with($message, '{')) {
                $message = $this->cryptService->decrypt($message);
            }

            $data = Json::decodeAsArray($message);
            $command = TcpResponseBuilder::build($data);

            if ($command instanceof AscDto) {
                $this->tcpClient->sendToken($token);
            }

            if ($command instanceof TokenDto) {
                $this->tcpClient->getDevices('');
            }

            if ($command instanceof GetDevicesDto) {
                $result = $command->jsonSerialize();
                $continue = false;
            }
        } while (true === $continue && microtime(true) - $time < self::TIMEOUT);

        $this->tcpClient->close();

        return $result;
    }

    /**
     * @throws JsonException
     */
    public function setDeviceUpdate(string $deviceId, array $params): array
    {
        [$key, $token, $host] = $this->loadCache();

        $this->tcpClient->connect($host);
        $this->cryptService->setKey($key);

        $result = [];
        $time = microtime(true);
        $continue = true;

        try {
            do {
                $message = $this->tcpClient->read();

                if (!str_starts_with($message, '{') && !str_starts_with($message, 'PONG')) {
                    $message = $this->cryptService->decrypt($message);
                }

                echo sprintf("(%d)<- %s\n", strlen($message), $message);

                if ('' === $message) {
                    usleep(1000);

                    continue;
                }

                if (str_starts_with($message, 'PONG')) {
                    continue;
                }

                $data = Json::decodeAsArray($message);
                $command = TcpResponseBuilder::build($data);

                if ($command instanceof AscDto) {
                    $this->tcpClient->sendToken($token);
                }

                if ($command instanceof TokenDto) {
                    $this->tcpClient->setDeviceParams($deviceId, $params);
                }

                if ($command instanceof GetDevicesDto) {
                    $this->tcpClient->setDeviceParams($deviceId, $params);
                }

                if ($command instanceof SetDeviceParamsDto) {
                    $result = $command->jsonSerialize();
                    $continue = false;
                }

                usleep(1000);

                $this->tcpClient->sendMessage('PING');

                $this->tcpClient->setDeviceParams($deviceId, $params);

                if (microtime(true) - $time > self::TIMEOUT) {
                    throw new RuntimeException('Timeout');
                }
            } while (true === $continue);
        } finally {
            $this->tcpClient->close();
        }

        return $result;
    }

    /**
     * @throws JsonException
     */
    public function sendMessageFromFile(string $file): void
    {
        [$key, $token, $host] = $this->loadCache();

        $this->tcpClient->connect($host);
        $this->cryptService->setKey($key);

        $continue = true;
        $prev = null;
        while ($continue) {
            try {
                $response = $this->tcpClient->read();
                $response = CleanHelper::clean($response);

                if (!str_starts_with($response, '{') && !str_starts_with($response, 'PONG')) {
                    $response = $this->cryptService->decrypt($response);
                }

                if ('' !== $response) {
                    echo sprintf("(%d)<- %s\n", strlen($response), $response);
                }

                $request = (string) file_get_contents($file);
                $request = CleanHelper::clean($request);

                if ($prev === $request) {
                    echo '.';

                    usleep(2 * 1000 * 1000);

                    continue;
                }

                $prev = $request;

                $this->tcpClient->sendMessage($request);
            } catch (Throwable) {
                $this->tcpClient->close();
                $continue = false;
            }
        }
    }

    #[ArrayShape(['key' => 'string', 'token' => 'string', 'host' => 'string'])]
    public function saveCache(string $key, string $token, string $host): array
    {
        $data = [
            'key' => $key,
            'token' => $token,
            'host' => $host,
        ];

        $this->cache->save(
            $this->cache
                ->getItem('auth-data')
                ->set($data)
        );

        return $data;
    }

    #[ArrayShape(['key' => 'string', 'token' => 'string', 'host' => 'string'])]
    public function loadCache(): array
    {
        $item = $this->cache->getItem('auth-data');

        if ($item->isHit()) {
            $data = $item->get();

            return [
                $data['key'],
                $data['token'],
                $data['host'],
            ];
        }

        $dto = $this->login();

        return $this->saveCache(
            $dto->getEncKey(),
            $dto->getToken(),
            $dto->getTcpServer()->__toString()
        );
    }
}
