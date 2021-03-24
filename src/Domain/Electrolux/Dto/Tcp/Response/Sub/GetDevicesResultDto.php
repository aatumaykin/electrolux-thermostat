<?php

declare(strict_types=1);

namespace App\Domain\Electrolux\Dto\Tcp\Response\Sub;

use App\Domain\Electrolux\Dto\AbstractDto;
use App\Domain\Electrolux\Traits\JsonSerializableTrait;

class GetDevicesResultDto extends AbstractDto
{
    use JsonSerializableTrait;

    public function __construct(
        private array $device = [],
        private array $invalid = [],
        private array $waiting = [],
    ) {
    }

    public function getDevice(): array
    {
        return $this->device;
    }

    public function getInvalid(): array
    {
        return $this->invalid;
    }

    public function getWaiting(): array
    {
        return $this->waiting;
    }
}
