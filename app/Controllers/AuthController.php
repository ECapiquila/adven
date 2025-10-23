<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Http\Request;
use App\Core\Http\Response;
use App\Core\Security\Csrf;
use App\Core\Support\Container;
use App\Core\Validation\Validator;
use App\Core\View\View;
use App\Core\Auth\AuthManager;
use App\Models\User;

class AuthController extends Controller
{
    public function __construct(
        Container $container,
        View $view,
        Response $response,
        private readonly AuthManager $auth,
        private readonly Validator $validator,
        private readonly Request $request,
        private readonly User $users
    ) {
        parent::__construct($container, $view, $response);
    }

    public function loginForm(): string
    {
        return $this->renderLayout('Entrar', 'auth.login');
    }

    public function registerForm(): string
    {
        return $this->renderLayout('Criar Conta', 'auth.register');
    }

    public function register(): Response
    {
        $data = $this->request->all();

        if (!Csrf::validate($data['_token'] ?? null)) {
            return $this->response->setContent($this->renderLayout('Criar Conta', 'auth.register', [
                'errors' => ['token' => ['Sessão expirada, tente novamente.']],
            ]));
        }

        $rules = [
            'name' => 'required|min:3',
            'email' => 'required',
            'phone' => 'required',
            'password' => 'required|min:8',
            'password_confirmation' => 'required',
            'province' => 'required',
            'municipio' => 'required',
        ];

        $errors = $this->validator->validate($data, $rules);
        if (($data['password'] ?? '') !== ($data['password_confirmation'] ?? '')) {
            $errors['password'][] = 'As senhas devem coincidir.';
        }

        if ($errors) {
            return $this->response->setContent($this->renderLayout('Criar Conta', 'auth.register', [
                'errors' => $errors,
                'old' => $data,
            ]));
        }

        $payload = [
            'name' => $data['name'],
            'email' => $data['email'] ?: null,
            'phone' => $data['phone'] ?: null,
            'password' => password_hash($data['password'], PASSWORD_BCRYPT),
            'gender' => $data['gender'] ?? null,
            'is_adventist' => ($data['is_adventist'] ?? '0') === '1' ? 1 : 0,
            'country' => $data['country'] ?? 'Angola',
            'province' => $data['province'] ?? null,
            'municipio' => $data['municipio'] ?? null,
            'bairro' => $data['bairro'] ?? null,
            'region_id' => $data['region_id'] ? (int) $data['region_id'] : null,
            'district_id' => $data['district_id'] ? (int) $data['district_id'] : null,
            'church_id' => $data['church_id'] ? (int) $data['church_id'] : null,
            'role' => 'membro',
        ];

        $userId = $this->users->createFromRegistration($payload);
        $this->auth->loginUsingId($userId);

        header('Location: /feed');
        exit;
    }

    public function login(): Response
    {
        $data = $this->request->all();

        if (!Csrf::validate($data['_token'] ?? null)) {
            return $this->response->setContent($this->renderLayout('Entrar', 'auth.login', [
                'errors' => ['token' => ['Sessão expirada, tente novamente.']],
            ]));
        }

        $errors = $this->validator->validate($data, [
            'login' => 'required',
            'password' => 'required|min:6',
        ]);

        if ($errors) {
            return $this->response->setContent($this->renderLayout('Entrar', 'auth.login', ['errors' => $errors]));
        }

        if (!$this->auth->attempt($data['login'], $data['password'])) {
            return $this->response->setContent($this->renderLayout('Entrar', 'auth.login', [
                'errors' => ['login' => ['Credenciais inválidas']],
            ]));
        }

        header('Location: /feed');
        exit;
    }

    public function logout(): void
    {
        $this->auth->logout();
        header('Location: /');
        exit;
    }
}
