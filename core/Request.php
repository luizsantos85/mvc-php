<?php

namespace Core;

use Config\app;

class Request
{
    public static function getMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public static function getUrl()
    {
        // $uri = filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_URL);

        // // Remove query string se existir
        // $uri = explode('?', $uri)[0];

        // return $uri;

        $url = filter_input(INPUT_GET, 'request');
        $url = str_replace(app::BASE_DIR, '', $url);
        return '/' . $url;
    }
}
