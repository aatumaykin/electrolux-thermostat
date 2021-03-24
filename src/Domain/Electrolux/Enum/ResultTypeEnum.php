<?php

declare(strict_types=1);

namespace App\Domain\Electrolux\Enum;

use MyCLabs\Enum\Enum;

/**
 * @method static self CODE()
 * @method static self ERROR()
 * @method static self SUCCESS()
 */
class ResultTypeEnum extends Enum
{
    public const CODE = 0;
    public const ERROR = 1;
    public const SUCCESS = 2;
}
