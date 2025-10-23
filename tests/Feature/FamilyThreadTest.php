<?php

use App\Models\FamilyStaff;
use App\Models\Notification;
use App\Models\QueueJob;
use App\Models\User;
use App\Services\FamilyService;

require_once __DIR__ . '/../TestCase.php';

class FamilyThreadTest extends TestCase
{
    public function testFamilyThreadNotifiesStaff(): void
    {
        /** @var User $users */
        $users = $this->container->make(User::class);
        $staffId = $users->createFromRegistration([
            'name' => 'Diretora Família',
            'email' => 'familia@example.com',
            'phone' => '+244900000666',
            'password' => password_hash('Senha123', PASSWORD_BCRYPT),
            'church_id' => 1,
        ]);

        /** @var FamilyStaff $familyStaff */
        $familyStaff = $this->container->make(FamilyStaff::class);
        $familyStaff->create([
            'church_id' => 1,
            'user_id' => $staffId,
            'role' => 'diretor',
            'assigned_by' => 1,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        $authorId = $users->createFromRegistration([
            'name' => 'Membro Família',
            'email' => 'membro@example.com',
            'phone' => '+244900000777',
            'password' => password_hash('Senha123', PASSWORD_BCRYPT),
            'church_id' => 1,
        ]);

        /** @var FamilyService $family */
        $family = $this->container->make(FamilyService::class);
        $threadId = $family->createThread([
            'author_id' => $authorId,
            'church_id' => 1,
            'subject' => 'Aconselhamento',
            'body' => 'Precisamos de orientação familiar.',
            'privacy' => 'pastor_equipe',
            'status' => 'aberto',
        ]);

        $this->assertTrue($threadId > 0);

        /** @var Notification $notifications */
        $notifications = $this->container->make(Notification::class);
        $staffNotifications = $notifications->unreadForUser($staffId);
        $this->assertNotEmpty($staffNotifications);

        /** @var QueueJob $jobs */
        $jobs = $this->container->make(QueueJob::class);
        $emailJobs = $jobs->where('job_type', 'FamilyThreadOpened');
        $this->assertNotEmpty($emailJobs);
    }
}
