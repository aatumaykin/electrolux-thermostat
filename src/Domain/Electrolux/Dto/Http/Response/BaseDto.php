<?php

declare(strict_types=1);

namespace App\Domain\Electrolux\Dto\Http\Response;

class BaseDto
{
    public function __construct(private int $errorCode, private string $errorMessage)
    {
    }

    public function getErrorCode(): int
    {
        return $this->errorCode;
    }

    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }
}
