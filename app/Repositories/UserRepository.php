<?php

namespace App\Repositories;

use Core\Database;
use PDO;

class UserRepository
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    public function findEmailUser(string $email): ?array
    {
        $sql = $this->pdo->prepare('SELECT * FROM users WHERE email = :email');
        $sql->bindValue(':email', $email, PDO::PARAM_STR);
        $sql->execute();       
        return $sql->fetch(PDO::FETCH_ASSOC);
    }

    public function findUserId(int $id): ?array
    {
        $sql = $this->pdo->prepare('SELECT * FROM users WHERE id = :id');
        $sql->bindValue(':id', $id, PDO::PARAM_INT);
        $sql->execute();
        return $sql->fetch(PDO::FETCH_ASSOC);
    }

    public function createUser(string $email, string $password): void
    {
        $sql = $this->pdo->prepare('INSERT INTO users (email, password) VALUES (:email, :password)');
        $sql->bindValue(':email', $email, PDO::PARAM_STR);
        $sql->bindValue(':password', $password, PDO::PARAM_STR);
        $sql->execute();
    }

}
