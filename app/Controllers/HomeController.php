<?php

namespace App\Controllers;

class HomeController extends Controller
{

    public function index()
    {
        return $this->view("layout", [
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
