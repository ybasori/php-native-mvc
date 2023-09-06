<?php

namespace App\Controllers;

use App\Models\DataItem;
use App\Models\Path;
use Exception;
use System\Response;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;
use UnexpectedValueException;


class Controller extends Response
{

    public function getSelectedPath($prefix)
    {
        $requestUri = parse_url($_SERVER['REQUEST_URI']);
        $requestPath = $requestUri['path'];
        $path = new Path;
        $p = $path->getByPath($prefix, $requestPath);
        if ($p) {
            return [
                "type" => "index",
                "data" => [
                    "path_id" => $p->id,
                    "full_path" => $p->full_path,
                    "pathname" => $p->pathname,
                    "privacy" => $p->privacy,
                ]
            ];
        } else {
            $dataItem = new DataItem;
            $d = $dataItem->getJoinPathByPath($prefix, $requestPath);
            if ($d) {
                return [
                    "type" => "detail",
                    "data" => [
                        "path_id" => $d->path_id,
                        "full_path" => $d->full_path,
                        "pathname" => $d->pathname,
                        "privacy" => $d->privacy,
                        "data_item_id" => $d->id,
                    ]
                ];
            } else {
                return [
                    "type" => "none",
                    "data" => []
                ];
            }
        }
    }

    public function getUser()
    {
        try {
            $code = JWT::decode(str_replace("Bearer ", "", $_SERVER["HTTP_AUTHORIZATION"]), new Key($_ENV['JWT_KEY'], 'HS256'));
            return $code;
        } catch (ExpiredException $err) {
            return "expired";
        } catch (UnexpectedValueException $err) {
            return "required";
        }
    }
}
