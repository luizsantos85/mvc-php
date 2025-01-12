<?php

use Core\Router;

$router = new Router();

$router->get('/', 'VideoController@index');
$router->get('/formulario', 'VideoController@formulario');
// $router->get('/formulario/{id}', 'VideoController@formulario');
