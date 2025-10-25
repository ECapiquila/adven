<?php

declare(strict_types=1);

use App\Controllers\AuthController;
use App\Controllers\FeedController;
use App\Controllers\HomeController;
use App\Controllers\NotificationController;
use App\Controllers\PrayerController;
use App\Controllers\PwaController;
use App\Controllers\SongController;

$router->get('/', [HomeController::class, 'landing']);
$router->match(['GET', 'POST'], '/login', [AuthController::class, 'login']);
$router->get('/register', [AuthController::class, 'register']);
$router->get('/logout', [AuthController::class, 'logout']);

$router->get('/feed', [FeedController::class, 'index']);

$router->get('/louvores', [SongController::class, 'index']);
$router->match(['GET', 'POST'], '/louvores/novo', [SongController::class, 'create']);
$router->get('/gerenciar/louvores', [SongController::class, 'manage']);

$router->get('/oracoes', [PrayerController::class, 'index']);
$router->match(['GET', 'POST'], '/oracoes/novo', [PrayerController::class, 'create']);
$router->get('/oracoes/{id}', [PrayerController::class, 'show']);
$router->get('/gerenciar/oracoes', [PrayerController::class, 'manage']);

$router->get('/notificacoes', [NotificationController::class, 'index']);
$router->get('/api/notifications/unread_count', [NotificationController::class, 'unreadCount']);
$router->get('/api/notifications/latest', [NotificationController::class, 'latest']);

$router->get('/manifest.webmanifest', [PwaController::class, 'manifest']);
