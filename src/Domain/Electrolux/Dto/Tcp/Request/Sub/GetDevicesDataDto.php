<?php

declare(strict_types=1);

namespace App\Domain\Electrolux\Dto\Tcp\Request\Sub;

use App\Domain\Electrolux\Dto\AbstractDto;
use App\Domain\Electrolux\Traits\JsonSerializableTrait;

class GetDevicesDataDto extends AbstractDto
{
    use JsonSerializableTrait;

    public function __construct(private string $uid)
    {
    }

    public function getUid(): string
    {
        return $this->uid;
    }
}
