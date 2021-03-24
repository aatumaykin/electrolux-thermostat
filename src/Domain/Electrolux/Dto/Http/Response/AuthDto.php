<?php

declare(strict_types=1);

namespace App\Domain\Electrolux\Dto\Http\Response;

use App\Domain\Electrolux\Dto\Http\AuthResultDto;
use App\Domain\Electrolux\Enum\ResultTypeEnum;

class AuthDto extends BaseDto
{
    private ?AuthResultDto $result;
    private ResultTypeEnum $resultType;

    public function __construct(int $errorCode, string $errorMessage)
    {
        parent::__construct($errorCode, $errorMessage);

        if (0 !== $this->getErrorCode()) {
            $this->setResultType(ResultTypeEnum::ERROR());
        }

        if (0 === $this->getErrorCode()) {
            $this->setResultType(ResultTypeEnum::SUCCESS());
        }
    }

    public function getResult(): ?AuthResultDto
    {
        return $this->result;
    }

    public function setResult(AuthResultDto $result): self
    {
        $this->result = $result;

        return $this;
    }

    public function getResultType(): ResultTypeEnum
    {
        return $this->resultType;
    }

    public function setResultType(ResultTypeEnum $resultType): self
    {
        $this->resultType = $resultType;

        return $this;
    }
}
