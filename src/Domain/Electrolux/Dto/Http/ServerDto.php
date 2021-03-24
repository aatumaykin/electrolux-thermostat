<?php

declare(strict_types=1);

namespace App\Domain\Electrolux\Dto\Http;

use App\Domain\Electrolux\Dto\AbstractDto;
use App\Domain\Electrolux\Traits\JsonSerializableTrait;

class ServerDto extends AbstractDto
{
    use JsonSerializableTrait;

    public function __construct(private string $host, private string $port)
    {
    }

    public function __toString(): string
    {
        return $this->host.':'.$this->port;
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function getPort(): string
    {
        return $this->port;
    }
}
