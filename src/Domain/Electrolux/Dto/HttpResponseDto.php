<?php

declare(strict_types=1);

namespace App\Domain\Electrolux\Dto;

class HttpResponseDto
{
    private array $result;
    private string $errorCode;
    private string $errorMessage;

    public function __construct(array $result, string $errorCode, string $errorMessage)
    {
        $this->result = $result;
        $this->errorCode = $errorCode;
        $this->errorMessage = $errorMessage;
    }

    public function getResult(): array
    {
        return $this->result;
    }

    public function setResult(array $result): self
    {
        $this->result = $result;

        return $this;
    }

    public function getErrorCode(): string
    {
        return $this->errorCode;
    }

    public function setErrorCode(string $errorCode): self
    {
        $this->errorCode = $errorCode;

        return $this;
    }

    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }

    public function setErrorMessage(string $errorMessage): self
    {
        $this->errorMessage = $errorMessage;

        return $this;
    }
}
