<?php

declare(strict_types=1);

namespace App\Controllers;

use Core\Controller;
use Core\Http\Request;

final class HomeController extends Controller
{
    public function landing(Request $request): string
    {
        return $this->view('pages/landing', [
            'title' => 'Sabbath Connect',
        ]);
    }
}
