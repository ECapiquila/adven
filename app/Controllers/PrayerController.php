<?php

namespace App\Controllers;

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
        private readonly PrayerRequest $prayerRequest
    ) {
        parent::__construct($container, $view, $response);
    }

    public function index(): string
    {
        $userId = $_SESSION['auth_user_id'] ?? 1;
        $scopeId = $_SESSION['church_id'] ?? 1;
        $pedidos = $this->prayerService->timeline($userId, $scopeId);
        return $this->renderLayout('Pedidos de Oração', 'prayer.index', compact('pedidos'));
    }

    public function show(int $id): string
    {
        $prayer = $this->prayerRequest->find($id) ?? [
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

        $payload = [
            'author_id' => $_SESSION['auth_user_id'] ?? 1,
            'scope_type' => 'church',
            'scope_id' => $_SESSION['church_id'] ?? 1,
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
}
