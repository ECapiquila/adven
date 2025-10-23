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
            $dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8mb4', $config['host'], $config['database']);
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];

            try {
                $this->pdo = new PDO($dsn, $config['username'], $config['password'], $options);
            } catch (PDOException $e) {
                throw new \RuntimeException('Database connection failed: ' . $e->getMessage());
            }
        }

        return $this->pdo;
    }
}
