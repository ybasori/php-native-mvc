<?php

namespace App\Controllers\Admin;

use App\Controllers\Controller;

class UserController extends Controller
{
    public function index()
    {
        $this->view("layouts/base-layout/header");
        $this->view("layouts/base-layout/navbar");
        $this->view("pages/admin/users/index");
        $this->view("layouts/base-layout/footer");
    }
    public function show($params)
    {
        $this->view("layouts/base-layout/header");
        $this->view("layouts/base-layout/navbar");
        $this->view("pages/admin/users/show", [
            "path" => (object) [
                "realpath" => "/json/v1/admin/users/" . $params->id
            ]
        ]);
        $this->view("layouts/base-layout/footer");
    }
}
