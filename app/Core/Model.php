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

    public function all(): array
    {
        $stmt = $this->pdo()->query("SELECT * FROM {$this->table}");
        return $stmt->fetchAll();
    }

    public function create(array $attributes): int
    {
        $columns = array_keys($attributes);
        $placeholders = array_map(fn($column) => ':' . $column, $columns);
        $sql = sprintf(
            'INSERT INTO %s (%s) VALUES (%s)',
            $this->table,
            implode(',', $columns),
            implode(',', $placeholders)
        );
        $stmt = $this->pdo()->prepare($sql);
        $stmt->execute($attributes);

        return (int) $this->pdo()->lastInsertId();
    }

    public function update(int $id, array $attributes): void
    {
        $set = [];
        foreach ($attributes as $column => $value) {
            $set[] = sprintf('%s = :%s', $column, $column);
        }

        $sql = sprintf('UPDATE %s SET %s WHERE id = :id', $this->table, implode(',', $set));
        $stmt = $this->pdo()->prepare($sql);
        $attributes['id'] = $id;
        $stmt->execute($attributes);
    }

    public function where(string $column, $value): array
    {
        $sql = sprintf('SELECT * FROM %s WHERE %s = :value', $this->table, $column);
        $stmt = $this->pdo()->prepare($sql);
        $stmt->execute(['value' => $value]);
        return $stmt->fetchAll();
    }

    public function firstWhere(string $column, $value): ?array
    {
        $sql = sprintf('SELECT * FROM %s WHERE %s = :value LIMIT 1', $this->table, $column);
        $stmt = $this->pdo()->prepare($sql);
        $stmt->execute(['value' => $value]);
        $result = $stmt->fetch();
        return $result ?: null;
    }
}
