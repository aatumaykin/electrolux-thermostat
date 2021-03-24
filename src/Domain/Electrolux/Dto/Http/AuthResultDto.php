<?php

declare(strict_types=1);

namespace App\Domain\Electrolux\Dto\Http;

use App\Domain\Electrolux\Dto\AbstractDto;
use App\Domain\Electrolux\Traits\JsonSerializableTrait;

class AuthResultDto extends AbstractDto
{
    use JsonSerializableTrait;

    private string $token;
    private UserDto $user;
    private string $encKey;
    private ServerDto $server;
    private ServerDto $tcpServer;
    private array $versions;

    public function getToken(): string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getUser(): UserDto
    {
        return $this->user;
    }

    public function setUser(UserDto $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getEncKey(): string
    {
        return $this->encKey;
    }

    public function setEncKey(string $encKey): self
    {
        $this->encKey = $encKey;

        return $this;
    }

    public function getServer(): ServerDto
    {
        return $this->server;
    }

    public function setServer(ServerDto $server): self
    {
        $this->server = $server;

        return $this;
    }

    public function getTcpServer(): ServerDto
    {
        return $this->tcpServer;
    }

    public function setTcpServer(ServerDto $tcpServer): self
    {
        $this->tcpServer = $tcpServer;

        return $this;
    }

    public function getVersions(): array
    {
        return $this->versions;
    }

    public function setVersions(array $versions): self
    {
        $this->versions = $versions;

        return $this;
    }
}
