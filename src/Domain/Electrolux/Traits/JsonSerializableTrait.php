<?php

declare(strict_types=1);

namespace App\Domain\Electrolux\Traits;

trait JsonSerializableTrait
{
    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}
