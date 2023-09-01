<?php

namespace App\Models;

use System\Model;
use System\Database as DB;

class Path extends Model
{

    public $table = "paths";
    private $db;

    function __construct()
    {
        parent::__construct();
        $this->db = new DB;
    }

    function getByPath($prefix, $path, $debug = false)
    {
        $sql = "SELECT * FROM (SELECT CONCAT('" . $prefix . "', full_path) as pathname, paths.* FROM paths) tbl_path WHERE pathname='$path'";


        if ($debug) {
            print_r($sql);
            die;
        }

        $query = $this->db->query($sql);
        $query->execute();

        return $query->fetch();
    }
}
