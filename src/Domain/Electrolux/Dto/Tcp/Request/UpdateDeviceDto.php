<?php

declare(strict_types=1);

namespace App\Domain\Electrolux\Dto\Tcp\Request;

use App\Domain\Electrolux\Dto\Tcp\Request\Sub\UpdateDevicesDto;
use App\Domain\Electrolux\Enum\RequestCommandTypeEnum;

class UpdateDeviceDto extends BaseDto
{
    private const COMMAND = RequestCommandTypeEnum::UPDATE_DEVICE;

    public function __construct(string $lang, UpdateDevicesDto $params)
    {
        parent::__construct(self::COMMAND, $lang, $params);
    }
}
