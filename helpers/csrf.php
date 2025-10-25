<?php

declare(strict_types=1);

use Core\Http\Request;

if (!function_exists('csrf_token')) {
    function csrf_token(): string
    {
        if (!isset($_SESSION['_csrf_token'])) {
            $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
        }

        return (string) $_SESSION['_csrf_token'];
    }
}

if (!function_exists('csrf_field')) {
    function csrf_field(): string
    {
        return '<input type="hidden" name="_token" value="' . htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') . '">';
    }
}

if (!function_exists('csrf_verify')) {
    function csrf_verify(Request $request): void
    {
        $cookies = $request->cookies();
        $token = $request->input('_token')
            ?? ($cookies['_token'] ?? null)
            ?? ($_SERVER['HTTP_X_CSRF_TOKEN'] ?? null);

        if (!is_string($token) || !hash_equals(csrf_token(), $token)) {
            http_response_code(419);
            echo 'Token CSRF inválido.';
            exit;
        }
    }
}
