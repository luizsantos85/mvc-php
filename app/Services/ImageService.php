<?php

namespace App\Services;


class ImageService
{
    public function uploadImage(array $file): string
    {
        // $allowedTypes = [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_GIF, IMAGETYPE_WEBP];
        
        // Verifica o tipo real do arquivo através da assinatura
        // $detectedType = @exif_imagetype($file['tmp_name']);
        // if (!$detectedType || !in_array($detectedType, $allowedTypes)) {
        //     throw new \Exception('Arquivo não é uma imagem válida.');
        // }

        // Utiliza finfo para detectar o tipo MIME do arquivo
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($mimeType, $allowedMimeTypes)) {
            throw new \Exception('Arquivo não é uma imagem válida.');
        }

        // Verifica o tamanho do arquivo
        if ($file['size'] > 5 * 1024 * 1024) {
            throw new \Exception('Tamanho do arquivo excede 5MB.');
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
