<?php

namespace App\Controllers\JSON;

use App\Models\Author;
use App\Controllers\Controller;

class DefaultController extends Controller
{

    public function author($params)
    {
        $author = new Author;

        $a = $author->get([
            "where" => [
                ["username", "=", $params->username]
            ]
        ]);

        if ($a) {
            unset($a->id);
            unset($a->user_id);
            return $this->json([
                "message" => "Data found",
                "data" => $a,
            ], 200);
        }
        return $this->json([
            "message" => "Data not found",
            "data" => null,
        ], 404);
    }
}
