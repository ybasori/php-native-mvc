<?php

namespace App\Models;

use System\Database as DB;

class Link
{

    private $db;

    function __construct()
    {
        $this->db = new DB;
    }

    public function getLinkByUid($uid)
    {

        $query = $this->db->query("SELECT * FROM links WHERE uid=:uid");
        $query->execute([
            "uid" => $uid,
        ]);

        return $query->fetch();
    }
}
