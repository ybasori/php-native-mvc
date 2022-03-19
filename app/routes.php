<?php

use System\Router;

use App\Controllers\HomeController;

$router = new Router();

$router->any([HomeController::class, 'index']);

$router->run();
