<?php

namespace App\Controllers;

use App\Models\DataField;
use App\Models\DataItem;
use App\Models\FieldForm;
use App\Models\Path;

class AdminController extends Controller
{


    public function index()
    {

        $requestUri = parse_url($_SERVER['REQUEST_URI']);
        $path = array_filter(explode("/", $requestUri['path']));
        unset($path[1]);
        $fullpath = "/" . implode("/", $path);

        if ($fullpath == "/") {
            $path = new Path;
            $data = $path->getAll([]);

            $this->view("layouts/base-layout/header");
            $this->view("pages/admin/index", ["data" => $data]);
            $this->view("layouts/base-layout/footer");
        }
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
                    ["path_id", "=", $cur['path_id']]
                ]
            ]);
            $data = $path->get([
                "where" => [
                    ["full_path", "=", $cur['path']]
                ]
            ]);
            if ($data) {

                $this->view("pages/admin/show", [
                    "path" => (object) array_merge((array) $data, [
                        "actualpath" => $cur['actualpath'],
                        "params" => (object) $cur['params'],
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

    public function store()
    {
        $path = new Path;
        $dataPath = $_POST;
        unset($dataPath['field']);
        $dataField = $_POST['field'];

        $p = $path->checkExistingFullPath($dataPath['full_path']);

        if ($p) {
            $dataPath['full_path'] = $dataPath['full_path'] . "-" . ($p->number + 1);

            $expFull = explode("/", $dataPath['full_path']);

            $dataPath['path'] = $expFull[count($expFull) - 1];
        }

        $id = $path->insert($dataPath);

        foreach ($dataField as $key => $value) {
            $fieldForm = new FieldForm;
            $fieldForm->insert(array_merge($value, [
                "path_id" => $id
            ]));
        }

        return $this->json(["id" => $id, "field" => $dataField,]);
    }

    public function update()
    {

        parse_str(file_get_contents('php://input'), $_PUT);
        $path = new Path;
        $path->update([
            "set" => $_PUT,
            "where" => [
                ["id", "=", $_GET['id']]
            ]
        ]);
        return $this->json(["id" => $_GET['id'], "data" => $_PUT]);
    }

    public function delete()
    {
        $df = new DataField;

        $df->delete([
            "where" => [
                ["path_id", "=", $_GET['id']]
            ]
        ]);

        $di = new DataItem;

        $di->delete([
            "where" => [
                ["path_id", "=", $_GET['id']]
            ]
        ]);

        $field = new FieldForm;

        $field->delete([
            "where" => [
                ["path_id", "=", $_GET['id']]
            ]
        ]);



        $path = new Path;


        $data = $path->getAll([
            "where" => [
                ["parent_id", "=", $_GET['id']]
            ]
        ]);

        foreach ($data as $dt) {
            $path->delete([
                "where" => [
                    ["id", "=", $dt->id]
                ]
            ]);
        }

        $path->delete([
            "where" => [
                ["id", "=", $_GET['id']]
            ]
        ]);
        return $this->json(["id" => $_GET['id']]);
    }
}
