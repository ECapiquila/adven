<?php

namespace App\Models;

use App\Core\Model;

class PrayerRequest extends Model
{
    protected string $table = 'prayer_requests';

    public function recentForUserScope(int $userId, string $scopeColumn, int $scopeId): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$scopeColumn} = :scope_id ORDER BY created_at DESC LIMIT 25";
        $stmt = $this->pdo()->prepare($sql);
        $stmt->execute(['scope_id' => $scopeId]);
        return $stmt->fetchAll();
    }

    public function createRequest(array $data): int
    {
        return $this->create($data);
    }
}
