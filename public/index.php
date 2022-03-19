<?php

require_once __DIR__ . '/../vendor/autoload.php';

date_default_timezone_set('UTC');

if (file_exists(__DIR__ . '/../.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();
}

error_reporting(E_ALL);

ini_set("display_errors", "0");

register_shutdown_function([\System\HandleError::onError(), "onError"]);


// require_once __DIR__ . '/../system/Functions.php';
// require_once __DIR__ . '/../app/routes.php';
