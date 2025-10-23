<?php

namespace App\Core\View;

use App\Core\Support\Config;

class View
{
    public function __construct(private readonly string $basePath)
    {
    }

    public function render(string $template, array $data = []): string
    {
        $file = $this->basePath . '/' . str_replace('.', '/', $template) . '.php';

        if (!is_file($file)) {
            throw new \RuntimeException("View {$template} not found");
        }

        extract($data, EXTR_SKIP);
        $appName = Config::get('app.name', 'Rede Adventista');

        ob_start();
        include $file;

        return (string) ob_get_clean();
    }
}
