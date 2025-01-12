<?php
session_start();
require_once __DIR__ . '/../vendor/autoload.php';

$rota = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$folder = '';
$archive = '';

switch ($rota) {
    case '/':
        $folder = 'views';
        $archive = 'lista-videos';
        break;

    case '/formulario':
        $folder = 'views';
        $archive = 'formulario';
        break;

    case '/editar-video':
        $folder = 'functions';
        $archive = 'editar-video';
        break;

    case '/novo-video':
        $folder = 'functions';
        $archive = 'novo-video';
        break;

    case '/excluir-video':
        $folder = 'functions';
        $archive = 'excluir-video';
        break;
    
    default:
        http_response_code(404);
        break;
}

require_once __DIR__ . "/../app/{$folder}/{$archive}.php";


// echo '<pre>';
// print_r($_SERVER);
// echo '</pre>';
// exit;
