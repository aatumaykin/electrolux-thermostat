<?php

declare(strict_types=1);

namespace App\Service;

use App\Domain\Electrolux\Builder\TcpResponseBuilder;
use App\Domain\Electrolux\Config;
use App\Domain\Electrolux\Dto\Http\AuthResultDto;
use App\Domain\Electrolux\Dto\Http\Request\LoginDto;
use App\Domain\Electrolux\Dto\Http\ServerDto;
use App\Domain\Electrolux\Dto\Http\UserDto;
use App\Domain\Electrolux\Dto\Tcp\Response\AscDto;
use App\Domain\Electrolux\Dto\Tcp\Response\GetDevicesDto;
use App\Domain\Electrolux\Dto\Tcp\Response\SetDeviceParamsDto;
use App\Domain\Electrolux\Dto\Tcp\Response\TokenDto;
use App\Domain\Electrolux\Helper\Json;
use App\Domain\Electrolux\HttpClient;
use App\Domain\Electrolux\Service\CryptService;
use App\Domain\Electrolux\TcpClient;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class Service
{
    private const TIMEOUT = 30 * 1000;

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

                if ('' === $message) {
                    usleep(500);

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
//                    $this->tcpClient->setDeviceParams($deviceId, $params);
                    $this->tcpClient->sendMessage('{"lang":"ru","command":"setDeviceParams","message_id":null,"data":{"device":[{"uid":"188577", "params":{"state":"1"}}]}}');
                }

                if ($command instanceof GetDevicesDto) {
//                    $this->tcpClient->setDeviceParams($deviceId, $params);
                    $this->tcpClient->sendMessage('{"lang":"ru","command":"setDeviceParams","message_id":null,"data":{"device":[{"uid":"188577", "params":{"state":"1"}}]}}');
                }

                if ($command instanceof SetDeviceParamsDto) {
                    $result = $command->jsonSerialize();
                    $continue = false;
                }

                usleep(500);
            } while (true === $continue && microtime(true) - $time < self::TIMEOUT);
        } finally {
            $this->tcpClient->close();
        }

        return $result;
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

    private function buildAuthResultDto(array $data): AuthResultDto
    {
        $dto = new AuthResultDto();
        $dto->setToken($data['token'])
            ->setEncKey($data['encKey'])
            ->setUser(new UserDto($data['user']['name']))
            ->setServer(new ServerDto($data['server']['host'], $data['server']['port']))
            ->setTcpServer(new ServerDto($data['tcpServer']['host'], $data['tcpServer']['port']))
            ->setVersions($data['versions'])
        ;

        return $dto;
    }
}
