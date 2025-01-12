<?php

namespace App\Mvc\Classes;

use App\Mvc\Config;

class Database
{
    private static $_pdo;

    /**
     * Retorna uma instância de PDO
     * @return void
     */
    public static function getInstance()
    {
        if (!isset(self::$_pdo)) {
            self::$_pdo = new \PDO(Config::DB_DRIVER . ":dbname=" . Config::DB_DATABASE . ";host=" . Config::DB_HOST, Config::DB_USER, Config::DB_PASS);
        }
        return self::$_pdo;
    }

    private function __construct() {}
    private function __clone() {}
    private function __wakeup() {}
}
