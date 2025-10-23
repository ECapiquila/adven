<?php

namespace App\Policies;

class BasePolicy
{
    protected function inScope(array $user, ?int $targetChurchId): bool
    {
        if ($targetChurchId === null) {
            return true;
        }

        return ($user['church_id'] ?? null) === $targetChurchId;
    }
}
