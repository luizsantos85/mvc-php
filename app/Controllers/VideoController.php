<?php

namespace App\Controllers;

use Core\Controller;
use App\Classes\Video;
use App\Repositories\VideoRepository;

class VideoController extends Controller
{
    public function __construct(private VideoRepository $videoRepository) {}

    public function index(): void
    {
        $listaVideos = $this->videoRepository->findAll();

        $this->render('home', [
            'listaVideos' => $listaVideos
        ]);
    }

    public function formulario(): void
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        $video = null;

        if ($id) {
            $video = $this->videoRepository->find($id);

            if (!$video) {
                $this->redirect('/');
            }
        }

        $this->render('formulario', [
            'video' => $video
        ]);
    }

    public function updateVideo(): void
    {
        $url = trim(filter_input(INPUT_POST, 'url', FILTER_VALIDATE_URL));
        $title = trim(filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS));
        $id = trim(filter_input(INPUT_POST, 'id_video', FILTER_SANITIZE_SPECIAL_CHARS));

        if (!$url || !$title) {
            $_SESSION['flash'] = "Preencha todos os campos.";
            $this->redirect('/formulario?id='.$id);
        }

        $video = new Video($url, $title);
        $video->setId($id);

        $result = $this->videoRepository->update($video);

        if ($result === false) {
            $_SESSION['flash'] = "Erro ao atualiza.";
            $this->redirect('/');
        }

        $_SESSION['flash'] = "Video atualizado com sucesso.";
        $this->redirect('/');
    }

    public function newVideo(): void
    {
        $url = trim(filter_input(INPUT_POST, 'url', FILTER_VALIDATE_URL));
        $title = trim(filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS));

        if (!$url || !$title) {
            $_SESSION['flash'] = "Preencha todos os campos.";
            $this->redirect('/formulario');
        }

        $video = new Video($url, $title);

        $result = $this->videoRepository->insert($video);

        if ($result === false) {
            $_SESSION['flash'] = "Erro ao inserir.";
            $this->redirect('/');
        }

        $_SESSION['flash'] = "Video inserido com sucesso.";
        $this->redirect('/');
    }

    public function deleteVideo(): void
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if ($id) {
            $video = $this->videoRepository->find($id);

            if (!$video) {
                $this->redirect('/');
            }

            $result = $this->videoRepository->delete($id);

            if ($result === false) {
                $_SESSION['flash'] = "Erro ao excluir.";
                $this->redirect('/');
            }

            $_SESSION['flash'] = "Video excluido com sucesso.";
            $this->redirect('/');
        }
    }
    
}
