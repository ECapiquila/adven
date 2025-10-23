<?php

namespace App\Models;

use App\Core\Model;

class User extends Model
{
    protected string $table = 'users';

    public function findByLogin(string $login): ?array
    {
        $stmt = $this->pdo()->prepare('SELECT * FROM users WHERE email = :login OR phone = :login LIMIT 1');
        $stmt->execute(['login' => $login]);
        $result = $stmt->fetch();
        return $result ?: null;
    }
}
