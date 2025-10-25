<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\User;
use Core\Session;
use PDOException;

final class Auth
{
    private const SESSION_KEY = 'auth_user_id';

    public static function attempt(string $email, string $password): bool
    {
        try {
            $user = User::findByEmail($email);
        } catch (PDOException|\RuntimeException) {
            return false;
        }

        if ($user === null) {
            return false;
        }

        if (!password_verify($password, $user['password'])) {
            return false;
        }

        session()->put(self::SESSION_KEY, (int) $user['id']);
        session()->regenerate();

        return true;
    }

    public static function logout(): void
    {
        session()->forget(self::SESSION_KEY);
        session()->regenerate();
    }

    public static function user(): ?array
    {
        $id = session()->get(self::SESSION_KEY);
        if (!$id) {
            return null;
        }

        try {
            return User::find((int) $id);
        } catch (PDOException|\RuntimeException) {
            return null;
        }
    }

    public static function check(): bool
    {
        return self::user() !== null;
    }

    public static function requireLogin(): void
    {
        if (!self::check()) {
            flash('error', 'Inicie sessão para continuar.');
            redirect('/login');
        }
    }

    public static function hasRole(string $role): bool
    {
        $user = self::user();
        if (!$user) {
            return false;
        }

        $flag = match ($role) {
            'admin' => (bool) ($user['is_admin'] ?? false),
            'musico' => (bool) ($user['is_musician'] ?? false),
            'pastor' => (bool) ($user['is_pastor'] ?? false),
            'anciao' => (bool) ($user['is_anciao'] ?? false),
            default => false,
        };

        if ($flag) {
            return true;
        }

        if (!empty($user['roles']) && is_array($user['roles'])) {
            return in_array($role, $user['roles'], true);
        }

        return false;
    }
}
