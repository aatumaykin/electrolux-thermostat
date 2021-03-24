<?php

declare(strict_types=1);

namespace App\Domain\Electrolux\Dto\Request;

use App\Domain\Electrolux\Dto\AbstractDto;
use JetBrains\PhpStorm\ArrayShape;

class LoginDto extends AbstractDto
{
    public function __construct(private string $login, private string $password, private string $appCode,)
    {
    }

    #[ArrayShape(['login' => "string", 'password' => "string", 'appCode' => "string"])]
    public function jsonSerialize(): array
    {
        return [
            'login' => $this->login,
            'password' => $this->password,
            'appcode' => $this->appCode,
        ];
    }
}
