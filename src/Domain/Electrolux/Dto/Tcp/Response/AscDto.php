<?php

declare(strict_types=1);

namespace App\Domain\Electrolux\Dto\Tcp\Response;

use App\Domain\Electrolux\Dto\AbstractDto;
use App\Domain\Electrolux\Traits\JsonSerializableTrait;

class AscDto extends AbstractDto
{
    use JsonSerializableTrait;

    public function __construct(private ?string $data = null)
    {
    }

    public function getData(): ?string
    {
        return $this->data;
    }
}
