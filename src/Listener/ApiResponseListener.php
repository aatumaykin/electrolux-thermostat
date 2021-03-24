<?php

declare(strict_types=1);

namespace App\Listener;

use InvalidArgumentException;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ApiResponseListener
{
    /**
     * @throws InvalidArgumentException
     */
    public function onKernelException(ExceptionEvent $event): void
    {
        if ($exception instanceof GenericException) {
            $event->setResponse($exception->getResponse()->setStatusCode($exception->getStatusCode()));
            $event->stopPropagation();
        }
    }
}
