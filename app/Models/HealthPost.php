<?php

namespace App\Models;

use App\Core\Model;

class HealthPost extends Model
{
    protected string $table = 'health_posts';

    public function latestApproved(): array
    {
        $stmt = $this->pdo()->query("SELECT * FROM {$this->table} WHERE status = 'approved' ORDER BY created_at DESC LIMIT 20");
        return $stmt->fetchAll();
    }
}
