<?php

declare(strict_types=1);

namespace App\Domain\Electrolux\Helper;

class CleanHelper
{
    public static function clean(string $message): string
    {
        return trim($message, " \t\n\r\0\x0B\x01\x10\x03\x0f");
    }
}
