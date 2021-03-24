<?php

declare(strict_types=1);

namespace App\Domain\Electrolux\Dto;

interface JsonStringableInterface
{
    public function asJsonString(): string;
}
