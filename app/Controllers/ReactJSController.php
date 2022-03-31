<?php

namespace App\Controllers;

class ReactJSController extends Controller
{

    public function index()
    {
        return $this->view("reactjs-layout", [
            "title" => "Yusuf App",
            "meta" => [
                (object) [
                    "name" => "description",
                    "content" => "Yusuf App"
                ],
                (object) [
                    "name" => "keyword",
                    "content" => "HTML, CSS, Javascript, PHP"
                ],
                (object) [
                    "name" => "author",
                    "content" => "Yusuf Basori"
                ],
            ]
        ]);
    }
}
