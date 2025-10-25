<?php

declare(strict_types=1);

namespace App\Models;

use Core\Model;
use PDO;
use PDOException;

final class User extends Model
{
    public static function find(int $id): ?array
    {
        $statement = self::query('SELECT * FROM users WHERE id = :id LIMIT 1', ['id' => $id]);
        $user = $statement->fetch(PDO::FETCH_ASSOC);

        if ($user === false) {
            return null;
        }

        $user['roles'] = self::roles((int) $user['id']);

        return $user;
    }

    public static function findByEmail(string $email): ?array
    {
        $statement = self::query('SELECT * FROM users WHERE email = :email LIMIT 1', ['email' => $email]);
        $user = $statement->fetch(PDO::FETCH_ASSOC);

        if ($user === false) {
            return null;
        }

        $user['roles'] = self::roles((int) $user['id']);

        return $user;
    }

    public static function roles(int $userId): array
    {
        try {
            $statement = self::query('SELECT r.slug FROM roles r JOIN role_user ru ON ru.role_id = r.id WHERE ru.user_id = :id', ['id' => $userId]);
            return $statement->fetchAll(PDO::FETCH_COLUMN) ?: [];
        } catch (PDOException|\RuntimeException) {
            return [];
        }
    }
}
