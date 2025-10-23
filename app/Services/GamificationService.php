<?php

namespace App\Services;

use App\Models\User;

class GamificationService
{
    public function __construct(private readonly User $users)
    {
    }

    public function reward(int $userId, int $points, string $source, array $meta = []): void
    {
        $this->users->incrementPoints($userId, $points, $source, $meta);
    }
}
