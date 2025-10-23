<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\Church;
use App\Models\ChurchRole;
use App\Models\ChurchRoleAssignment;
use App\Models\District;
use App\Models\Region;
use App\Models\User;
use RuntimeException;

class RbacService
{
    public function __construct(
        private readonly User $users,
        private readonly ChurchRole $roles,
        private readonly ChurchRoleAssignment $assignments,
        private readonly AuditLog $auditLog,
        private readonly Church $church,
        private readonly District $district,
        private readonly Region $region,
    ) {
    }

    public function userHasRole(int $userId, string|array $roleNames): bool
    {
        $rolesToCheck = (array) $roleNames;
        $user = $this->users->find($userId);
        if (!$user) {
            return false;
        }

        if (in_array($user['role'], $rolesToCheck, true)) {
            return true;
        }

        $assigned = $this->users->roles($userId);
        foreach ($assigned as $role) {
            if (in_array($role['name'], $rolesToCheck, true)) {
                return true;
            }
        }

        return false;
    }

    public function leadersForChurch(int $churchId, array $roleNames): array
    {
        $hierarchy = $this->buildHierarchy($churchId);
        $levels = [
            ['column' => 'church_id', 'value' => $hierarchy['church_id']],
            ['column' => 'district_id', 'value' => $hierarchy['district_id']],
            ['column' => 'region_id', 'value' => $hierarchy['region_id']],
            ['column' => 'association_id', 'value' => $hierarchy['association_id']],
        ];

        $leaders = [];
        foreach ($levels as $level) {
            if (!$level['value']) {
                continue;
            }

            $leaders = array_merge($leaders, $this->assignments->byScopeAndRoles($level['column'], (int) $level['value'], $roleNames));
        }

        return $leaders;
    }

    public function assignRole(int $actingUserId, int $userId, string $roleName, int $churchId): int
    {
        if (!$this->userHasRole($actingUserId, ['admin', 'Presidente', 'Pastor'])) {
            throw new RuntimeException('Utilizador sem permissão para atribuir cargos.');
        }

        $role = $this->findRoleByName($roleName);
        $hierarchy = $this->buildHierarchy($churchId);

        $payload = [
            'user_id' => $userId,
            'role_id' => $role['id'],
            'assigned_by' => $actingUserId,
            'association_id' => $hierarchy['association_id'],
            'region_id' => $hierarchy['region_id'],
            'district_id' => $hierarchy['district_id'],
            'church_id' => $hierarchy['church_id'],
            'created_at' => date('Y-m-d H:i:s'),
        ];

        $assignmentId = $this->assignments->assign($payload);
        $this->auditLog->create([
            'user_id' => $actingUserId,
            'action' => 'assign_role',
            'target_type' => 'user',
            'target_id' => $userId,
            'meta_json' => json_encode(['role' => $roleName, 'church_id' => $churchId], JSON_UNESCAPED_UNICODE),
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return $assignmentId;
    }

    public function revokeRole(int $actingUserId, int $assignmentId): void
    {
        if (!$this->userHasRole($actingUserId, ['admin', 'Presidente', 'Pastor'])) {
            throw new RuntimeException('Utilizador sem permissão para revogar cargos.');
        }

        $this->assignments->revoke($assignmentId);
        $this->auditLog->create([
            'user_id' => $actingUserId,
            'action' => 'revoke_role',
            'target_type' => 'church_role_assignment',
            'target_id' => $assignmentId,
            'meta_json' => json_encode([], JSON_UNESCAPED_UNICODE),
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    private function findRoleByName(string $roleName): array
    {
        $role = $this->roles->firstWhere('name', $roleName);
        if (!$role) {
            throw new RuntimeException('Cargo inválido: ' . $roleName);
        }

        return $role;
    }

    private function buildHierarchy(int $churchId): array
    {
        $church = $this->church->find($churchId);
        if (!$church) {
            throw new RuntimeException('Igreja não encontrada.');
        }

        $district = $church['district_id'] ? $this->district->find((int) $church['district_id']) : null;
        $region = $district && isset($district['region_id']) ? $this->region->find((int) $district['region_id']) : null;
        $associationId = $region['parent_id'] ?? null;

        return [
            'church_id' => $churchId,
            'district_id' => $district['id'] ?? null,
            'region_id' => $region['id'] ?? null,
            'association_id' => $associationId,
        ];
    }
}
