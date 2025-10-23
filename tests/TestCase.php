<?php

use App\Core\Database\Connection;
use App\Core\Support\Container;
use App\Core\Validation\Validator;
use App\Models\User;
use App\Models\PrayerRequest;
use App\Models\PrayerResponse;
use App\Models\PrayerReaction;
use App\Models\PrayerFollower;
use App\Models\Notification;
use App\Models\QueueJob;
use App\Models\Region;
use App\Models\District;
use App\Models\Church;
use App\Models\ChurchRole;
use App\Models\ChurchRoleAssignment;
use App\Models\AuditLog;
use App\Models\FamilyThread;
use App\Models\FamilyMessage;
use App\Models\FamilyStaff;
use App\Models\ChatThread;
use App\Models\ChatParticipant;
use App\Models\ChatMessage;
use App\Models\HealthPost;
use App\Services\NotificationService;
use App\Services\QueueService;
use App\Services\GamificationService;
use App\Services\RbacService;
use App\Services\PrayerService;
use App\Services\FamilyService;
use App\Services\ChatService;
use App\Core\Auth\AuthManager;
use Database\DatabaseManager;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\GeoSeeder;
use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected Container $container;
    protected Connection $connection;

    protected function setUp(): void
    {
        parent::setUp();
        $this->connection = new Connection();
        $manager = new DatabaseManager($this->connection);
        $manager->runMigrations();
        (new GeoSeeder($this->connection))->run();
        (new DatabaseSeeder($this->connection))->run();

        $this->container = new Container();
        $this->container->singleton(Connection::class, fn() => $this->connection);
        $this->container->singleton(Validator::class, fn() => new Validator());
        $this->container->singleton(User::class, fn() => new User($this->connection));
        $this->container->singleton(PrayerRequest::class, fn() => new PrayerRequest($this->connection));
        $this->container->singleton(PrayerResponse::class, fn() => new PrayerResponse($this->connection));
        $this->container->singleton(PrayerReaction::class, fn() => new PrayerReaction($this->connection));
        $this->container->singleton(PrayerFollower::class, fn() => new PrayerFollower($this->connection));
        $this->container->singleton(Notification::class, fn() => new Notification($this->connection));
        $this->container->singleton(QueueJob::class, fn() => new QueueJob($this->connection));
        $this->container->singleton(Region::class, fn() => new Region($this->connection));
        $this->container->singleton(District::class, fn() => new District($this->connection));
        $this->container->singleton(Church::class, fn() => new Church($this->connection));
        $this->container->singleton(ChurchRole::class, fn() => new ChurchRole($this->connection));
        $this->container->singleton(ChurchRoleAssignment::class, fn() => new ChurchRoleAssignment($this->connection));
        $this->container->singleton(AuditLog::class, fn() => new AuditLog($this->connection));
        $this->container->singleton(FamilyThread::class, fn() => new FamilyThread($this->connection));
        $this->container->singleton(FamilyMessage::class, fn() => new FamilyMessage($this->connection));
        $this->container->singleton(FamilyStaff::class, fn() => new FamilyStaff($this->connection));
        $this->container->singleton(ChatThread::class, fn() => new ChatThread($this->connection));
        $this->container->singleton(ChatParticipant::class, fn() => new ChatParticipant($this->connection));
        $this->container->singleton(ChatMessage::class, fn() => new ChatMessage($this->connection));
        $this->container->singleton(HealthPost::class, fn() => new HealthPost($this->connection));
        $this->container->singleton(AuthManager::class, fn($c) => new AuthManager($c->make(User::class)));
        $this->container->singleton(NotificationService::class, fn($c) => new NotificationService($c->make(Notification::class)));
        $this->container->singleton(QueueService::class, fn($c) => new QueueService($c->make(QueueJob::class)));
        $this->container->singleton(GamificationService::class, fn($c) => new GamificationService($c->make(User::class)));
        $this->container->singleton(RbacService::class, fn($c) => new RbacService(
            $c->make(User::class),
            $c->make(ChurchRole::class),
            $c->make(ChurchRoleAssignment::class),
            $c->make(AuditLog::class),
            $c->make(Church::class),
            $c->make(District::class),
            $c->make(Region::class),
        ));
        $this->container->singleton(PrayerService::class, fn($c) => new PrayerService(
            $c->make(PrayerRequest::class),
            $c->make(PrayerResponse::class),
            $c->make(PrayerReaction::class),
            $c->make(PrayerFollower::class),
            $c->make(NotificationService::class),
            $c->make(RbacService::class),
            $c->make(QueueService::class),
            $c->make(GamificationService::class),
        ));
        $this->container->singleton(FamilyService::class, fn($c) => new FamilyService(
            $c->make(FamilyThread::class),
            $c->make(FamilyMessage::class),
            $c->make(FamilyStaff::class),
            $c->make(NotificationService::class),
            $c->make(QueueService::class),
            $c->make(GamificationService::class),
            $c->make(RbacService::class),
        ));
        $this->container->singleton(ChatService::class, fn($c) => new ChatService(
            $c->make(ChatThread::class),
            $c->make(ChatParticipant::class),
            $c->make(ChatMessage::class),
        ));
    }
}
