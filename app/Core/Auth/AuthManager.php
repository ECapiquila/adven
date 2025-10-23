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

        $this->loginUsingId((int) $user['id']);
        $this->syncContext($user);
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
        unset($_SESSION['church_id']);
    }

    public function loginUsingId(int $userId): void
    {
        $_SESSION[self::SESSION_KEY] = $userId;
        $user = $this->userModel->find($userId);
        if ($user) {
            $this->syncContext($user);
        }
    }

    private function syncContext(array $user): void
    {
        if (!empty($user['church_id'])) {
            $_SESSION['church_id'] = (int) $user['church_id'];
        }
    }
}
