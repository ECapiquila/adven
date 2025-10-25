<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Support\Auth;
use Core\Controller;
use Core\Http\Request;

final class PrayerController extends Controller
{
    public function index(Request $request): string
    {
        return $this->view('pages/oracoes/index', [
            'title' => 'Pedidos de Oração',
            'prayers' => [],
        ]);
    }

    public function show(Request $request, string $id): string
    {
        return $this->view('pages/oracoes/show', [
            'title' => 'Pedido de Oração',
            'prayerId' => $id,
        ]);
    }

    public function create(Request $request): string
    {
        return $this->view('pages/oracoes/create', [
            'title' => 'Novo Pedido de Oração',
        ]);
    }

    public function manage(Request $request): string
    {
        Auth::requireLogin();
        if (!Auth::hasRole('admin') && !Auth::hasRole('pastor') && !Auth::hasRole('anciao')) {
            flash('error', 'Precisa de permissão pastoral para aceder à fila de orações.');
            $this->redirect('/oracoes');
        }

        return $this->view('pages/oracoes/manage', [
            'title' => 'Fila de Oração',
            'prayers' => [],
        ]);
    }
}
