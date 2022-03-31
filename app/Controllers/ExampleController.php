<?php

namespace App\Controllers;

class ExampleController extends Controller
{

    public function index()
    {
        return $this->view("example", [
            "greeting" => "Hello world",
        ]);
    }
}
