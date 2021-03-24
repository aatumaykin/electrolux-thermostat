<?php

declare(strict_types=1);

namespace App\Domain\Electrolux;

class Config
{
    private string $uri;
    private string $login;
    private string $password;
    private string $appCode;
    private string $lang;

    public function __construct(array $config = [])
    {
        $this->fromArray($config);
    }

    public function fromArray(array $config = []): self
    {
        foreach ($config as $property => $value) {
            if (!property_exists($this, $property)) {
                continue;
            }

            $this->{$property} = $value;
        }

        return $this;
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function setUri(string $uri): self
    {
        $this->uri = $uri;

        return $this;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function setLogin(string $login): self
    {
        $this->login = $login;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getAppCode(): string
    {
        return $this->appCode;
    }

    public function setAppCode(string $appCode): self
    {
        $this->appCode = $appCode;

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
}
