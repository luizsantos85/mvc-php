<?php

namespace App\Repositories;

use App\Classes\Video;
use Core\Database;
use PDO;

class VideoRepository
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    /**
     * Busca todos os vídeos
     *
     * @return Video[]
     */
    public function findAll(): array
    {
        $videoList = $this->pdo->query('SELECT * FROM videos')
            ->fetchAll(PDO::FETCH_ASSOC);
        return array_map(
            $this->assembleVideo(...), // Retornará um array de objetos Video
            $videoList
        );
    }

    /**
     * Busca um vídeo pelo id
     *
     * @param integer $id
     * @return Video|null
     */
    public function find(int $id): ?Video
    {
        $sql = $this->pdo->prepare('SELECT * FROM videos WHERE id = :id');
        $sql->bindValue(':id', $id, PDO::PARAM_INT);
        $sql->execute();

        return $this->assembleVideo($sql->fetch(PDO::FETCH_ASSOC));
    }

    private function assembleVideo(array $videoData): Video
    {
        $video = new Video($videoData['url'], $videoData['title']);
        $video->setId($videoData['id']);
        
        if($videoData['image_name'] !== null){
            $video->setFileImage($videoData['image_name']);
        }

        return $video;
    }

    /**
     * Insere um vídeo
     *
     * @param Video $video
     * @return Video
     */
    public function insert(Video $video): bool
    {
        $sql = $this->pdo->prepare("INSERT INTO videos (url,title,image_name) values (:url,:title,:image_name)");
        $sql->bindValue(':url', $video->url);
        $sql->bindValue(':title', $video->title);
        $sql->bindValue(':image_name', $video->getFileName());
        $result = $sql->execute();

        if ($result === false) {
            throw new \Exception("Erro ao cadastrar.");
        }

        $id = $this->pdo->lastInsertId(); // Retorna o id do último registro inserido
        $video->setId(intval($id));

        return $result;
    }

    /**
     * Atualiza um vídeo
     *
     * @param Video $video
     * @return Video
     */
    public function update(Video $video): bool
    {
        $sql = $this->pdo->prepare("UPDATE videos SET url = :url, title = :title , image_name = :image_name WHERE id = :id");
        $sql->bindValue(':url', $video->url);
        $sql->bindValue(':title', $video->title);
        $sql->bindValue(':id', $video->id);
        $sql->bindValue(':image_name', $video->getFileName());
        
        return $sql->execute();

        // if ($sql->execute() === false) {
        //     throw new \Exception("Erro ao atualizar.");
        // }

        // return $video;
    }

    /**
     * Exclui um vídeo
     *
     * @param integer $id
     * @return void
     */
    public function delete(int $id): bool
    {
        $sql = $this->pdo->prepare('DELETE FROM videos WHERE id = :id');
        $sql->bindValue(':id', $id, PDO::PARAM_INT);
        return $sql->execute();

        // if ($sql->execute() === false) {
        //     throw new \Exception("Erro ao excluir.");
        // }

        // return;
    }
}
