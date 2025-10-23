<?php

namespace App\Services;

use App\Models\FamilyThread;

class FamilyService
{
    public function __construct(private readonly FamilyThread $familyThread)
    {
    }

    public function conversations(int $churchId): array
    {
        return $this->familyThread->recentForChurch($churchId);
    }
}
