<?php

declare(strict_types=1);

namespace App\Domain\Electrolux\Enum;

use MyCLabs\Enum\Enum;

class ErrorCodeEnum extends Enum
{
    private static $codes = [
        101 => 'Неверные входные параметры',
        106 => 'Неверная пара логин\пароль.',
        112 => 'Пользователь не найден',
        136 => 'Пользователь не найден',
        156 => 'Неверный идентификатор приложения',
    ];
}
