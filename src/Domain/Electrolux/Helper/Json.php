<?php

declare(strict_types=1);

namespace App\Domain\Electrolux\Helper;

use JsonException;

class Json
{
    /**
     * @throws JsonException
     */
    public static function encode(mixed $data): string
    {
        if (is_array($data) && count($data) > 0) {
            return json_encode(
                $data,
                JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR | JSON_PRESERVE_ZERO_FRACTION
            );
        }

        return json_encode(
            $data,
            JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR | JSON_PRESERVE_ZERO_FRACTION | JSON_FORCE_OBJECT
        );
    }

    /**
     * @throws JsonException
     */
    public static function decodeAsArray(string $content): ?array
    {
        return json_decode($content, true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * @throws JsonException
     */
    public static function decodeAsObject(string $content): object
    {
        return json_decode($content, false, 512, JSON_THROW_ON_ERROR);
    }
}
