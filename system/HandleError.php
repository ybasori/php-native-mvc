<?php

namespace System;

class HandleError
{
    public static function onError()
    {
        $isError = false;

        if ($error = error_get_last()) {
            switch ($error['type']) {
                case E_ERROR:
                case E_CORE_ERROR:
                case E_COMPILE_ERROR:
                case E_USER_ERROR:
                    $isError = true;
                    break;
            }
        }

        if ($isError) {
            return self::json($error, 500);
        }

        return null;
    }

    private static function json($data = [], $statusCode = 200)
    {
        header('Content-Type: application/json');
        http_response_code($statusCode);

        echo json_encode((object) [
            "message" => "Something went wrong!",
            "errors" => null,
            "internalErrors" => (object) $data
        ]);
    }
}
