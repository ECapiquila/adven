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

class AuthController extends Controller
{
    public function __construct(
        Container $container,
        View $view,
        Response $response,
        private readonly AuthManager $auth,
        private readonly Validator $validator,
        private readonly Request $request
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
