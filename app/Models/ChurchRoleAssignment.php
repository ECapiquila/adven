<?php

namespace App\Models;

use App\Core\Model;

class ChurchRoleAssignment extends Model
{
    protected string $table = 'church_role_assignments';

    public function assign(array $data): int
    {
        return $this->create($data);
    }

    public function revoke(int $id): void
    {
        $stmt = $this->pdo()->prepare('UPDATE church_role_assignments SET revoked_at = CURRENT_TIMESTAMP WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }

    public function recentWithDetails(int $limit = 50): array
    {
        $sql = 'SELECT cra.*, u.name AS user_name, cr.name AS role_name FROM church_role_assignments cra
            JOIN users u ON u.id = cra.user_id
            JOIN church_roles cr ON cr.id = cra.role_id
            ORDER BY cra.created_at DESC LIMIT :limit';
        $stmt = $this->pdo()->prepare($sql);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function byScopeAndRoles(string $column, int $value, array $roleNames): array
    {
        $placeholders = implode(',', array_fill(0, count($roleNames), '?'));
        $sql = "SELECT u.*, cr.name AS role_name FROM church_role_assignments cra
            JOIN church_roles cr ON cr.id = cra.role_id
            JOIN users u ON u.id = cra.user_id
            WHERE cra.revoked_at IS NULL AND cr.name IN ({$placeholders}) AND cra.{$column} = ?";
        $stmt = $this->pdo()->prepare($sql);
        $params = array_merge($roleNames, [$value]);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}
