<?php

declare(strict_types=1);

namespace App\Domain\Electrolux\Dto\Tcp\Response;

use App\Domain\Electrolux\Dto\AbstractDto;
use App\Domain\Electrolux\Traits\JsonSerializableTrait;

class NullDto extends AbstractDto
{
    use JsonSerializableTrait;

    private string $commandId = '';

    public function getCommandId(): string
    {
        return $this->commandId;
    }

    public function setCommandId(string $commandId): self
    {
        $this->commandId = $commandId;

        return $this;
    }
}
