<?php

declare(strict_types=1);

namespace App\Domain\Electrolux;

use App\Domain\Electrolux\Dto\Tcp\Request\GetDevicesDto;
use App\Domain\Electrolux\Dto\Tcp\Request\Sub\GetDevicesDataDto;
use App\Domain\Electrolux\Dto\Tcp\Request\Sub\UpdateDeviceDataDto;
use App\Domain\Electrolux\Dto\Tcp\Request\Sub\UpdateDevicesDto;
use App\Domain\Electrolux\Dto\Tcp\Request\TokenDto;
use App\Domain\Electrolux\Dto\Tcp\Request\UpdateDeviceDto;
use App\Domain\Electrolux\Helper\CleanHelper;
use JsonException;
use Socket\Raw\Factory;
use Socket\Raw\Socket;
use Throwable;
use function strlen;

class TcpClient
{
    private const COMMAND_SUFFIX = "\r\n";

    private Socket $socket;

    public function __construct(private Config $config)
    {
    }

    public function __destruct()
    {
        $this->close();
    }

    public function connect(string $address): self
    {
        $this->socket = (new Factory())->createClient($address);
        $this->socket->setBlocking(false);

        return $this;
    }

    public function close(): void
    {
        try {
            $this->socket->close();
        } catch (Throwable) {
        }
    }

    public function read(): string
    {
        if (!$this->socket->selectRead()) {
            return '';
        }

        return CleanHelper::clean($this->socket->read(8192, PHP_NORMAL_READ));
    }

    public function sendMessage(string $message): self
    {
        $result = $this->socket->write($message.self::COMMAND_SUFFIX);
        echo sprintf("\n(%d/%d)-> %s\n", $result, strlen($message), $message);

        return $this;
    }

    /**
     * @throws JsonException
     */
    public function sendToken(string $token): self
    {
        $command = new TokenDto(
            $this->config->getLang(),
            $token
        );

        $this->sendMessage($command->asJsonString());

        return $this;
    }

    /**
     * @throws JsonException
     */
    public function getDevices(string $uid): self
    {
        $command = new GetDevicesDto(
            $this->config->getLang(),
            new GetDevicesDataDto($uid)
        );

        $this->sendMessage($command->asJsonString());

        return $this;
    }

    /**
     * @throws JsonException
     */
    public function setDeviceParams(string $deviceId, array $params): self
    {
        $devicesList = new UpdateDevicesDto();
        $devicesList->add(new UpdateDeviceDataDto($deviceId, $params));

        $command = new UpdateDeviceDto(
            $this->config->getLang(),
            $devicesList
        );

        $this->sendMessage($command->asJsonString());

        return $this;
    }
}
