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

    public function findEmailUser(string $email) : array|false
    {
        $sql = $this->pdo->prepare('SELECT * FROM user WHERE email = :email');
        $sql->bindValue(':email', $email, PDO::PARAM_STR);
        $sql->execute();
        $result = $sql->fetch(PDO::FETCH_ASSOC);
        return $result === false ? false : $result;
    }

    public function findUserId(int $id): array|false
    {
        $sql = $this->pdo->prepare('SELECT * FROM user WHERE id = :id');
        $sql->bindValue(':id', $id, PDO::PARAM_INT);
        $sql->execute();
        $result = $sql->fetch(PDO::FETCH_ASSOC);
        return  $result === false ? false : $result;
    }

    public function createUser(string $email, string $password): void
    {
        $sql = $this->pdo->prepare('INSERT INTO user (email, password) VALUES (:email, :password)');
        $sql->bindValue(':email', $email, PDO::PARAM_STR);
        $sql->bindValue(':password', $password, PDO::PARAM_STR);
        $sql->execute();
    }

}
