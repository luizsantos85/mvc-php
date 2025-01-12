<?php

namespace Config;

class app
{
    const BASE_DIR = __DIR__.'/mvc/public'; // Colocar o caminho correto da pasta

    const DB_DRIVER = 'mysql';
    const DB_HOST = 'localhost';
    const DB_DATABASE = 'estudos_mvc';
    const DB_USER = 'root';
    const DB_PASS = '1234';

    const ERROR_CONTROLLER = 'ErrorController';
    const DEFAULT_ACTION = 'index';
}
