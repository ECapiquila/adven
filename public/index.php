<?php

use App\Core\Application;
use App\Core\Autoloader;
use App\Core\Auth\AuthManager;
use App\Core\Database\Connection;
use App\Core\Http\Request;
use App\Core\Http\Response;
use App\Core\Routing\Router;
use App\Core\Support\Config;
use App\Core\Support\Container;
use App\Core\Validation\Validator;
use App\Core\View\View;
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
use App\Models\FamilyThread;
use App\Models\FamilyMessage;
use App\Models\FamilyStaff;
use App\Models\ChatThread;
use App\Models\ChatParticipant;
use App\Models\ChatMessage;
use App\Models\AuditLog;
use App\Services\PrayerService;
use App\Services\NotificationService;
use App\Services\QueueService;
use App\Services\GamificationService;
use App\Services\RbacService;
use App\Services\FamilyService;
use App\Services\ChatService;
use App\Models\HealthPost;

require __DIR__ . '/../vendor/autoload.php';

$autoloader = new Autoloader();
$autoloader->addNamespace('App', __DIR__ . '/../app');
$autoloader->addNamespace('Database', __DIR__ . '/../database');
$autoloader->addNamespace('Database\\Seeders', __DIR__ . '/../database/seeders');
$autoloader->register();

Config::load(__DIR__ . '/../config');

$container = new Container();
$container->singleton(Request::class, fn() => new Request());
$container->singleton(Response::class, fn() => new Response());
$container->singleton(View::class, fn() => new View(__DIR__ . '/../resources/views'));
$container->singleton(Connection::class, fn() => new Connection());
$container->singleton(Validator::class, fn() => new Validator());
$container->singleton(User::class, fn($c) => new User($c->make(Connection::class)));
$container->singleton(PrayerRequest::class, fn($c) => new PrayerRequest($c->make(Connection::class)));
$container->singleton(PrayerResponse::class, fn($c) => new PrayerResponse($c->make(Connection::class)));
$container->singleton(PrayerReaction::class, fn($c) => new PrayerReaction($c->make(Connection::class)));
$container->singleton(PrayerFollower::class, fn($c) => new PrayerFollower($c->make(Connection::class)));
$container->singleton(Notification::class, fn($c) => new Notification($c->make(Connection::class)));
$container->singleton(QueueJob::class, fn($c) => new QueueJob($c->make(Connection::class)));
$container->singleton(Region::class, fn($c) => new Region($c->make(Connection::class)));
$container->singleton(District::class, fn($c) => new District($c->make(Connection::class)));
$container->singleton(Church::class, fn($c) => new Church($c->make(Connection::class)));
$container->singleton(ChurchRole::class, fn($c) => new ChurchRole($c->make(Connection::class)));
$container->singleton(ChurchRoleAssignment::class, fn($c) => new ChurchRoleAssignment($c->make(Connection::class)));
$container->singleton(AuditLog::class, fn($c) => new AuditLog($c->make(Connection::class)));
$container->singleton(FamilyThread::class, fn($c) => new FamilyThread($c->make(Connection::class)));
$container->singleton(FamilyMessage::class, fn($c) => new FamilyMessage($c->make(Connection::class)));
$container->singleton(FamilyStaff::class, fn($c) => new FamilyStaff($c->make(Connection::class)));
$container->singleton(ChatThread::class, fn($c) => new ChatThread($c->make(Connection::class)));
$container->singleton(ChatParticipant::class, fn($c) => new ChatParticipant($c->make(Connection::class)));
$container->singleton(ChatMessage::class, fn($c) => new ChatMessage($c->make(Connection::class)));
$container->singleton(HealthPost::class, fn($c) => new HealthPost($c->make(Connection::class)));
$container->singleton(AuthManager::class, fn($c) => new AuthManager($c->make(User::class)));
$container->singleton(NotificationService::class, fn($c) => new NotificationService($c->make(Notification::class)));
$container->singleton(QueueService::class, fn($c) => new QueueService($c->make(QueueJob::class)));
$container->singleton(GamificationService::class, fn($c) => new GamificationService($c->make(User::class)));
$container->singleton(RbacService::class, fn($c) => new RbacService(
    $c->make(User::class),
    $c->make(ChurchRole::class),
    $c->make(ChurchRoleAssignment::class),
    $c->make(AuditLog::class),
    $c->make(Church::class),
    $c->make(District::class),
    $c->make(Region::class),
));
$container->singleton(PrayerService::class, fn($c) => new PrayerService(
    $c->make(PrayerRequest::class),
    $c->make(PrayerResponse::class),
    $c->make(PrayerReaction::class),
    $c->make(PrayerFollower::class),
    $c->make(NotificationService::class),
    $c->make(RbacService::class),
    $c->make(QueueService::class),
    $c->make(GamificationService::class),
));
$container->singleton(FamilyService::class, fn($c) => new FamilyService(
    $c->make(FamilyThread::class),
    $c->make(FamilyMessage::class),
    $c->make(FamilyStaff::class),
    $c->make(NotificationService::class),
    $c->make(QueueService::class),
    $c->make(GamificationService::class),
    $c->make(RbacService::class),
));
$container->singleton(ChatService::class, fn($c) => new ChatService(
    $c->make(ChatThread::class),
    $c->make(ChatParticipant::class),
    $c->make(ChatMessage::class),
));

$router = new Router($container);

require __DIR__ . '/../routes/web.php';

$app = new Application($router, $container->make(Request::class), $container->make(Response::class), $container);
$app->run();
