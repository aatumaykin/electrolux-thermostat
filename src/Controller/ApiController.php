<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\Service;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ApiController
{
    public function __construct(private Service $service)
    {
    }

    #[Route('/login', methods: ['POST'])]
    public function login(): JsonResponse
    {
        $dto = $this->service->login();

        return new JsonResponse($dto);
    }

    #[Route('/devices', methods: ['GET'])]
    public function getDevices(): JsonResponse
    {
        $data = $this->service->getDevices();

        return new JsonResponse($data);
    }
}
