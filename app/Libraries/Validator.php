<?php

namespace App\Libraries;

use System\Database as DB;

class Validator
{
    private $db;
    function __construct()
    {
        $this->db = new DB;
    }

    private function uniqueness($table, $column, $value, $ignoreId = null, $ignoreIdColumn = null)
    {

        $algo = "";
        if ($ignoreId != null) {
            $algo = $algo + ' AND ' + $ignoreIdColumn + ' != "' + $ignoreId + '"';
        }

        $query = $this->db->query('SELECT * FROM ' . $table . ' WHERE ' . $column . '= :value ' . $algo);
        $query->execute([
            "value" => $value
        ]);

        return $query->fetch();
    }

    public function make($body, $rules = [])
    {

        $error = 0;
        $errMsg = [];

        foreach ($rules as $key => $rule) {

            $label = empty($rule['label']) ? $key : $rule['label'];

            $noRule = 0;
            foreach ($rule['rule'] as $keyRule => $value) {

                switch ($keyRule) {

                    case "required":
                        if ($value) {
                            if (empty($body[$key])) {
                                if ($noRule) {
                                    $errMsg[$key] = [];
                                }
                                $errMsg[$key][] = $label . ' is required.';
                                $noRule += 1;
                                $error += 1;
                            }
                        }
                        break;

                    case "email":

                        if ($value) {
                            if (!filter_var($body[$key], FILTER_VALIDATE_EMAIL)) {
                                if ($noRule == 0) {
                                    $errMsg[$key] = [];
                                }
                                $errMsg[$key][] = $label . ' is invalid.';
                                $noRule += 1;
                                $error += 1;
                            }
                        }

                        break;

                    case "equalsTo":

                        if ($value) {

                            $value2 = $body[$value];

                            $key2 = $value;

                            $label2 = empty($rules[$key]['label']) ? $key2 : $rules[$key2]['label'];

                            if ($body[$key] != $value2) {
                                if ($noRule == 0) {
                                    $errMsg[$key] = [];
                                }


                                $errMsg[$key][] = $label . ' must be match to ' . $label2 . '.';
                                $noRule += 1;
                                $error += 1;
                            }
                        }

                        break;

                    case "unique":
                        if ($value) {
                            $uniqueRule = explode(",", $value);
                            $table = $uniqueRule[0];
                            $column = $uniqueRule[1];
                            $ignoreId = null;
                            $ignoreIdColumn = "id";

                            if (!empty($uniqueRule[2])) {
                                $ignoreId = $uniqueRule[2];
                            }
                            if (!empty($uniqueRule[3])) {
                                $ignoreIdColumn = $uniqueRule[3];
                            }
                            $res = $this->uniqueness($table, $column, $body[$key], $ignoreId, $ignoreIdColumn);
                            if ($res) {
                                if ($noRule == 0) {
                                    $errMsg[$key] = [];
                                }
                                $errMsg[$key][] = $label . ' is already in use.';
                                $noRule++;
                                $error++;
                            }
                        }
                        break;

                    default:
                        break;
                }
            }
        }

        return (object)[
            "fails" => $error > 0,
            "messages" => $errMsg
        ];
    }
}
