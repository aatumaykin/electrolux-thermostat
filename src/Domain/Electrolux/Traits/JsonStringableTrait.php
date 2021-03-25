<?php

declare(strict_types=1);

namespace App\Domain\Electrolux\Traits;

use App\Domain\Electrolux\Helper\Json;
use JsonException;

trait JsonStringableTrait
{
    /**
     * @throws JsonException
     */
    public function asJsonString(): string
    {
        return Json::encode($this->jsonSerialize());
    }
}
