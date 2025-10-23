<?php

namespace App\Services;

use App\Core\Support\Config;

class ThemeService
{
    public function palette(): array
    {
        return Config::get('theme.palette');
    }
}
