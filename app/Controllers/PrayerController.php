<?php

namespace App\Controllers;

use App\Core\Auth\AuthManager;
use App\Core\Controller;
use App\Core\Http\Request;
use App\Core\Http\Response;
use App\Core\Security\Csrf;
use App\Core\Support\Container;
use App\Core\Validation\Validator;
use App\Core\View\View;
use App\Models\PrayerRequest;
use App\Services\PrayerService;

class PrayerController extends Controller
{
    public function __construct(
        Container $container,
        View $view,
        Response $response,
        private readonly PrayerService $prayerService,
        private readonly Validator $validator,
        private readonly Request $request,
        private readonly PrayerRequest $prayerRequest,
        private readonly AuthManager $auth
    ) {
        parent::__construct($container, $view, $response);
    }

    public function index(): string
    {
        $user = $this->auth->user();
        if (!$user) {
            header('Location: /login');
            exit;
        }
        $userId = $user['id'] ?? 0;
        $scopeId = $user['church_id'] ?? ($_SESSION['church_id'] ?? 1);
        $pedidos = $this->prayerService->timeline((int) $userId, (int) $scopeId);
        return $this->renderLayout('Pedidos de Oração', 'prayer.index', compact('pedidos'));
    }

    public function show(int $id): string
    {
        $prayer = $this->prayerRequest->find($id) ?? [
            'id' => $id,
            'title' => 'Pedido não encontrado',
            'category' => 'n/d',
            'privacy' => 'publico',
            'body' => 'O pedido indicado não existe.'
        ];
        return $this->renderLayout('Pedido de Oração', 'prayer.show', compact('prayer'));
    }

    public function createForm(): string
    {
        return $this->renderLayout('Novo Pedido de Oração', 'prayer.create');
    }

    public function store(): Response
    {
        $data = $this->request->all();

        if (!Csrf::validate($data['_token'] ?? null)) {
            return $this->response->setContent($this->renderLayout('Novo Pedido de Oração', 'prayer.create', [
                'errors' => ['token' => ['Sessão expirada, tente novamente.']],
            ]));
        }

        $rules = [
            'title' => 'required|min:3',
            'category' => 'required',
            'body' => 'required|min:5',
            'privacy' => 'required',
        ];
        $errors = $this->validator->validate($data, $rules);

        if ($errors) {
            return $this->response->setContent($this->renderLayout('Novo Pedido de Oração', 'prayer.create', compact('errors')));
        }

        $user = $this->auth->user();
        if (!$user) {
            header('Location: /login');
            exit;
        }
        $payload = [
            'author_id' => $user['id'],
            'scope_type' => 'church',
            'scope_id' => $user['church_id'] ?? ($_SESSION['church_id'] ?? 1),
            'title' => $data['title'],
            'category' => $data['category'],
            'body' => $data['body'],
            'privacy' => $data['privacy'],
            'status' => 'aberto',
        ];
        $this->prayerService->create($payload);

        header('Location: /oracoes');
        exit;
    }

    public function respond(int $id): void
    {
        $data = $this->request->all();
        if (!Csrf::validate($data['_token'] ?? null)) {
            header('Location: /oracao/' . $id);
            exit;
        }
        $errors = $this->validator->validate($data, ['body' => 'required']);
        if ($errors) {
            header('Location: /oracao/' . $id);
            exit;
        }
        $user = $this->auth->user();
        if (!$user) {
            header('Location: /login');
            exit;
        }
        $this->prayerService->respond($id, (int) $user['id'], $data['body']);
        header('Location: /oracao/' . $id);
        exit;
    }

    public function react(int $id): Response
    {
        $data = $this->request->all();
        $type = $data['type'] ?? 'orar';
        $user = $this->auth->user();
        if (!$user) {
            return $this->response->json(['status' => 'unauthorized'], 401);
        }
        $this->prayerService->react($id, (int) $user['id'], $type);
        return $this->response->json(['status' => 'ok']);
    }
}
