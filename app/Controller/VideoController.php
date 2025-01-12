<?php

namespace App\Mvc\Controller;

use App\Mvc\Classes\Video;
use App\Mvc\Repository\VideoRepository;

class VideoController implements Controller
{
    public function __construct(private VideoRepository $videoRepository) {}

    public function processIndex(): void
    {
        $listaVideos = $this->videoRepository->findAll();

        require_once __DIR__ . '/../views/partials/header.php'; ?>

        <ul class="videos__container" alt="videos alura">
            <?php foreach ($listaVideos as $video): ?>
                <?php if (str_starts_with($video->url, 'http')): ?>
                    <li class="videos__item">
                        <iframe width="100%" height="72%" src="<?= $video->url; ?>"
                            title="YouTube video player" frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen></iframe>
                        <div class="descricao-video">
                            <img src="./img/logo.png" alt="logo canal alura">
                            <h3><?= $video->title; ?></h3>
                            <div class="acoes-video">
                                <a href="formulario?id=<?= $video->id; ?>">Editar</a>
                                <a href="excluir-video?id=<?= $video->id; ?>">Excluir</a>
                            </div>
                        </div>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>

    <?php require_once __DIR__ . '/../views/partials/footer.php';
    }

    public function processFormulario(): void
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        $video = null;

        if ($id) {
            $video = $this->videoRepository->find($id);

            if (!$video) {
                header("Location: index.php");
                exit;
            }
        }

        require_once __DIR__ . '/../views/partials/header.php'; ?>

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
                            value="<?= $video?->url; ?>" />
                    </div>


                    <div class="formulario__campo">
                        <label class="campo__etiqueta" for="title">Título do vídeo</label>
                        <input name="title" class="campo__escrita" placeholder="Neste campo, dê o nome do vídeo"
                            id='title' value="<?= $video?->title; ?>" />
                    </div>
                    <input type="hidden" name="id_video" value="<?= $video?->id; ?>">

                    <input class="formulario__botao" type="submit" value="Enviar" />
            </form>
        </main>
        <?php require_once __DIR__ . '/../views/partials/footer.php';
    }

    public function processUpdateVideo(): void
    {
        $url = trim(filter_input(INPUT_POST, 'url', FILTER_VALIDATE_URL));
        $title = trim(filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS));
        $id = trim(filter_input(INPUT_POST, 'id_video', FILTER_SANITIZE_SPECIAL_CHARS));

        if (!$url || !$title) {
            $_SESSION['flash'] = "Preencha todos os campos.";
            header("Location: /formulario?id={$id}");
            exit;
        }

        $video = new Video($url, $title);
        $video->setId($id);

        $result = $this->videoRepository->update($video);

        if ($result === false) {
            $_SESSION['flash'] = "Erro ao atualiza.";
            header("Location: /");
            exit;
        }

        $_SESSION['flash'] = "Video atualizado com sucesso.";
        header("Location: /");
        exit;
    }

    public function processNewVideo(): void
    {
        $url = trim(filter_input(INPUT_POST, 'url', FILTER_VALIDATE_URL));
        $title = trim(filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS));

        if (!$url || !$title) {
            $_SESSION['flash'] = "Preencha todos os campos.";
            header("Location: /formulario");
            exit;
        }

        $video = new Video($url, $title);

        $result = $this->videoRepository->insert($video);

        if ($result === false) {
            $_SESSION['flash'] = "Erro ao inserir.";
            header("Location: /");
            exit;
        }

        $_SESSION['flash'] = "Video inserido com sucesso.";
        header("Location: /");
        exit;
    }

    public function processDeleteVideo(): void
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if ($id) {
            $video = $this->videoRepository->find($id);

            if (!$video) {
                header("Location: /");
                exit;
            }

            $result = $this->videoRepository->delete($id);

            if ($result === false) {
                $_SESSION['flash'] = "Erro ao excluir.";
                header("Location: /");
                exit;
            }

            $_SESSION['flash'] = "Video excluido com sucesso.";
            header("Location: /");
            exit;
        }
    }
    
}
