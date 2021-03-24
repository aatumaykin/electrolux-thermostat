<?php

declare(strict_types=1);

namespace App\Listener;

use InvalidArgumentException;
use JsonSerializable;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ApiResponseListener
{
    /**
     * @throws InvalidArgumentException
     */
    public function onKernelException(ExceptionEvent $event): void
    {
        $throwable = $event->getThrowable();

        $response = new JsonResponse([
            'data' => $throwable instanceof JsonSerializable
                ? $throwable->jsonSerialize()
                : $throwable->getTrace(),
            'message' => $throwable->getMessage(),
        ]);

        $event->setResponse($response);
        $event->setThrowable($throwable);
        $event->stopPropagation();
    }
}
