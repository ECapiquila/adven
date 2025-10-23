<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Http\Request;
use App\Core\Http\Response;
use App\Core\Security\Csrf;
use App\Core\Support\Config;
use App\Core\Support\Container;
use App\Core\Validation\Validator;
use App\Core\View\View;
use App\Core\Database\Connection;
use Database\DatabaseManager;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\GeoSeeder;

class InstallController extends Controller
{
    private string $lockPath;

    public function __construct(
        Container $container,
        View $view,
        Response $response,
        private readonly Validator $validator,
        private readonly Request $request,
        private readonly Connection $connection
    ) {
        parent::__construct($container, $view, $response);
        $this->lockPath = __DIR__ . '/../../storage/install.lock';
    }

    public function index(): string
    {
        if ($this->isLocked()) {
            return $this->renderLayout('Instalador', 'install.locked');
        }
        return $this->renderLayout('Instalador', 'install.index');
    }

    public function run(): Response
    {
        if ($this->isLocked()) {
            return $this->response->setContent($this->renderLayout('Instalador', 'install.locked'));
        }

        $data = $this->request->all();
        if (!Csrf::validate($data['_token'] ?? null)) {
            return $this->response->setContent($this->renderLayout('Instalador', 'install.index', [
                'errors' => ['token' => ['Sessão expirada.']],
            ]));
        }

        $errors = $this->validator->validate($data, [
            'app_name' => 'required',
            'db_driver' => 'required',
            'db_host' => 'required',
            'db_name' => 'required',
            'db_user' => 'required',
        ]);

        if ($errors) {
            return $this->response->setContent($this->renderLayout('Instalador', 'install.index', [
                'errors' => $errors,
                'old' => $data,
            ]));
        }

        $this->writeEnv($data);
        Config::load(__DIR__ . '/../../config');

        $manager = new DatabaseManager($this->connection);
        $manager->runMigrations();

        (new GeoSeeder($this->connection))->run();
        (new DatabaseSeeder($this->connection))->run();

        if (!is_dir(__DIR__ . '/../../public/uploads')) {
            mkdir(__DIR__ . '/../../public/uploads', 0775, true);
        }

        $this->lock();

        return $this->response->setContent($this->renderLayout('Instalador', 'install.done', [
            'appName' => $data['app_name'],
            'adminEmail' => 'admin@demo.com',
        ]));
    }

    private function writeEnv(array $data): void
    {
        $env = [
            'APP_NAME' => $data['app_name'],
            'DB_CONNECTION' => $data['db_driver'],
            'DB_HOST' => $data['db_host'],
            'DB_PORT' => $data['db_port'] ?? '3306',
            'DB_DATABASE' => $data['db_name'],
            'DB_USERNAME' => $data['db_user'],
            'DB_PASSWORD' => $data['db_password'] ?? '',
        ];
        $content = "";
        foreach ($env as $key => $value) {
            $content .= $key . '="' . addslashes($value) . ""\n";
            putenv($key . '=' . $value);
            $_ENV[$key] = $value;
        }
        file_put_contents(__DIR__ . '/../../.env', $content);
    }

    private function isLocked(): bool
    {
        return file_exists($this->lockPath);
    }

    private function lock(): void
    {
        if (!is_dir(dirname($this->lockPath))) {
            mkdir(dirname($this->lockPath), 0775, true);
        }
        file_put_contents($this->lockPath, (string) time());
    }
}
