<?php

declare(strict_types=1);

namespace App\Domain\Electrolux\Dto\Tcp\Response;

use App\Domain\Electrolux\Dto\AbstractDto;
use App\Domain\Electrolux\Traits\JsonSerializableTrait;

class GetDeviceParamsDto extends AbstractDto
{
    use JsonSerializableTrait;

    public function __construct(private string $commandId, private array $result, private string $message)
    {
    }

    public function getResult(): array
    {
        return $this->result;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getCommandId(): string
    {
        return $this->commandId;
    }
}
