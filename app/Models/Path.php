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
    public function checkExistingFullPath($value)
    {

        $expValue = explode("-", $value);

        if (count($expValue) > 1) {
            if (is_numeric($expValue[count($expValue) - 1])) {
                unset($expValue[count($expValue) - 1]);
            }
        }

        $impValue = implode("-", $expValue);
        $query = $this->db->query("SELECT full_path, number FROM (SELECT full_path,  cast(replace(replace(full_path,'$impValue',''),'-','') as int) as number FROM $this->table WHERE full_path like '$impValue%') tbl ORDER BY number DESC LIMIT 1");
        $query->execute();

        return $query->fetch();
    }
}
