<?php

namespace App\Controllers;

use App\Models\Path;

class JSONController extends Controller
{

    public function index()
    {


        $path = new Path;
        $data_path = $path->getAll([]);

        return $this->json(["data" => $data_path]);
    }
}
