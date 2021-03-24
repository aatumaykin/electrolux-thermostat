<?php

declare(strict_types=1);

namespace App\Domain\Electrolux\Dto\Tcp\Request;

use App\Domain\Electrolux\Dto\Tcp\Request\Sub\GetDevicesDataDto;
use App\Domain\Electrolux\Enum\RequestCommandTypeEnum;

class GetDevicesDto extends BaseDto
{
    private const COMMAND = RequestCommandTypeEnum::GET_DEVICES;

    public function __construct(string $lang, GetDevicesDataDto $data)
    {
        parent::__construct(self::COMMAND, $lang, $data);
    }
}
