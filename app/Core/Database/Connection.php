<?php

namespace App\Core\Database;

use App\Core\Support\Config;
use PDO;
use PDOException;

class Connection
{
    private ?PDO $pdo = null;

    public function getPdo(): PDO
    {
        if ($this->pdo === null) {
            $config = Config::get('database');
            $driver = $config['driver'] ?? 'mysql';
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];

            try {
                if ($driver === 'sqlite') {
                    $database = $config['database'] ?? ':memory:';
                    $dsn = $database === ':memory:'
                        ? 'sqlite::memory:'
                        : 'sqlite:' . $database;
                    $this->pdo = new PDO($dsn, null, null, $options);
                } else {
                    $charset = $config['charset'] ?? 'utf8mb4';
                    $dsn = sprintf(
                        'mysql:host=%s;port=%s;dbname=%s;charset=%s',
                        $config['host'],
                        $config['port'] ?? '3306',
                        $config['database'],
                        $charset
                    );
                    $this->pdo = new PDO($dsn, $config['username'], $config['password'], $options);
                }
            } catch (PDOException $e) {
                throw new \RuntimeException('Database connection failed: ' . $e->getMessage());
            }
        }

        return $this->pdo;
    }
}
