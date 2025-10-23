<?php

namespace Database;

use App\Core\Database\Connection;
use App\Core\Support\Config;

class DatabaseManager
{
    public function __construct(private readonly Connection $connection)
    {
    }

    public function runMigrations(): void
    {
        $pdo = $this->connection->getPdo();
        $driver = Config::get('database.driver', 'mysql');

        if ($driver === 'sqlite') {
            $pdo->exec('PRAGMA foreign_keys = ON');
        }

        $files = glob(__DIR__ . '/migrations/*.sql');
        sort($files);

        foreach ($files as $file) {
            $sql = file_get_contents($file);
            $queries = $this->prepareStatements($sql, $driver);
            foreach ($queries as $statement) {
                if (trim($statement) === '') {
                    continue;
                }
                $pdo->exec($statement);
            }
        }
    }

    private function prepareStatements(string $sql, string $driver): array
    {
        if ($driver === 'sqlite') {
            $sql = preg_replace('/INT\s+UNSIGNED/i', 'INTEGER', $sql);
            $sql = preg_replace('/TINYINT\(1\)/i', 'INTEGER', $sql);
            $sql = preg_replace('/\bJSON\b/i', 'TEXT', $sql);
            $sql = str_replace('ON UPDATE CURRENT_TIMESTAMP', '', $sql);
            $sql = preg_replace('/ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;?/i', ';', $sql);
            $sql = preg_replace('/(INT|INTEGER)\s+AUTO_INCREMENT/i', 'INTEGER PRIMARY KEY AUTOINCREMENT', $sql);
            $sql = preg_replace('/INTEGER PRIMARY KEY AUTOINCREMENT PRIMARY KEY/', 'INTEGER PRIMARY KEY AUTOINCREMENT', $sql);
            $sql = preg_replace('/AUTOINCREMENT PRIMARY KEY/', 'AUTOINCREMENT', $sql);
            $sql = preg_replace('/;\s*;/', ';', $sql);
            $sql = preg_replace('/UNIQUE KEY [^\(]+\(([^\)]+)\)/i', 'UNIQUE($1)', $sql);
        }

        $statements = array_filter(array_map('trim', explode(';', $sql)));
        return array_map(fn($statement) => $statement . ';', $statements);
    }
}
