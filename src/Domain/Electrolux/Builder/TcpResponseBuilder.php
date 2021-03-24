<?php

declare(strict_types=1);

namespace App\Domain\Electrolux\Builder;

use App\Domain\Electrolux\Dto\Tcp\Response\AscDto;
use App\Domain\Electrolux\Dto\Tcp\Response\GetDevicesDto;
use App\Domain\Electrolux\Dto\Tcp\Response\NullDto;
use App\Domain\Electrolux\Dto\Tcp\Response\Sub\GetDevicesResultDto;
use App\Domain\Electrolux\Dto\Tcp\Response\Sub\TokenResultDto;
use App\Domain\Electrolux\Dto\Tcp\Response\TokenDto;
use App\Domain\Electrolux\Enum\ResponseCommandTypeEnum;

class TcpResponseBuilder
{
    public static function build(array $data): mixed
    {
        $type = new ResponseCommandTypeEnum($data['command']);

        if ($type->equals(ResponseCommandTypeEnum::ASK())) {
            return new AscDto($data['data']);
        }

        if ($type->equals(ResponseCommandTypeEnum::TOKEN())) {
            return new TokenDto(
                $data['message_id'],
                new TokenResultDto($data['result']['result']),
                $data['message']
            );
        }

        if ($type->equals(ResponseCommandTypeEnum::GET_DEVICES())) {
            return new GetDevicesDto(
                $data['message_id'],
                new GetDevicesResultDto(
                    $data['result']['device'],
                    $data['result']['invalid_device'],
                    $data['result']['waiting_device'],
                ),
                $data['message']
            );
        }

        return new NullDto();
    }
}
