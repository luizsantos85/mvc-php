<?php

namespace App\Controllers;

use Core\Controller;
use App\Classes\Video;
use App\Repositories\VideoRepository;

class VideoController extends Controller
{
    private $loggedUser;

    public function __construct(private VideoRepository $videoRepository)
    {
        $this->loggedUser = $_SESSION['LOGGED'] ?? false;
        if (!isset($_SESSION['LOGGED']) || $this->loggedUser === false) {
            $this->redirect('/login');
        }
    }

    public function index(): void
    {
        $listaVideos = $this->videoRepository->findAll();

        $this->render('home', [
            'listaVideos' => $listaVideos
        ]);
    }

    public function formulario($atr = []): void
    {
        // O id pode vir por parametro ou por get
        $id = $atr['id'] ?? filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

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
            $this->setFlashMessage('message', 'Preencha todos os campos.', 'error');
            $this->redirect('/formulario?id=' . $id);
        }

        $video = new Video($url, $title);
        $video->setId($id);

        $result = $this->videoRepository->update($video);

        if ($result === false) {
            $this->setFlashMessage('message', "Erro ao atualizar.", 'error');
            $this->redirect('/');
        }

        $this->setFlashMessage('message', "Video atualizado com sucesso.", 'success');
        $this->redirect('/');
    }

    public function newVideo(): void
    {
        $url = trim(filter_input(INPUT_POST, 'url', FILTER_VALIDATE_URL));
        $title = trim(filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS));

        if (!$url || !$title) {
            $this->setFlashMessage('message', 'Preencha todos os campos.', 'error');

            $this->redirect('/formulario');
        }

        $video = new Video($url, $title);

        $result = $this->videoRepository->insert($video);

        if ($result === false) {
            $_SESSION['flash'] = "Erro ao inserir.";
            $this->redirect('/');
        }

        $this->setFlashMessage('message', "Video inserido com sucesso.", 'success');
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
                $this->setFlashMessage('message', "Erro ao excluir.", 'error');
                $this->redirect('/');
            }

            $this->setFlashMessage('message', "Video excluído com sucesso.", 'success');
            $this->redirect('/');
        }
    }
}
