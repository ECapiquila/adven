<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Support\Auth;
use Core\Controller;
use Core\Http\Request;

final class AuthController extends Controller
{
    public function login(Request $request): string
    {
        if ($request->method() === 'POST') {
            $credentials = $request->only(['email', 'password']);
            if (!filter_var($credentials['email'] ?? '', FILTER_VALIDATE_EMAIL)) {
                flash('error', 'Forneça um e-mail válido.');
            } elseif (!isset($credentials['password']) || $credentials['password'] === '') {
                flash('error', 'A palavra-passe é obrigatória.');
            } elseif (Auth::attempt($credentials['email'], (string) $credentials['password'])) {
                flash('success', 'Bem-vindo de volta!');
                return $this->redirectResponse('/feed');
            } else {
                flash('error', 'Credenciais inválidas.');
            }
        }

        return $this->view('auth/login', ['title' => 'Entrar']);
    }

    public function register(Request $request): string
    {
        return $this->view('auth/register', ['title' => 'Criar conta']);
    }

    public function logout(Request $request): void
    {
        Auth::logout();
        flash('success', 'Sessão terminada.');
        $this->redirect('/');
    }

    private function redirectResponse(string $path): string
    {
        redirect($path);
        return '';
    }
}
