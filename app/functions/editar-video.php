<?php

use App\Mvc\Classes\Video;
use App\Mvc\Repository\VideoRepository;

$url = trim(filter_input(INPUT_POST, 'url', FILTER_VALIDATE_URL));
$title = trim(filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS));
$id = trim(filter_input(INPUT_POST, 'id_video', FILTER_SANITIZE_SPECIAL_CHARS));


if (!$url && !$title) {
    $_SESSION['flash'] = "Preencha todos os campos.";
    header("Location: formulario.php?id={$id}");
    exit;
}

$video = new Video($url, $title);
$video->setId($id);

$repository = new VideoRepository();
$result = $repository->update($video);


if ($result === false) {
    $_SESSION['flash'] = "Erro ao atualiza.";
    header("Location: /");
    exit;
}

$_SESSION['flash'] = "Video atualizado com sucesso.";
header("Location: /");
exit;
