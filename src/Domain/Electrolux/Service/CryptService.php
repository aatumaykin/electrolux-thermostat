<?php

declare(strict_types=1);

namespace App\Domain\Electrolux\Service;

use App\Domain\Electrolux\Helper\CleanHelper;

class CryptService
{
    private const CIPHER_ALGO = "AES-256-CBC";

    public function __construct(private string $key)
    {
    }

    public function encrypt(string $message): string
    {
        $hash = hash('sha384', $this->key, true);

        $iv = substr($hash, 32, 16);
        $key = substr($hash, 0, 32);

        $encrypt = (string) openssl_encrypt(
            $message,
            self::CIPHER_ALGO,
            $key,
            OPENSSL_RAW_DATA,
            $iv
        );

        $result = base64_encode($encrypt);
        $result .= md5($result);

        return $result;
    }

    public function decrypt(string $message): string
    {
        $hash = hash('sha384', $this->key, true);

        $iv = substr($hash, 32, 16);
        $key = substr($hash, 0, 32);

        $message = substr($message, 0, -32);

        $result = (string) openssl_decrypt(
            base64_decode($message),
            self::CIPHER_ALGO,
            $key,
            OPENSSL_CIPHER_AES_256_CBC,
            $iv
        );

        return CleanHelper::clean($result);
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function setKey(string $key): void
    {
        $this->key = $key;
    }
}
