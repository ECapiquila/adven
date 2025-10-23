<?php

namespace App\Policies;

class PrayerPolicy extends BasePolicy
{
    public function view(array $user, array $prayer): bool
    {
        if ($prayer['privacy'] === 'publico') {
            return $this->inScope($user, (int) $prayer['scope_id']);
        }

        return in_array($user['role'] ?? '', ['pastor', 'anciao'], true) && $this->inScope($user, (int) $prayer['scope_id']);
    }
}
