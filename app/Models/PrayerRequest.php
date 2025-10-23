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

    public function create(array $data): int
    {
        $sql = "INSERT INTO {$this->table} (author_id, scope_type, scope_id, title, category, body, privacy, status, created_at, updated_at)
                VALUES (:author_id, :scope_type, :scope_id, :title, :category, :body, :privacy, :status, NOW(), NOW())";
        $stmt = $this->pdo()->prepare($sql);
        $stmt->execute($data);
        return (int) $this->pdo()->lastInsertId();
    }
}
