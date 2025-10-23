<?php

namespace App\Services;

use App\Models\HealthPost;

class HealthService
{
    public function __construct(private readonly HealthPost $healthPost)
    {
    }

    public function feed(): array
    {
        return $this->healthPost->latestApproved();
    }
}
