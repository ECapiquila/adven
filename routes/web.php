<?php

use App\Controllers\AuthController;
use App\Controllers\FamilyController;
use App\Controllers\FeedController;
use App\Controllers\HealthController;
use App\Controllers\HomeController;
use App\Controllers\PrayerController;

$router->get('/', [HomeController::class, 'landing']);
$router->get('/feed', [FeedController::class, 'index']);
$router->get('/login', [AuthController::class, 'loginForm']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/register', [AuthController::class, 'registerForm']);
$router->get('/logout', [AuthController::class, 'logout']);

$router->get('/oracoes', [PrayerController::class, 'index']);
$router->get('/oracoes/criar', [PrayerController::class, 'createForm']);
$router->post('/oracoes', [PrayerController::class, 'store']);
$router->get('/oracao/{id}', [PrayerController::class, 'show']);

$router->get('/saude', [HealthController::class, 'index']);
$router->get('/saude/postar', [HealthController::class, 'createForm']);

$router->get('/familia', [FamilyController::class, 'index']);
$router->get('/familia/abrir-conversa', [FamilyController::class, 'createForm']);
$router->get('/familia/t/{id}', [FamilyController::class, 'show']);
