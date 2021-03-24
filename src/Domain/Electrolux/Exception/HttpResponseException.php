<?php

declare(strict_types=1);

namespace App\Domain\Electrolux\Exception;

use Psr\Http\Message\ResponseInterface;
use Throwable;

class HttpResponseException extends AbstractException
{
    public function __construct(
        string $message,
        private ResponseInterface $response,
        ?Throwable $previous = null
    )
    {
        parent::__construct($message, $previous);
    }

    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }
}
