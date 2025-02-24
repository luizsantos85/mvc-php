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
            $this->setFlashMessage('message', 'Preencha todos os campos.', 'error');
            $this->redirect('/login');
        }

        $userRepository = new UserRepository();
        $user = $userRepository->findEmailUser($email);

        if (!$user) {
            $this->setFlashMessage('message', 'Usuário ou senha inválidos.', 'error');
            $this->redirect('/login');
        }

        $correctPass = password_verify($password, $user['password'] ?? '');

        //Verifica se a senha precisa ser refeita
        if(password_needs_rehash($user['password'], PASSWORD_ARGON2ID)){ 
            $newHash = password_hash($password, PASSWORD_ARGON2ID);
            $userRepository->updatePassword($user['id'], $newHash);
        }

        if (!$correctPass) {
            $this->setFlashMessage('message', 'Usuário ou senha inválidos.', 'error');
            $this->redirect('/login');
        }

        $_SESSION['LOGGED'] = $user;
        $this->redirect('/');
    }

    public function logout(): void
    {
        // session_destroy();
        unset($_SESSION['LOGGED']);
        $this->redirect('/');
    }
}
