<?php

namespace Core;

use Config\app as Config;


class Controller
{
    protected function redirect($url)
    {
        // Remove barra inicial duplicada se existir
        $url = ltrim($url, '/');
        $base = $this->getBaseUrl();

        // Remove barras duplicadas na junção
        $redirectUrl = rtrim($base, '/') . '/' . $url;
        header("Location: " . $redirectUrl);
        exit;
    }

    protected function url($path)
    {
        return $this->getBaseUrl() . '/' . ltrim($path, '/');
    }

    private function getBaseUrl()
    {
        $protocol = (isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on') ? 'https://' : 'http://';
        $host = $_SERVER['SERVER_NAME'];
        $port = ($_SERVER['SERVER_PORT'] != '80') ? ':' . $_SERVER['SERVER_PORT'] : '';

        // Se estiver usando servidor embutido do PHP
        if ($host === 'localhost' && $port) {
            return "{$protocol}{$host}{$port}";
        }

        // Base ambiente de produção/Apache
        $baseDir = dirname($_SERVER['SCRIPT_NAME']);

        // Se o baseDir for a raiz, retorna sem ele
        if ($baseDir === '/' || $baseDir === '\\') {
            $baseDir = '';
        }

        return "{$protocol}{$host}{$port}{$baseDir}";
    }

    private function _render($folder, $viewName, $viewData = [])
    {
        if (file_exists('../app/views/' . $folder . '/' . $viewName . '.php')) {
            extract($viewData);
            $render = fn($vN, $vD = []) => $this->renderPartial($vN, $vD);
            $base = $this->getBaseUrl();
            $url = fn($path) => $this->url($path); // função helper
            $asset = fn($path) => $this->url('assets/' . ltrim($path, '/')); // Helper específico para assets
            require '../app/views/' . $folder . '/' . $viewName . '.php';
        }
    }

    private function renderPartial($viewName, $viewData = [])
    {
        $this->_render('partials', $viewName, $viewData);
    }

    public function render($viewName, $viewData = [])
    {
        $this->_render('pages', $viewName, $viewData);
    }
}
