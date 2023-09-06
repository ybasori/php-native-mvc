<?php

namespace System;

use Exception;
use System\Database as DB;

class Model
{

    private $db;
    public $table = "";

    function __construct()
    {
        $this->db = new DB;
    }

    public function querySort($value = null)
    {
        if (empty($value)) {
            $value = ["created_at" => "asc"];
        }

        $sort = "";
        $sortArr = [];
        foreach ($value as $key => $s) {
            $sortArr[] = $key . " " . ($s == "asc" ? "ASC" : "DESC");
        }

        if (count($sortArr) > 0) {
            $sort = "ORDER BY " . implode(", ", $sortArr);
        }

        return $sort;
    }

    public function queryPagination($value)
    {
        $limit = "";
        if (!empty($value)) {
            $offset = ($value['page'] - 1) * $value['limit'];
            $limit = "LIMIT $offset," . $value['limit'];
        }

        return $limit;
    }

    public function queryWhereClause($value)
    {
        $where = [];
        $whereArr = [];

        foreach ($value['where'] as $item) {
            if (!empty($item[2])) {
                if (!empty($item[3]) && $item[3] == true) {
                    $whereArr[] = " " . $item[0] . " " . $item[1] . " " . $item[2];
                } else {
                    $item[2] = "'$item[2]'";
                    $whereArr[] = " " . implode(" ", $item);
                }
            }
        }

        $orWhereArr = [];
        foreach ($value['orwhere'] as $item) {
            if ($item[2] !== "") {
                if ($item[3] == true) {
                    $orWhereArr[] = " " . $item[0] . " " . $item[1] . " " . $item[2];
                } else {
                    $item[2] = "'$item[2]'";
                    $orWhereArr[] = " " . implode(" ", $item);
                }
            }
        }
        if (count($whereArr) > 0) {
            $where[] = "(" . implode(" AND ", $whereArr) . ")";
        }
        if (count($orWhereArr) > 0) {
            $where[] = "(" . implode(" OR ", $orWhereArr) . ")";
        }

        if (count($where) > 0) {
            return "WHERE " . implode(" AND ", $where);
        }

        return "";
    }

    public function get($data, $debug = false)
    {

        if (empty($data['where'])) {
            $data['where'] = [];
        }
        if (empty($data['orwhere'])) {
            $data['orwhere'] = [];
        }
        $where = $this->queryWhereClause([
            "where" => $data['where'],
            "orwhere" => $data['orwhere']
        ]);


        $sql = "SELECT * FROM $this->table $where";

        if ($debug) {
            print_r($data);
            die;
        }
        $query = $this->db->query($sql);
        $query->execute();

        return $query->fetch();
    }

    public function getAll($data, $debug = false)
    {

        $sort = $this->querySort($data['sort']);

        $limit = $this->queryPagination($data['pagination']);

        if (empty($data['where'])) {
            $data['where'] = [];
        }
        if (empty($data['orwhere'])) {
            $data['orwhere'] = [];
        }
        $where = $this->queryWhereClause([
            "where" => $data['where'],
            "orwhere" => $data['orwhere']
        ]);

        $sql = "SELECT * FROM $this->table $where $sort $limit";

        if ($debug) {
            http_response_code(500);
            print_r($sql);
            die;
        }

        $query = $this->db->query($sql);
        $query->execute();

        return $query->fetchAll();
    }

    public function getTotal($data)
    {

        if (empty($data['where'])) {
            $data['where'] = [];
        }
        if (empty($data['orwhere'])) {
            $data['orwhere'] = [];
        }
        $where = $this->queryWhereClause([
            "where" => $data['where'],
            "orwhere" => $data['orwhere']
        ]);

        $query = $this->db->query("SELECT COUNT(*) as total FROM $this->table $where");
        $query->execute();

        return $query->fetch();
    }

    public function insert($data)
    {

        $label = "";
        $value = "";
        $data = array_merge($data, [
            "created_at" => date("Y-m-d H:i:s"),
            "updated_at" => date("Y-m-d H:i:s")
        ]);
        foreach ($data as $key => $dt) {
            if ($label != "") {
                $label .= ", ";
            }
            $label .= $key;

            if ($value != "") {
                $value .= ", ";
            }
            $value .= "'$dt'";
        }


        $query = $this->db->query("INSERT INTO $this->table ($label) VALUES($value)");
        $query->execute();

        return $this->db->lastInsertedId();
    }
    public function update($data)
    {

        $set = array_merge($data['set'], [
            "updated_at" => date("Y-m-d H:i:s")
        ]);
        $update = [];
        foreach ($set as $key => $dt) {
            $update[] = "$key = '$dt'";
        }


        $where = "";
        $whereArr = [];

        foreach ($data['where'] as $item) {
            if ($item[3] == true) {
                $whereArr[] = " " . $item[0] . " " . $item[1] . " " . $item[2];
            } else {
                $item[2] = "'$item[2]'";
                $whereArr[] = " " . implode(" ", $item);
            }
        }
        if (count($whereArr) > 0) {
            $where = "WHERE " . implode(" AND ", $whereArr);
        }


        $query = $this->db->query("UPDATE $this->table SET " . implode(", ", $update) . " $where");
        return $query->execute();
    }

    public function delete($data, $debug = false)
    {

        try {

            if (empty($data['where'])) {
                $data['where'] = [];
            }
            if (empty($data['orwhere'])) {
                $data['orwhere'] = [];
            }
            $where = $this->queryWhereClause([
                "where" => $data['where'],
                "orwhere" => $data['orwhere']
            ]);
            $sql = "DELETE FROM $this->table $where";
            if ($debug) {
                http_response_code(500);
                print_r($sql);
                die;
            }
            $query = $this->db->query($sql);
            return $query->execute();
        } catch (Exception $err) {
            throw new Exception($err);
        }
    }

    public function checkDuplicate($column, $value)
    {

        $expValue = explode("-", $value);

        if (count($expValue) > 1) {
            if (is_numeric($expValue[count($expValue) - 1])) {
                unset($expValue[count($expValue) - 1]);
            }
        }

        $impValue = implode("-", $expValue);
        $query = $this->db->query("SELECT id, $column, origin, number FROM (SELECT id, $column, cast(replace(replace($column, '$impValue',''),'-','') as int) as number, replace(replace($column, cast(replace(replace($column, '$impValue',''),'-','') as int),''),'-','') as origin FROM $this->table WHERE $column like '$impValue%') tbl WHERE origin='$impValue' ORDER BY number DESC; LIMIT 1");



        $query->execute();

        return $query->fetch();
    }
}
