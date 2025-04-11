<?php
session_start();
session_regenerate_id();

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/routes.php';

$router->run($router->routes);



