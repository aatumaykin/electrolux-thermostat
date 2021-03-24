<?php

declare(strict_types=1);

namespace App\Domain\Electrolux\Dto\Tcp\Request;

use App\Domain\Electrolux\Enum\RequestCommandTypeEnum;

class TokenDto extends BaseDto
{
    private const COMMAND = RequestCommandTypeEnum::TOKEN;

    public function __construct(string $lang, string $token)
    {
        parent::__construct(self::COMMAND, $lang, $token);
    }
}
