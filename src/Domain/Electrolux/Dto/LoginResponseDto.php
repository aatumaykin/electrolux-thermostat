<?php

declare(strict_types=1);

namespace App\Domain\Electrolux\Dto;

class LoginResponseDto
{
    private string $encKey;
    private string $token;
    private array $versions;
    private ServerDto $server;
    private ServerDto $tcpServer;

    public static function fromResponseDto(array $data): self
    {
        $dto = new self();
        $dto->setEncKey($data['enc_key'] ?? '')
            ->setServer(new ServerDto(
                $data['server']['host'] ?? '',
                $data['server']['port'] ?? '',
            ))
            ->setTcpServer(new ServerDto(
                $data['tcp_server']['host'] ?? '',
                $data['tcp_server']['port'] ?? '',
            ))
            ->setToken($data['token'] ?? '')
            ->setVersions($data['versions'] ?? [])
        ;

        return $dto;
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

    public function getToken(): string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

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
}
