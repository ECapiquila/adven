<?php

declare(strict_types=1);

namespace Core;

final class Session
{
    private static ?self $instance = null;

    private function __construct()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            $secure = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
            session_start([
                'cookie_httponly' => true,
                'cookie_secure' => $secure,
                'cookie_samesite' => 'Strict',
                'use_strict_mode' => true,
                'use_cookies' => true,
            ]);
        }
    }

    public static function instance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $_SESSION[$key] ?? $default;
    }

    public function put(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    public function forget(string $key): void
    {
        unset($_SESSION[$key]);
    }

    public function flash(string $key, mixed $value): void
    {
        $_SESSION['_flash'][$key] = $value;
    }

    public function pull(string $key, mixed $default = null): mixed
    {
        $value = $this->get($key, $default);
        $this->forget($key);

        return $value;
    }

    public function all(): array
    {
        return $_SESSION;
    }

    public function regenerate(): void
    {
        session_regenerate_id(true);
    }

    public function flushFlash(): array
    {
        $flash = $_SESSION['_flash'] ?? [];
        unset($_SESSION['_flash']);

        return $flash;
    }
}
