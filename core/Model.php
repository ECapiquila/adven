<?php

declare(strict_types=1);

namespace Core;

use PDO;
use PDOStatement;

abstract class Model
{
    protected static function query(string $sql, array $params = []): PDOStatement
    {
        $pdo = Database::connection();
        $statement = $pdo->prepare($sql);
        $statement->execute($params);

        return $statement;
    }

    protected static function pdo(): PDO
    {
        return Database::connection();
    }
}
