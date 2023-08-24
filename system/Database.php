<?php

namespace System;

use Exception;
use PDO;

class Database
{

    private $pdo;

    function __construct()
    {

        if (!empty($_ENV['DB_HOST']) && !empty($_ENV['DB_USER']) && !empty($_ENV['DB_NAME']) && !empty($_ENV['DB_CONN']) && !empty($_ENV['DB_PORT']) && empty($_ENV['DATABASE_URL'])) {
            $dbhost = $_ENV['DB_HOST'];
            $dbuser = $_ENV['DB_USER'];
            $dbpass = $_ENV['DB_PASS'] ? $_ENV['DB_PASS'] : "";
            $dbname = $_ENV['DB_NAME'];
            $dbconn = $_ENV['DB_CONN'];
            $dbport = $_ENV['DB_PORT'];
            $dsn = $dbconn . ':host=' . $dbhost . ';dbname=' . $dbname . ';port=' . $dbport;
        }
        if (!empty($_ENV['DATABASE_URL'])) {
            $dburlarr = explode("://", $_ENV['DATABASE_URL']);
            $dburlarrarr = explode(":", $dburlarr[1]);
            $dburlarrarrarr = explode("@", $dburlarrarr[1]);
            $dburlarrarrarr1 = explode("/", $dburlarrarr[2]);

            $dbconn = $dburlarr[0] == "postgres" ? "pgsql" : $dburlarr[0];
            $dbuser = $dburlarrarr[0];
            $dbpass = $dburlarrarrarr[0];
            $dbhost = "host=" . $dburlarrarrarr[1];
            $dbname = "dbname=" . $dburlarrarrarr1[1];
            $dbport = "port=" . $dburlarrarrarr1[0];

            $dsn = $dbconn . ':' . $dbhost . ';' . $dbname . ';' . $dbport;
        }


        $this->pdo = new PDO($dsn, $dbuser, $dbpass);
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    }

    public function query($sql)
    {
        try {
            $stmt = $this->pdo->prepare($sql);

            return $stmt;
        } catch (Exception $err) {
            throw new Exception($err);
        }
    }

    public function lastInsertedId()
    {
        return $this->pdo->lastInsertId();
    }
}
