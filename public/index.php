<?php
session_start();
require_once __DIR__ . '/../vendor/autoload.php';

use App\Mvc\Controller\Controller;
use App\Mvc\Controller\NotfoundController;
use App\Mvc\Controller\VideoController;
use App\Mvc\Repository\VideoRepository;

$rota = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$repository = new VideoRepository();
$videoController = new VideoController($repository);
$notFoundController = new NotfoundController();

switch ($rota) {
    case '/':
        $videoController->processIndex();
        break;

    case '/formulario':
        $videoController->processFormulario();
        break;

    case '/editar-video':
        $videoController->processUpdateVideo();
        break;

    case '/novo-video':
        $videoController->processNewVideo();
        break;

    case '/excluir-video':
        $videoController->processDeleteVideo();
        break;
    
    default:
        $notFoundController->processNotFound();
        break;
}

/** @var Controller $controller */


