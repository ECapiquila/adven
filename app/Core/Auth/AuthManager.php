<?php

namespace App\Core\Auth;

use App\Models\User;

class AuthManager
{
    private const SESSION_KEY = 'auth_user_id';

    public function __construct(private readonly User $userModel)
    {
    }

    public function attempt(string $login, string $password): bool
    {
        $user = $this->userModel->findByLogin($login);
        if (!$user) {
            return false;
        }

        if (!password_verify($password, $user['password'])) {
            return false;
        }

        $_SESSION[self::SESSION_KEY] = $user['id'];
        return true;
    }

    public function user(): ?array
    {
        $userId = $_SESSION[self::SESSION_KEY] ?? null;
        if (!$userId) {
            return null;
        }

        return $this->userModel->find((int) $userId);
    }

    public function check(): bool
    {
        return $this->user() !== null;
    }

    public function logout(): void
    {
        unset($_SESSION[self::SESSION_KEY]);
    }
}
