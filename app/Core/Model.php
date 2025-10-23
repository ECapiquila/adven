<?php

namespace App\Core;

use App\Core\Database\Connection;
use PDO;

abstract class Model
{
    protected string $table;

    public function __construct(protected readonly Connection $connection)
    {
    }

    protected function pdo(): PDO
    {
        return $this->connection->getPdo();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->pdo()->prepare("SELECT * FROM {$this->table} WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }
}
