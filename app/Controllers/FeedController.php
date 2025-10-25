<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Support\Auth;
use Core\Controller;
use Core\Http\Request;

final class FeedController extends Controller
{
    public function index(Request $request): string
    {
        Auth::requireLogin();

        return $this->view('pages/feed', [
            'title' => 'Feed',
            'user' => Auth::user(),
        ]);
    }
}
