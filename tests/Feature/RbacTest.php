<?php

use App\Models\User;
use App\Services\RbacService;

require_once __DIR__ . '/../TestCase.php';

class RbacTest extends TestCase
{
    public function testAssignRoleCreatesHierarchyEntry(): void
    {
        /** @var User $users */
        $users = $this->container->make(User::class);
        $memberId = $users->createFromRegistration([
            'name' => 'Pastor Novo',
            'email' => 'pastor@example.com',
            'phone' => '+244900000222',
            'password' => password_hash('Senha123', PASSWORD_BCRYPT),
            'gender' => 'm',
            'is_adventist' => 1,
            'country' => 'Angola',
            'region_id' => 1,
            'district_id' => 1,
            'church_id' => 1,
        ]);

        /** @var RbacService $rbac */
        $rbac = $this->container->make(RbacService::class);
        $assignmentId = $rbac->assignRole(1, $memberId, 'Pastor', 1);

        $this->assertTrue($assignmentId > 0, 'Assignment ID should be generated.');
        $leaders = $rbac->leadersForChurch(1, ['Pastor']);
        $this->assertNotEmpty($leaders);
        $this->assertEquals($memberId, (int) $leaders[0]['id']);
        $this->assertTrue($rbac->userHasRole($memberId, ['Pastor']));
    }
}
