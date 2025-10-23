<?php

use App\Models\Notification;
use App\Models\QueueJob;
use App\Models\User;
use App\Services\PrayerService;
use App\Services\RbacService;

require_once __DIR__ . '/../TestCase.php';

class PrayerGamificationTest extends TestCase
{
    public function testPrayerCreationRewardsAndQueues(): void
    {
        /** @var RbacService $rbac */
        $rbac = $this->container->make(RbacService::class);
        $rbac->assignRole(1, 1, 'Pastor', 1);

        /** @var User $users */
        $users = $this->container->make(User::class);
        $authorId = $users->createFromRegistration([
            'name' => 'Autor Oração',
            'email' => 'autor@example.com',
            'phone' => '+244900000333',
            'password' => password_hash('Senha123', PASSWORD_BCRYPT),
            'church_id' => 1,
            'district_id' => 1,
            'region_id' => 1,
        ]);

        /** @var PrayerService $prayers */
        $prayers = $this->container->make(PrayerService::class);
        $prayerId = $prayers->create([
            'author_id' => $authorId,
            'scope_type' => 'church',
            'scope_id' => 1,
            'title' => 'Interceder pela Família',
            'category' => 'familia',
            'body' => 'Pedido de oração pela família.',
            'privacy' => 'publico',
            'status' => 'aberto',
        ]);

        $this->assertTrue($prayerId > 0);

        /** @var Notification $notifications */
        $notifications = $this->container->make(Notification::class);
        $leaderNotifications = $notifications->unreadForUser(1);
        $this->assertNotEmpty($leaderNotifications);

        /** @var QueueJob $jobs */
        $jobs = $this->container->make(QueueJob::class);
        $emailJobs = $jobs->where('queue', 'emails');
        $this->assertNotEmpty($emailJobs);

        $refreshedAuthor = $users->find($authorId);
        $this->assertTrue($refreshedAuthor['points'] >= 10);
    }
}
