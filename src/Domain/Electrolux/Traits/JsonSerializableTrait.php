<?php

declare(strict_types=1);

namespace App\Domain\Electrolux\Traits;

use App\Domain\Electrolux\Helper\JsonSerializeHelper;

trait JsonSerializableTrait
{
    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return JsonSerializeHelper::jsonSerializeRecursive(get_object_vars($this));
    }
}
