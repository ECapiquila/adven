<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Http\Response;
use App\Core\Support\Container;
use App\Core\View\View;
use App\Models\FamilyThread;
use App\Services\FamilyService;

class FamilyController extends Controller
{
    public function __construct(
        Container $container,
        View $view,
        Response $response,
        private readonly FamilyService $familyService,
        private readonly FamilyThread $familyThread
    ) {
        parent::__construct($container, $view, $response);
    }

    public function index(): string
    {
        $threads = $this->familyService->conversations($_SESSION['church_id'] ?? 1);
        return $this->renderLayout('Lar & Família', 'family.index', compact('threads'));
    }

    public function show(int $id): string
    {
        $thread = $this->familyThread->find($id) ?? [
            'status' => 'aberto',
            'privacy' => 'pastor_equipe',
            'body' => 'Conversa não encontrada.'
        ];
        $messages = [
            ['author' => 'Pastor', 'body' => 'Estamos a orar por si.', 'created_at' => date('d/m/Y H:i')],
        ];
        return $this->renderLayout('Conversação Familiar', 'family.thread', compact('thread', 'messages'));
    }

    public function createForm(): string
    {
        return $this->renderLayout('Abrir Conversa', 'family.create');
    }
}
