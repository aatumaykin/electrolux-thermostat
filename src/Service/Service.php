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
use App\Domain\Electrolux\Dto\Tcp\Response\TokenDto;
use App\Domain\Electrolux\Helper\Json;
use App\Domain\Electrolux\HttpClient;
use App\Domain\Electrolux\Service\CryptService;
use App\Domain\Electrolux\TcpClient;
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
        $item = $this->cache->getItem('auth-data');

        if ($item->isHit()) {
            return $this->buildAuthResultDto($item->get());
        }

        $loginDto = new LoginDto(
            $this->config->getLogin(),
            $this->config->getPassword(),
            $this->config->getAppCode()
        );

        $dto = $this->httpClient->login($loginDto);

        $item->set($dto->jsonSerialize());
        $this->cache->save($item);

        return $dto;
    }

    public function getDevices(): array
    {
        $item = $this->cache->getItem('auth-data');

        if (!$item->isHit()) {
            $this->login();
            $item = $this->cache->getItem('auth-data');
        }

        $dto = $this->buildAuthResultDto($item->get());

        $this->tcpClient->connect($dto->getTcpServer()->__toString());
        $this->cryptService->setKey($dto->getEncKey());

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
                $this->tcpClient->sendToken($dto->getToken());
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
