<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Support\Auth;
use Core\Controller;
use Core\Http\Request;

final class SongController extends Controller
{
    public function index(Request $request): string
    {
        return $this->view('pages/louvores/index', [
            'title' => 'Louvores',
            'songs' => [],
        ]);
    }

    public function create(Request $request): string
    {
        Auth::requireLogin();
        if (!Auth::hasRole('admin') && !Auth::hasRole('musico')) {
            flash('error', 'Precisa de permissão de músico ou administrador.');
            $this->redirect('/louvores');
        }

        return $this->view('pages/louvores/create', [
            'title' => 'Submeter Louvor',
        ]);
    }

    public function manage(Request $request): string
    {
        Auth::requireLogin();
        if (!Auth::hasRole('admin') && !Auth::hasRole('musico')) {
            flash('error', 'Apenas administradores e músicos podem gerir louvores.');
            $this->redirect('/louvores');
        }

        return $this->view('pages/louvores/manage', [
            'title' => 'Gerir Louvores',
            'songs' => [],
        ]);
    }
}
