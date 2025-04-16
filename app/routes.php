<?php

use Core\Router;

$router = new Router();

$router->get('/', 'VideoController@index');
$router->get('/formulario', 'VideoController@formulario');
$router->get('/formulario/{id}', 'VideoController@formulario'); //Caso queira passar por parametro
$router->get('/excluir-video', 'VideoController@deleteVideo');
$router->get('/excluir-capa', 'VideoController@deleteImage');
$router->post('/novo-video', 'VideoController@newVideo');
$router->post('/editar-video', 'VideoController@updateVideo');

$router->get('/create-user', 'UsersController@createUser');
$router->post('/create-user', 'UsersController@createUserAction');

$router->get('/login', 'AuthController@login');
$router->post('/login', 'AuthController@loginAction');
$router->get('/logout', 'AuthController@logout');

$router->get('/json-videos', 'VideoController@jsonVideos');
$router->post('/json-videos-create', 'VideoController@jsonVideosPost');
