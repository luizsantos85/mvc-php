<?php

namespace App\Controllers;

use App\Repositories\UserRepository;
use Core\Controller;

class AuthController extends Controller
{
    public function login(): void
    {
        $this->render('login', [
            'title' => 'Efetue login',
            'btnText' => 'Entrar',
            'action' => '/login',
            'textA' => 'Não possui uma conta?',
            'urlA' => '/create-user'
        ]);
    }

    public function loginAction(): void
    {
        $email = trim(filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL));
        $password = trim(filter_input(INPUT_POST, 'senha', FILTER_SANITIZE_FULL_SPECIAL_CHARS));

        if (!$email || !$password) {
            $_SESSION['flash'] = "Preencha todos os campos.";
            $this->redirect('/login');
        }

        $userRepository = new UserRepository();
        $user = $userRepository->findEmailUser($email);

        if (!$user) {
            $_SESSION['flash'] = "Usuário ou senha inválidos.";
            $this->redirect('/login');
        }

        if (!password_verify($password, $user['password'] ?? '')) {
            $_SESSION['flash'] = "E-mail e ou senha inválidos.";
            $this->redirect('/login');
        }

        $_SESSION['LOGGED'] = $user;
        $this->redirect('/');
    }

    public function logout(): void
    {
        unset($_SESSION['LOGGED']);
        $this->redirect('/');
    }
}
