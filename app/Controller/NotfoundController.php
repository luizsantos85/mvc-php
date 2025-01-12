<?php

namespace App\Mvc\Controller;

class NotfoundController
{
    public function processNotFound(): void
    {
        http_response_code(404);
    }
}
