<?php

namespace App\Services;

class PresenceService
{
    public function attendanceSummary(int $churchId): array
    {
        return ['presentes' => 0, 'ausentes' => 0];
    }
}
