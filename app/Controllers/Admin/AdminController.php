<?php

namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Models\FieldForm;
use App\Models\Path;

class AdminController extends Controller
{

    public function index()
    {

        $path = new Path;
        $data = $path->getAll([]);

        $this->view("layouts/base-layout/header");
        $this->view("layouts/base-layout/navbar");
        $this->view("pages/admin/index", ["data" => $data]);
        $this->view("layouts/base-layout/footer");
    }

    public function show()
    {

        $requestUri = parse_url($_SERVER['REQUEST_URI']);
        $path = array_filter(explode("/", $requestUri['path']));
        unset($path[1]);
        $fullpath = "/" . implode("/", $path);

        $this->view("layouts/base-layout/header");
        $this->view("layouts/base-layout/navbar");
        $path = new Path;

        if ($fullpath == "/") {
            $data = $path->getAll([]);

            $this->view("pages/admin/index", ["data" => $data]);
        } else {
            $cur = $this->getSelectedPath("/admin/try");

            $fieldform = new FieldForm;
            $fields = $fieldform->getAll([
                "where" => [
                    ["path_id", "=", $cur['data']['path_id']]
                ]
            ]);
            $data = $path->get([
                "where" => [
                    ["id", "=", $cur['data']['path_id']]
                ]
            ]);
            if ($data) {

                $this->view("pages/admin/show", [
                    "path" => (object) array_merge((array) $data, [
                        "realpath" => substr(parse_url($_SERVER['REQUEST_URI'])['path'], 10)
                    ]),
                    "fields" => $fields
                ]);
            } else {
                echo "404";
            }
        }
        $this->view("layouts/base-layout/footer");
    }

    public function login()
    {
        $this->view("layouts/base-layout/header");
        $this->view("pages/admin/login");
        $this->view("layouts/base-layout/footer");
    }
}
