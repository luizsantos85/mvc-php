<?php

namespace App\Classes;

class Video
{
    public readonly string $url;
    public readonly int $id;
    public ?string $fileName = null;

    public function __construct(string $url,public readonly string $title)
    {
        $this->setUrl($url);
    }

    private function setUrl(string $url): void
    {
        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            throw new \InvalidArgumentException('URL inválida: ' . $url);
        }

        $this->url = $url;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setFileImage(string $file): void
    {
        $this->fileName = $file;
    }

    public function getFileName(): ?string
    {
        return $this->fileName;
    }

}
