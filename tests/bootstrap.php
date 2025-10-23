<?php
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../app/Core/Autoloader.php';
require __DIR__ . '/../vendor/phpunit/phpunit/src/Framework/TestCase.php';

use App\Core\Autoloader;
use App\Core\Support\Config;

$autoloader = new Autoloader();
$autoloader->addNamespace('App', __DIR__ . '/../app');
$autoloader->addNamespace('Database', __DIR__ . '/../database');
$autoloader->addNamespace('Database\\Seeders', __DIR__ . '/../database/seeders');
$autoloader->register();

putenv('DB_CONNECTION=sqlite');
putenv('DB_DATABASE=:memory:');
Config::load(__DIR__ . '/../config');
