<?php

namespace Core;

class Controller
{
    protected function redirect($url, $flashMessage = null)
    {
        if($flashMessage){
            $this->setFlashMessage('message', $flashMessage);
        }

        // Remove barra inicial duplicada se existir
        $url = ltrim($url, '/');
        $base = $this->getBaseUrl();

        // Remove barras duplicadas na junção
        $redirectUrl = rtrim($base, '/') . '/' . $url;
        header("Location: " . $redirectUrl);
        exit;
    }

    protected function setFlashMessage($key, $message, $type = 'error')
    {
        if(!isset($_SESSION['flash'])) {
            $_SESSION['flash'] = [];
        }

        $_SESSION['flash'][$key] = [
            'message' => $message,
            'type' => $type
        ];
    }

    protected function getFlashMessage($key)
    {
        if(isset($_SESSION['flash'])){
            $message = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]);
            return $message;
        }
        return null;
    }

    protected function hasFlashMessage($key)
    {
        return isset($_SESSION['flash'][$key]);
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

            // Helpers disponiveis para views
            $render = fn($vN, $vD = []) => $this->renderPartial($vN, $vD);
            $base = $this->getBaseUrl();
            $url = fn($path) => $this->url($path); // função helper para algumas rotas
            $asset = fn($path) => $this->url('assets/' . ltrim($path, '/')); // Helper específico para assets

            //Helpers para flash messages
            $getFlash = fn($key) => $this->getFlashMessage($key);
            $hasFlash = fn($key) => $this->hasFlashMessage($key);

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
