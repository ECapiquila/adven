<?php

namespace App\Services;

use App\Models\PrayerRequest;

class PrayerService
{
    public function __construct(private readonly PrayerRequest $prayerRequest)
    {
    }

    public function timeline(int $userId, int $scopeId): array
    {
        return $this->prayerRequest->recentForUserScope($userId, 'scope_id', $scopeId);
    }

    public function create(array $payload): int
    {
        return $this->prayerRequest->create($payload);
    }
}
