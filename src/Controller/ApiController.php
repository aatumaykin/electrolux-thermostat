<?php

declare(strict_types=1);

namespace App\Controller;

use App\Domain\Electrolux\Client;
use App\Domain\Electrolux\Config;
use App\Domain\Electrolux\Dto\Request\LoginDto;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ApiController
{
    public function __construct(private Config $config, private Client $client)
    {

    }

    #[Route("/login", methods: ["POST"])]
    public function login(): JsonResponse
    {
        $loginDto = new LoginDto(
            $this->config->getLogin(),
            $this->config->getPassword(),
            $this->config->getAppCode()
        );

        $responseDto = $this->client->login($loginDto);

        return new JsonResponse($responseDto);
    }
}
