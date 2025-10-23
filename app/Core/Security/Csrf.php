<?php

namespace App\Core\Security;

class Csrf
{
    private const TOKEN_KEY = '_token';

    public static function token(): string
    {
        if (empty($_SESSION[self::TOKEN_KEY])) {
            $_SESSION[self::TOKEN_KEY] = bin2hex(random_bytes(32));
        }

        return $_SESSION[self::TOKEN_KEY];
    }

    public static function validate(?string $token): bool
    {
        return hash_equals($_SESSION[self::TOKEN_KEY] ?? '', $token ?? '');
    }
}
