<?php

namespace App\Services;

use Config\app as Config;

class ImageService
{
    public function uploadImage(array $file): string
    {
        $allowedTypes = ['image/jpeg','image/png','image/gif', 'image/webp'];
        if (!in_array($file['type'], $allowedTypes) || $file['size'] > 5 * 1024 * 1024) {
            throw new \Exception('Tipo ou tamanho de arquivo inválido.');
        }

        $uploadDir = __DIR__.'/../../public/assets/img/uploads/';

        // Cria diretório se não existir
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $fileName = uniqid('', false) . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
        $targetFilePath = $uploadDir . $fileName;

        if (!move_uploaded_file($file['tmp_name'], $targetFilePath)) {
            throw new \Exception('Falha ao mover o arquivo. Verifique as permissões do diretório.');
        }

        return $fileName;
    }

    public function deleteImage(string $fileName): void
    {
        $filePath = __DIR__.'/../../public/assets/img/uploads/' . $fileName;

        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }
}
