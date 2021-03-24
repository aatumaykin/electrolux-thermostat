<?php

declare(strict_types=1);

namespace App\Domain\Electrolux\Dto\Http;

use App\Domain\Electrolux\Dto\AbstractDto;
use App\Domain\Electrolux\Traits\JsonSerializableTrait;

class UserDto extends AbstractDto
{
    use JsonSerializableTrait;

    public function __construct(private string $name)
    {
    }

    public function getName(): string
    {
        return $this->name;
    }
}
