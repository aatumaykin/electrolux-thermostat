<?php

declare(strict_types=1);

namespace App\Domain\Electrolux\Helper;

use JsonSerializable;

class JsonSerializeHelper
{
    public static function jsonSerializeRecursive(array $data): array
    {
        return array_map(static function ($value) {
            if ($value instanceof JsonSerializable) {
                return $value->jsonSerialize();
            }

            if (is_array($value)) {
                return static::jsonSerializeRecursive($value);
            }

            return $value;
        }, $data);
    }
}
