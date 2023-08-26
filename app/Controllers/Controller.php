<?php

namespace App\Controllers;

use App\Models\Path;
use System\Response;

class Controller extends Response
{

    private function matchingPath($requestPath, $prefix,  $fullpath, $affix)
    {
        $explodedFullpath = explode("/", $prefix . $fullpath . $affix);
        $explodedReqPath = explode("/", $requestPath);
        if (count($explodedFullpath) == count($explodedReqPath)) {
            $correct = 0;
            $newParams = [];
            foreach ($explodedReqPath as $keyErp => $erp) {
                if ($explodedFullpath[$keyErp] === $erp) {
                    $correct += 1;
                } else {
                    if (substr($explodedFullpath[$keyErp], 0, 1) == ":") {
                        $newParams[substr($explodedFullpath[$keyErp], 1, strlen($explodedFullpath[$keyErp]))] = $erp;
                        $correct += 1;
                    }
                }
            }
            if (count($explodedReqPath) == $correct) {
                return [
                    "path" => $fullpath,
                    "fullpath" => $fullpath . $affix,
                    "matched" => true,
                    "params" => $newParams
                ];
            }
            return [
                "path" => "",
                "fullpath" => "",
                "matched" => false,
                "params" => []
            ];
        }
        return [
            "path" => "",
            "fullpath" => "",
            "matched" => false,
            "params" => []
        ];
    }
    public function getSelectedPath($prefix)
    {
        $requestUri = parse_url($_SERVER['REQUEST_URI']);
        $requestPath = $requestUri['path'];
        $path = new Path;
        $data_path = $path->getAll([]);


        $params = [];
        $matched = false;
        $selectedPath = "";
        $selectedActualPath = "";
        $path_id = null;
        foreach ($data_path as $value) {

            if ($matched == false) {

                foreach (json_decode($value->resources_json) as $resource) {

                    $fullpath = $value->full_path;
                    if ($matched == false) {
                        $m = [
                            "path" => "",
                            "fullpath" => "",
                            "matched" => false,
                            "params" => []
                        ];
                        if ($_SERVER['REQUEST_METHOD'] == "GET") {
                            switch ($resource) {
                                case 'index':
                                    $m = $this->matchingPath($requestPath, $prefix, $fullpath, "");
                                    break;
                                case 'show':
                                    $m = $this->matchingPath($requestPath, $prefix, $fullpath, "/:slug");
                                    break;
                                default:
                                    $m = $this->matchingPath($requestPath, $prefix, $fullpath, "");
                                    break;
                            }
                        }
                        if ($matched == false && $_SERVER['REQUEST_METHOD'] == "POST" && $resource == "store") {
                            $m = $this->matchingPath($requestPath, $prefix, $fullpath, "");
                        }
                        if ($matched == false && $_SERVER['REQUEST_METHOD'] == "PUT" && $resource == "update") {
                            $m = $this->matchingPath($requestPath, $prefix, $fullpath, "/:slug");
                        }
                        if ($matched == false && $_SERVER['REQUEST_METHOD'] == "DELETE" && $resource == "delete") {
                            $m = $this->matchingPath($requestPath, $prefix, $fullpath, "/:slug");
                        }
                        $matched = $m['matched'];
                        $params = $m['params'];
                        $selectedPath = $m['path'];
                        $selectedActualPath = $m['fullpath'];
                        if ($matched) {
                            $path_id = $value->id;
                        }
                    }
                }
            }
        }

        return [
            "matched" => $matched,
            "path" => $selectedPath,
            "actualpath" => $selectedActualPath,
            "params" => $params,
            "path_id" => $path_id
        ];
    }
}
