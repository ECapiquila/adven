<?php

use App\Controllers\AuthController;
use App\Controllers\FamilyController;
use App\Controllers\FeedController;
use App\Controllers\HealthController;
use App\Controllers\HomeController;
use App\Controllers\AdminController;
use App\Controllers\PrayerController;
use App\Controllers\NotificationController;
use App\Controllers\ChatController;
use App\Controllers\InstallController;

$router->get('/', [HomeController::class, 'landing']);
$router->get('/feed', [FeedController::class, 'index']);
$router->get('/login', [AuthController::class, 'loginForm']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/register', [AuthController::class, 'registerForm']);
$router->post('/register', [AuthController::class, 'register']);
$router->get('/logout', [AuthController::class, 'logout']);

$router->get('/install', [InstallController::class, 'index']);
$router->post('/install', [InstallController::class, 'run']);

$router->get('/oracoes', [PrayerController::class, 'index']);
$router->get('/oracoes/criar', [PrayerController::class, 'createForm']);
$router->post('/oracoes', [PrayerController::class, 'store']);
$router->get('/oracao/{id}', [PrayerController::class, 'show']);
$router->post('/oracao/{id}/responder', [PrayerController::class, 'respond']);
$router->post('/oracao/{id}/reagir', [PrayerController::class, 'react']);

$router->get('/notificacoes', [NotificationController::class, 'index']);

$router->get('/chat', [ChatController::class, 'index']);
$router->post('/chat/enviar', [ChatController::class, 'send']);
$router->get('/chat/poll', [ChatController::class, 'poll']);

$router->get('/saude', [HealthController::class, 'index']);
$router->get('/saude/postar', [HealthController::class, 'createForm']);

$router->get('/familia', [FamilyController::class, 'index']);
$router->get('/familia/abrir-conversa', [FamilyController::class, 'createForm']);
$router->post('/familia', [FamilyController::class, 'store']);
$router->get('/familia/t/{id}', [FamilyController::class, 'show']);
$router->post('/familia/t/{id}/mensagem', [FamilyController::class, 'sendMessage']);

$router->get('/gerenciar', [AdminController::class, 'index']);
$router->post('/gerenciar/cargos', [AdminController::class, 'assignRole']);
$router->get('/gerenciar/cargos/{id}/revogar', [AdminController::class, 'revokeRole']);
