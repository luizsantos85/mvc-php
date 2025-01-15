<?php

use Core\Router;

$router = new Router();

$router->get('/', 'VideoController@index');
$router->get('/formulario', 'VideoController@formulario');
$router->get('/formulario/{id}', 'VideoController@formulario');
$router->get('/excluir-video', 'VideoController@deleteVideo');
$router->post('/novo-video', 'VideoController@newVideo');
$router->post('/editar-video', 'VideoController@updateVideo');

$router->get('/login', 'UsersController@login');
$router->get('/logout', 'UsersController@logout');

