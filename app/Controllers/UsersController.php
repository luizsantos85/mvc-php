<?php

namespace App\Controllers;

use Core\Controller;

class UsersController extends Controller
{
    public function login(): void
    {
        $this->render('login', []);
    }

    public function logout(): void
    {
        // $_SESSION['token'] = '';
        $this->redirect('/login');
    }

    
}