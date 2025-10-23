<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth\AuthManager;
use App\Core\Http\Request;
use App\Core\Http\Response;
use App\Core\Security\Csrf;
use App\Core\Support\Container;
use App\Core\Validation\Validator;
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
        private readonly FamilyThread $familyThread,
        private readonly AuthManager $auth,
        private readonly Request $request,
        private readonly Validator $validator
    ) {
        parent::__construct($container, $view, $response);
    }

    public function index(): string
    {
        $user = $this->auth->user();
        $churchId = $user['church_id'] ?? ($_SESSION['church_id'] ?? 1);
        $threads = $this->familyService->conversations((int) $churchId);
        return $this->renderLayout('Lar & Família', 'family.index', compact('threads'));
    }

    public function show(int $id): string
    {
        $thread = $this->familyThread->find($id) ?? [
            'id' => $id,
            'status' => 'aberto',
            'privacy' => 'pastor_equipe',
            'body' => 'Conversa não encontrada.'
        ];
        $messages = $this->familyService->messages($id);
        return $this->renderLayout('Conversação Familiar', 'family.thread', compact('thread', 'messages'));
    }

    public function createForm(): string
    {
        return $this->renderLayout('Abrir Conversa', 'family.create');
    }

    public function store(): Response
    {
        $data = $this->request->all();
        if (!Csrf::validate($data['_token'] ?? null)) {
            return $this->response->setContent($this->renderLayout('Abrir Conversa', 'family.create', [
                'errors' => ['token' => ['Sessão expirada.']],
            ]));
        }

        $errors = $this->validator->validate($data, [
            'subject' => 'required|min:3',
            'body' => 'required|min:5',
        ]);

        if ($errors) {
            return $this->response->setContent($this->renderLayout('Abrir Conversa', 'family.create', [
                'errors' => $errors,
                'old' => $data,
            ]));
        }

        $user = $this->auth->user();
        $churchId = $user['church_id'] ?? ($_SESSION['church_id'] ?? 1);
        $threadId = $this->familyService->createThread([
            'author_id' => $user['id'],
            'church_id' => $churchId,
            'subject' => $data['subject'],
            'body' => $data['body'],
            'privacy' => $data['privacy'] ?? 'pastor_equipe',
            'status' => 'aberto',
        ]);

        header('Location: /familia/t/' . $threadId);
        exit;
    }

    public function sendMessage(int $id): void
    {
        $data = $this->request->all();
        if (!Csrf::validate($data['_token'] ?? null)) {
            header('Location: /familia/t/' . $id);
            exit;
        }
        $errors = $this->validator->validate($data, ['body' => 'required']);
        if ($errors) {
            header('Location: /familia/t/' . $id);
            exit;
        }
        $user = $this->auth->user();
        $this->familyService->postMessage($id, (int) $user['id'], $data['body'], isset($data['is_staff_note']));
        header('Location: /familia/t/' . $id);
        exit;
    }
}
