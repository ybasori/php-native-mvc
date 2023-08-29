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

    public function get($data)
    {

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



        $query = $this->db->query("SELECT * FROM $this->table $where");
        $query->execute();

        return $query->fetch();
    }

    public function getAll($data, $debug = false)
    {

        if (empty($data['sort'])) {
            $data['sort'] = [["created_at", "ASC"]];
        }

        $sort = "";
        $sortArr = [];
        foreach ($data['sort'] as $s) {
            $sortArr[] = implode(" ", $s);
        }

        if (count($sortArr) > 0) {
            $sort = "ORDER BY " . implode(", ", $sortArr);
        }

        $limit = "";
        if (!empty($data['pagination'])) {
            $offset = ($data['pagination']['page'] - 1) * $data['pagination']['limit'];
            $limit = "LIMIT $offset," . $data['pagination']['limit'];
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

        $sql = "SELECT * FROM $this->table $where $sort $limit";

        if ($debug) {
            print_r($sql);
            die;
        }

        $query = $this->db->query($sql);
        $query->execute();

        return $query->fetchAll();
    }

    public function getTotal($data)
    {

        $where = "";
        $whereArr = [];

        foreach ($data['where'] as $item) {
            if ($item[3] == true) {
                $whereArr[] = " " . implode(" ", $item);
            } else {
                $item[2] = "'$item[2]'";
                $whereArr[] = " " . implode(" ", $item);
            }
        }
        if (count($whereArr) > 0) {
            $where = "WHERE " . implode(" AND ", $whereArr);
        }

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

    public function delete($data)
    {

        try {

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

            $query = $this->db->query("DELETE FROM $this->table $where");
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
        $query = $this->db->query("SELECT $column, origin, number FROM (SELECT $column, cast(replace(replace($column, '$impValue',''),'-','') as int) as number, replace(replace($column, cast(replace(replace($column, '$impValue',''),'-','') as int),''),'-','') as origin FROM $this->table WHERE $column like '$impValue%') tbl WHERE origin='$impValue' ORDER BY number DESC; LIMIT 1");



        $query->execute();

        return $query->fetch();
    }
}
