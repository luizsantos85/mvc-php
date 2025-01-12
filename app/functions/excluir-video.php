<?php

use App\Mvc\Repository\VideoRepository;

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($id) {
    $repository = new VideoRepository();
    $video = $repository->find($id);

    if (!$video) {
        header("Location: index.php");
        exit;
    }

    $result = $repository->delete($id);

    if ($result === false) {
        $_SESSION['flash'] = "Erro ao excluir.";
        header("Location: /");
        exit;
    }
    
    $_SESSION['flash'] = "Video excluido com sucesso.";
    header("Location: /");
    exit;

}
