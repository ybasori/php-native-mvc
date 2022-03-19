<?php

namespace System;

class Response
{
    public function json($data = [], $statusCode = 200)
    {
        header('Content-Type: application/json');
        http_response_code($statusCode);

        echo json_encode((object) $data);
    }

    public function view($viewPath, $data = [])
    {
        $data = (object) $data;
        foreach ($data as $key => $value) {
            $$key = $value;
        }
        require_once __DIR__ . "/../app/Views/" . $viewPath . ".php";
    }
}
