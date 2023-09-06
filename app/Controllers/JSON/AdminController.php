<?php


namespace App\Controllers\JSON;

use App\Models\DataField;
use App\Models\DataItem;
use App\Models\FieldForm;
use App\Models\Path;
use App\Controllers\Controller;

class AdminController extends Controller
{

    private function singularToPlural($value)
    {
        if (substr($value, -1) != "s") {
            if (substr($value, -1) == "y") {
                $value = substr($value, 0, -1);
                $value .= "ies";
            } else {
                $value .= "s";
            }
        }
    }

    public function store()
    {
        $path = new Path;
        $dataPath = $_POST;
        unset($dataPath['field']);
        $dataField = $_POST['field'];

        $err = [];
        if (empty($dataPath['name'])) {
            $err['name'][] = "Name is required!";
        }
        if (empty($dataPath['type'])) {
            $err['type'][] = "Type is required!";
        }
        if (empty($dataPath['privacy'])) {
            $err['privacy'][] = "Privacy is required!";
        }

        if (count($err) > 0) {
            return $this->json([
                "message" => "Something went wrong!",
                "data" => null,
                "errors" => $err
            ], 400);
        }

        $dataPath['name_singular'] = $dataPath['name'];
        $dataPath['name_plural'] = $this->singularToPlural($dataPath['name']);

        $dataPath['path'] = preg_replace("/[^a-zA-Z0-9-_\s]/i", "", $dataPath['name']);
        $dataPath['path'] = preg_replace("/\s/i", "-", $dataPath['path']);
        $dataPath['path'] = strtolower($dataPath['path']);

        if (!empty($dataPath['parent_id'])) {
            $dataParent = $path->get([
                "where" => [
                    ["id", "=", $dataPath['parent_id']]
                ]
            ]);
            $dataPath['full_path'][] = substr($dataParent->full_path, 1);
        }

        $dataPath['full_path'][] = $dataPath['path'];
        $dataPath['full_path'] = "/" . implode("/", $dataPath['full_path']);

        $p = $path->checkDuplicate("full_path", $dataPath['full_path']);

        if ($p) {
            $dataPath['full_path'] = $dataPath['full_path'] . "-" . ($p->number + 1);

            $expFull = explode("/", $dataPath['full_path']);

            $dataPath['path'] = $expFull[count($expFull) - 1];
        }
        foreach ($dataField as $key => $value) {
            if (empty($value['name'])) {
                $err['field'][$key]['name'][] = "Name of field is required!";
            } else {
                if (preg_match("/[^a-zA-Z0-9_\s]/i", $value['name'])) {
                    $err['field'][$key]['name'][] = "Only use alphanumeric, uppercase, lowercase, and underscore";
                }
            }
            if (empty($value['label'])) {
                $err['field'][$key]['label'][] = "Label of field is required!";
            }
            if (empty($value['type'])) {
                $err['field'][$key]['type'][] = "Type of field is required!";
            }
        }

        if (count($err) > 0) {
            return $this->json([
                "message" => "Something went wrong!",
                "data" => null,
                "errors" => $err
            ], 400);
        }

        $id = $path->insert($dataPath);

        if ($id) {

            foreach ($dataField as $key => $value) {
                $fieldForm = new FieldForm;
                $fieldForm->insert(array_merge($value, [
                    "path_id" => $id
                ]));
            }
        } else {
            return $this->json([
                "message" => "Something went wrong!",
                "data" => null,
            ], 400);
        }

        return $this->json(["id" => $id, "field" => $dataField,]);
    }

    public function update()
    {

        parse_str(file_get_contents('php://input'), $_PUT);

        $path = new Path;
        $err = [];
        $dataUpdate = $_PUT;
        if (empty($dataUpdate['name'])) {
            $err['name'][] = "Name is required!";
        }
        if (count($err) > 0) {
            return $this->json([
                "message" => "Something went wrong!",
                "data" => null,
                "errors" => $err
            ], 400);
        }
        $path = new Path;
        $dataPath = $path->get([
            "where" => [
                ["id", "=", $_GET['id']]
            ]
        ]);
        if (!empty($dataUpdate['parent_id'])) {
            $dataParent = $path->get([
                "where" => [
                    ["id", "=", $dataUpdate['parent_id']]
                ]
            ]);
            $dataUpdate['full_path'][] = substr($dataParent->full_path, 1);
        }
        $dataUpdate['full_path'][] = $dataPath->path;
        $dataUpdate['full_path'] = "/" . implode("/", $dataUpdate['full_path']);
        $path->update([
            "set" => [
                "name" => $dataUpdate['name'],
                "parent_id" => $dataUpdate['parent_id'],
                "full_path" => $dataUpdate['full_path']
            ],
            "where" => [
                ["id", "=", $_GET['id']]
            ]
        ]);
        return $this->json(["id" => $_GET['id'], "data" => $_PUT]);
    }

    public function delete($params)
    {

        $path = new Path;
        $allPathIds = [$params->id];
        $scanChildPathIds = [$params->id];
        $searching = true;
        while ($searching) {
            $storeChildPathIds = [];

            if (count($scanChildPathIds) > 0) {
                foreach ($scanChildPathIds as $pathId) {
                    if (!empty($pathId)) {
                        $data = $path->getAll([
                            "where" => [
                                ["parent_id", "=", $pathId]
                            ]
                        ]);

                        foreach ($data as $dt) {
                            $storeChildPathIds[] = $dt->id;
                        }
                    }
                }
                $scanChildPathIds = $storeChildPathIds;
                $allPathIds = array_merge($allPathIds, $storeChildPathIds);
            } else {
                $searching = false;
            }
        }

        $df = new DataField;

        $allPathIds = array_filter($allPathIds);

        if (count($allPathIds) > 0) {
            $df->delete([
                "where" => [
                    ["path_id", "IN", "(" . implode(",", $allPathIds) . ")", true]
                ]
            ]);

            $di = new DataItem;

            $di->delete([
                "where" => [
                    ["path_id", "IN", "(" . implode(",", $allPathIds) . ")", true]
                ]
            ]);

            $field = new FieldForm;

            $field->delete([
                "where" => [
                    ["path_id", "IN", "(" . implode(",", $allPathIds) . ")", true]
                ]
            ]);

            $path->delete([
                "where" => [
                    ["id", "IN", "(" . implode(",", $allPathIds) . ")", true]
                ]
            ]);
        }
        return $this->json(["id" => $_GET['id']]);
    }
}
