<?php

declare(strict_types=1);

namespace App\Domain\Electrolux\Dto\Tcp\Request\Sub;

use App\Domain\Electrolux\Dto\AbstractDto;
use App\Domain\Electrolux\Traits\JsonSerializableTrait;

class UpdateDevicesDto extends AbstractDto
{
    use JsonSerializableTrait;

    private array $device = [];

    public function add(UpdateDeviceDataDto $device): self
    {
        $this->device[] = $device;

        return $this;
    }
}
