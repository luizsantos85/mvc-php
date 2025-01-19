<?php

namespace App\Controllers;

use App\Repositories\UserRepository;
use Core\Controller;

class UsersController extends Controller
{
    public function createUser()
    {
        $this->render('login', [
            'title' => 'Criar usuário',
            'btnText' => 'Criar',
            'action' => '/create-user',
            'textA' => 'Já possui uma conta?',
            'urlA' => '/login'
        ]);
    }

    public function createUserAction(): void
    {
        $email = trim(filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL));
        $password = trim(filter_input(INPUT_POST, 'senha', FILTER_SANITIZE_FULL_SPECIAL_CHARS));

        if (!$email || !$password) {
            $this->setFlashMessage('message', 'Preencha todos os campos.', 'error');
            $this->redirect('/create-user');
        }

        $hashPassword = password_hash($password, PASSWORD_ARGON2ID);

        $userRepository = new UserRepository();
        $userRepository->createUser($email, $hashPassword);

        $this->redirect('/login');
    }









    
}