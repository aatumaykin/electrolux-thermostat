<?php

declare(strict_types=1);

namespace App\Domain\Electrolux\Exception;

use Exception;
use JsonSerializable;
use Throwable;

abstract class AbstractException extends Exception implements JsonSerializable
{
    public function __construct(
        string $message,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, 0, $previous);
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): mixed
    {
        return get_object_vars($this);
    }
}
