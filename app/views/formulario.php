<?php

use App\Mvc\Classes\Video;
use App\Mvc\Repository\VideoRepository;

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

$video = '';

if ($id) {
    $repository = new VideoRepository();
    $video = $repository->find($id);

    if (!$video) {
        header("Location: index.php");
        exit;
    }
}
?>
<?php require_once '../app/views/partials/header.php'; ?>


<main class="container">
    <form class="container__formulario"
        action="<?= isset($id) && !empty($id) ? '/editar-video' : '/novo-video' ?>"
        method="post">
        <h2 class="formulario__titulo">Envie um vídeo!</h3>
            <div class="formulario__campo">
                <label class="campo__etiqueta" for="url">Link embed</label>
                <input name="url" class="campo__escrita"
                    placeholder="Por exemplo: https://www.youtube.com/embed/FAY1K2aUg5g"
                    id='url'
                    value="<?= isset($video) && !empty($video->url) ? $video->url : '' ?>" />
            </div>


            <div class="formulario__campo">
                <label class="campo__etiqueta" for="title">Título do vídeo</label>
                <input name="title" class="campo__escrita" placeholder="Neste campo, dê o nome do vídeo"
                    id='title' value="<?= isset($video) && !empty($video->title) ? $video->title : '' ?>" />
            </div>
            <input type="hidden" name="id_video" value="<?= isset($video) && !empty($video->id) ? $video->id : '' ?>">

            <input class="formulario__botao" type="submit" value="Enviar" />
    </form>
</main>


<?php require_once '../app/views/partials/footer.php'; ?>