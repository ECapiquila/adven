<?php

namespace App\Models;

use App\Core\Model;

class FamilyThread extends Model
{
    protected string $table = 'family_threads';

    public function recentForChurch(int $churchId): array
    {
        $stmt = $this->pdo()->prepare("SELECT * FROM {$this->table} WHERE church_id = :church ORDER BY updated_at DESC LIMIT 20");
        $stmt->execute(['church' => $churchId]);
        return $stmt->fetchAll();
    }
}
