<?php

namespace App\Controllers;

use Core\Controller;
use App\Classes\Video;
use App\Repositories\VideoRepository;
use App\Services\ImageService;

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
        // echo '<pre>';
        // print_r($listaVideos);
        // echo '</pre>';exit;

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
        $oldImage = trim(filter_input(INPUT_POST, 'old_image', FILTER_SANITIZE_SPECIAL_CHARS));
        $image = $_FILES['image'];

        if (!$url || !$title) {
            $this->setFlashMessage('message', 'Preencha todos os campos.', 'error');
            $this->redirect('/formulario?id=' . $id);
        }

        $video = new Video($url, $title);

        try {
            $video->setId($id);
            $video->setFileImage($oldImage);

            if ($image['error'] === UPLOAD_ERR_OK) {
                $imageService = new ImageService();

                // Deleta a imagem antiga
                $oldImage = $video->getFileName();

                if ($oldImage) {
                    $imageService->deleteImage($oldImage);
                }

                // Salva a nova imagem
                $imagePath = $imageService->uploadImage($image);
                $video->setFileImage($imagePath);
            }

            $result = $this->videoRepository->update($video);

            if ($result === false) {
                $this->setFlashMessage('message', "Erro ao atualizar.", 'error');
                $this->redirect('/');
            }

            $this->setFlashMessage('message', "Video atualizado com sucesso.", 'success');
            $this->redirect('/');
        } catch (\Exception $e) { //Retorna o erro da exception
            $this->setFlashMessage('message', $e->getMessage(), 'error');
            $this->redirect('/formulario');
        }
               
        $this->redirect('/');
    }

    public function newVideo(): void
    {
        $url = trim(filter_input(INPUT_POST, 'url', FILTER_VALIDATE_URL));
        $title = trim(filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS));
        $image = $_FILES['image'];

        if (!$url || !$title) {
            $this->setFlashMessage('message', 'Preencha todos os campos.', 'error');

            $this->redirect('/formulario');
        }
        
        $video = new Video($url, $title);

        try {
            if ($image['error'] === UPLOAD_ERR_OK) {
                $imageService = new ImageService();
                $imagePath = $imageService->uploadImage($image);
                $video->setFileImage($imagePath);
            }

            $result = $this->videoRepository->insert($video);

            if ($result === false) {
                $_SESSION['flash'] = "Erro ao inserir.";
                $this->redirect('/');
            }

            $this->setFlashMessage('message', "Video inserido com sucesso.", 'success');
            $this->redirect('/');

        } catch (\Exception $e) { //Retorna o erro da exception
            $this->setFlashMessage('message', $e->getMessage(), 'error');
            $this->redirect('/formulario');
        }
        
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

            // Deleta a imagem do vídeo
            if ($video->getFileName()) {
                $imageService = new ImageService();
                $imageService->deleteImage($video->getFileName());
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

    public function deleteImage(): void
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if ($id) {
            $video = $this->videoRepository->find($id);

            if (!$video) {
                $this->redirect('/');
            }

            // Deleta a imagem do vídeo
            if ($video->getFileName()) {
                $imageService = new ImageService();
                $imageService->deleteImage($video->getFileName());
            }

            $video->setFileImage('null');

            $result = $this->videoRepository->update($video);

            if ($result === false) {
                $this->setFlashMessage('message', "Erro ao excluir.", 'error');
                $this->redirect('/');
            }

            $this->setFlashMessage('message', "Imagem excluída com sucesso.", 'success');
            $this->redirect('/');
        }
    }

    public function jsonVideos(): void
    {
        $videos = array_map(function (Video $video): array {
            return [
                'url' => $video->url,
                'title' => $video->title,
                'file_path' => '/img/uploads/'.$video->getFileName()
            ];

        },$this->videoRepository->findAll());

        echo json_encode($videos);
        exit;
    }

    public function jsonVideosPost(): void
    {
        // $url = trim(filter_input(INPUT_POST, 'url', FILTER_VALIDATE_URL));
        // $title = trim(filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS));

        $request = file_get_contents('php://input');
        $data = json_decode($request, true);
        $url = $data['url'] ?? null;
        $title = $data['title'] ?? null;

        if (!$url || !$title) {
            echo json_encode(['error' => 'Preencha todos os campos.']);
            exit;
        }

        $video = new Video($url, $title);

        try {
            $result = $this->videoRepository->insert($video);

            if ($result === false) {
                echo json_encode(['error' => 'Erro ao inserir.']);
                exit;
            }

            http_response_code(201);
            echo json_encode(['success' => 'Video inserido com sucesso.']);
            exit;

        } catch (\Exception $e) { //Retorna o erro da exception
            echo json_encode(['error' => $e->getMessage()]);
            exit;
        }
    }
}
