<?php

use App\Mvc\Classes\Video;
use App\Mvc\Repository\VideoRepository;

$url = trim(filter_input(INPUT_POST, 'url', FILTER_VALIDATE_URL));
$title = trim(filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS));

if(!$url && !$title){
    $_SESSION['flash'] = "Preencha todos os campos.";
    header("Location: formulario.php");
    exit;
}

// Cria um objeto Video com os dados
$video = new Video($url, $title);

$repository = new VideoRepository();
$result = $repository->insert($video);


if($result === false){
    $_SESSION['flash'] = "Erro ao cadastrar.";
    header("Location: /");
    exit;
}else{
    $_SESSION['flash'] = "Video adicionado com sucesso.";
    header("Location: /");
    exit;
}



