<?php

namespace App\Controllers;

use App\Models\FieldForm;
use App\Models\Path;

class AdminController extends Controller
{
    private function getSelectedPath()
    {
        $requestUri = parse_url($_SERVER['REQUEST_URI']);
        $requestPath = $requestUri['path'];
        $path = new Path;
        $data_path = $path->getAll([]);

        $selectedPath = "";
        $params = [];
        $matched = false;
        foreach ($data_path as $dt) {

            if ($matched) {
                continue;
            }
            $fullpath = $dt->full_path;

            $fullpath = str_replace("\\", "\\\\", $fullpath);


            $explodePath = explode("/", $fullpath);

            $arrayPath = [];
            foreach ($explodePath as $value) {
                if ($value != "") {
                    $arrayPath[] = $value;
                }
            }

            $explodeReqPath = explode("/", $requestPath);


            $arrayReqPath = [];
            foreach ($explodeReqPath as $value) {
                if ($value != "") {
                    $arrayReqPath[] = $value;
                }
            }

            $newParams = [];

            if ($requestPath == "/" && $requestPath === $path) {
                $matched = true;
            } else {

                $skip = false;
                $any = false;

                foreach ($arrayReqPath as $key => $value) {

                    if ($skip) {
                        continue;
                    }

                    if (!empty($arrayPath[$key]) || $any) {

                        if (!$any && $arrayPath[$key] != $value) {

                            if (substr($arrayPath[$key], 0, 1) == ":") {
                                if ($arrayPath[$key] == ":any") {
                                    $any = true;
                                } else {
                                    $newParams[substr($arrayPath[$key], 1, strlen($arrayPath[$key]))] = $value;
                                }
                            } else {
                                $skip = true;
                            }
                        }
                    } else {
                        $skip = true;
                    }

                    if (count($arrayReqPath) - 1 == $key && !$any) {
                        if (!empty($arrayPath[$key + 1]) && $arrayPath[$key + 1] != "") {
                            $skip = true;
                        }
                    }
                }
            }

            if ($matched) {
                $selectedPath = $dt->full_path;
                $params = $newParams;
            }
        }

        return [
            "path" => $selectedPath,
            "params" => $params
        ];
    }

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
            $cur = $this->getSelectedPath();
            $data = $path->get([
                "where" => [
                    ["path", "=", $cur['path']]
                ]
            ]);
        }
        $this->view("layouts/base-layout/footer");
    }

    public function store()
    {
        $path = new Path;
        $dataPath = $_POST;
        unset($dataPath['field']);
        $dataField = $_POST['field'];

        $id = $path->insert($dataPath);

        foreach ($dataField as $key => $value) {
            $fieldForm = new FieldForm;
            $fieldForm->insert(array_merge($value, [
                "path_id" => $id
            ]));
        }

        return $this->json(["id" => $id, "field" => $dataField]);
    }

    public function delete()
    {
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
