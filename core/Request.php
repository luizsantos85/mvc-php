<?php

namespace Core;

use Config\app;

class Request
{
    public static function getMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * Obtém a URL atual da página.
     *
     * Esta função recupera a URL atual da página, excluindo a string de consulta 
     * (parâmetros após o ?) e o caminho base do projeto.
     *
     * @return string A URL atual da página.
     */
    public static function getUrl()
    {
        $uri = filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_URL);

        // Remove query string se existir
        $uri = explode('?', $uri)[0];

        // Remove o path base do projeto se existir
        $scriptName = dirname($_SERVER['SCRIPT_NAME']);
        if ($scriptName !== '/') {
            $uri = str_replace($scriptName, '', $uri);
        }

        // Garante que a URL comece com '/'
        if (!str_starts_with($uri, '/')) {
            $uri = '/' . $uri;
        }

        return $uri;
    }
}
