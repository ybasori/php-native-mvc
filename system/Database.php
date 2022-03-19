<?php

namespace System;

use PDO;

class Database
{

    private $pdo;

    function __construct()
    {

        if (!empty($_ENV['DB_HOST']) && !empty($_ENV['DB_USER']) && !empty($_ENV['DB_PASS']) && !empty($_ENV['DB_NAME']) && !empty($_ENV['DB_CONN'])) {
            $dbhost = $_ENV['DB_HOST'];
            $dbuser = $_ENV['DB_USER'];
            $dbpass = $_ENV['DB_PASS'];
            $dbname = $_ENV['DB_NAME'];
            $dbconn = $_ENV['DB_CONN'];
            $dsn = $dbconn . ':host=' . $dbhost . ';dbname=' . $dbname;
            $this->pdo = new PDO($dsn, $dbuser, $dbpass);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        }
    }

    public function query($sql)
    {

        $stmt = $this->pdo->prepare($sql);

        return $stmt;
    }
}
