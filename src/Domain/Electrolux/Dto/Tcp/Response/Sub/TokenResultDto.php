<?php

declare(strict_types=1);

namespace App\Domain\Electrolux\Dto\Tcp\Response\Sub;

class TokenResultDto
{
    public function __construct(private string $result)
    {
    }

    public function getResult(): string
    {
        return $this->result;
    }
}
