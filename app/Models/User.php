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

    public function createFromRegistration(array $data): int
    {
        return $this->create([
            'name' => $data['name'],
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'] ?? null,
            'password' => $data['password'],
            'role' => $data['role'] ?? 'membro',
            'is_adventist' => $data['is_adventist'] ?? 0,
            'gender' => $data['gender'] ?? null,
            'country' => $data['country'] ?? 'Angola',
            'province' => $data['province'] ?? null,
            'municipio' => $data['municipio'] ?? null,
            'bairro' => $data['bairro'] ?? null,
            'region_id' => $data['region_id'] ?? null,
            'district_id' => $data['district_id'] ?? null,
            'church_id' => $data['church_id'] ?? null,
            'points' => $data['points'] ?? 0,
        ]);
    }

    public function incrementPoints(int $userId, int $points, string $source, array $meta = []): void
    {
        $stmt = $this->pdo()->prepare('UPDATE users SET points = points + :points WHERE id = :id');
        $stmt->execute(['points' => $points, 'id' => $userId]);

        $logStmt = $this->pdo()->prepare('INSERT INTO user_points_log (user_id, source, points, meta_json, created_at) VALUES (:u, :s, :p, :meta, CURRENT_TIMESTAMP)');
        $logStmt->execute([
            'u' => $userId,
            's' => $source,
            'p' => $points,
            'meta' => json_encode($meta, JSON_UNESCAPED_UNICODE),
        ]);
    }

    public function roles(int $userId): array
    {
        $sql = 'SELECT cr.name, cra.* FROM church_role_assignments cra JOIN church_roles cr ON cr.id = cra.role_id WHERE cra.user_id = :user AND cra.revoked_at IS NULL';
        $stmt = $this->pdo()->prepare($sql);
        $stmt->execute(['user' => $userId]);
        return $stmt->fetchAll();
    }
}
