<?php

declare(strict_types=1);

namespace App\Domain\Electrolux\Dto\Tcp\Response;

use App\Domain\Electrolux\Dto\AbstractDto;
use App\Domain\Electrolux\Dto\Tcp\Response\Sub\GetDevicesResultDto;
use App\Domain\Electrolux\Traits\JsonSerializableTrait;

class GetDevicesDto extends AbstractDto
{
    use JsonSerializableTrait;

    public function __construct(private string $commandId, private GetDevicesResultDto $result, private string $message)
    {
    }

    public function getResult(): GetDevicesResultDto
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
