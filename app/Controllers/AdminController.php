<?php

namespace App\Controllers;

use App\Models\FieldForm;
use App\Models\Path;

class AdminController extends Controller
{

    public function index()
    {

        $path = new Path;
        $data = $path->getAll([]);

        $this->view("layouts/base-layout/header");
        if ($_GET['page'] === "login") {
            $this->view("pages/admin/login");
        } else {
            $this->view("pages/admin/index", ["data" => $data]);
        }
        $this->view("layouts/base-layout/footer");
    }

    public function show()
    {

        $requestUri = parse_url($_SERVER['REQUEST_URI']);
        $path = array_filter(explode("/", $requestUri['path']));
        unset($path[1]);
        $fullpath = "/" . implode("/", $path);

        $this->view("layouts/base-layout/header");
        $path = new Path;

        if ($fullpath == "/") {
            $data = $path->getAll([]);

            $this->view("pages/admin/index", ["data" => $data]);
        } else {
            $cur = $this->getSelectedPath("/admin");

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
                        "realpath" => substr(parse_url($_SERVER['REQUEST_URI'])['path'], 6)
                    ]),
                    "fields" => $fields
                ]);
            } else {
                echo "404";
            }
        }
        $this->view("layouts/base-layout/footer");
    }
}
