<?php

namespace App\Models;

use App\Core\Model;

class QueueJob extends Model
{
    protected string $table = 'queue_jobs';

    public function enqueue(array $data): int
    {
        return $this->create($data);
    }

    public function reserveNext(string $queue): ?array
    {
        $sql = 'SELECT * FROM queue_jobs WHERE queue = :queue AND reserved_at IS NULL AND failed_at IS NULL AND available_at <= CURRENT_TIMESTAMP ORDER BY id ASC LIMIT 1';
        $stmt = $this->pdo()->prepare($sql);
        $stmt->execute(['queue' => $queue]);
        $job = $stmt->fetch();
        if ($job) {
            $update = $this->pdo()->prepare('UPDATE queue_jobs SET reserved_at = CURRENT_TIMESTAMP, attempts = attempts + 1 WHERE id = :id');
            $update->execute(['id' => $job['id']]);
            $job['attempts']++;
        }
        return $job ?: null;
    }

    public function markCompleted(int $id): void
    {
        $stmt = $this->pdo()->prepare('UPDATE queue_jobs SET completed_at = CURRENT_TIMESTAMP WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }
}
