<?php

declare(strict_types=1);

namespace App\Domain\Electrolux\Dto\Tcp\Request;

use App\Domain\Electrolux\Dto\AbstractDto;
use App\Domain\Electrolux\Dto\JsonStringableInterface;
use App\Domain\Electrolux\Traits\JsonStringableTrait;
use JetBrains\PhpStorm\ArrayShape;

class BaseDto extends AbstractDto implements JsonStringableInterface
{
    use JsonStringableTrait;

    private ?string $commandId = null;

    public function __construct(private string $command, private string $lang, private mixed $data)
    {
    }

    public function getCommand(): string
    {
        return $this->command;
    }

    public function setCommand(string $command): self
    {
        $this->command = $command;

        return $this;
    }

    public function getCommandId(): string
    {
        return $this->commandId;
    }

    public function setCommandId(string $commandId): self
    {
        $this->commandId = $commandId;

        return $this;
    }

    public function getLang(): string
    {
        return $this->lang;
    }

    public function setLang(string $lang): self
    {
        $this->lang = $lang;

        return $this;
    }

    public function getData(): mixed
    {
        return $this->data;
    }

    public function setData(mixed $data): self
    {
        $this->data = $data;

        return $this;
    }

    #[ArrayShape(['lang' => 'string', 'command' => 'string', 'message_id' => 'string', 'data' => 'mixed'])]
    public function jsonSerialize(): array
    {
        return [
            'lang' => $this->lang,
            'command' => $this->command,
            //            'message_id' => $this->commandId,
            'data' => $this->data,
        ];
    }
}
