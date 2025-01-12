<?php
session_start();
require_once __DIR__ . '/../vendor/autoload.php';

use App\Mvc\Repository\VideoRepository;
use \App\Mvc\Controller\VideoController;

$rota = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$folder = '';
$archive = '';
$repository = new VideoRepository();
$controller = new VideoController($repository);

switch ($rota) {
    case '/':
        $controller->processIndex();
        break;

    case '/formulario':
        $controller->processFormulario();
        break;

    case '/editar-video':
        $controller->processUpdateVideo();
        break;

    case '/novo-video':
        $controller->processNewVideo();

        break;

    case '/excluir-video':
        $controller->processDeleteVideo();

        break;
    
    default:
        http_response_code(404);
        break;
}

