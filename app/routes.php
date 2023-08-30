<?php

use System\Router;


use App\Middlewares\ExampleFailMiddleware;
use App\Middlewares\ExampleSuccessMiddleware;

use App\Controllers\HomeController;
use App\Controllers\AdminController;
use App\Controllers\JSON\JSONController;
use App\Controllers\JSON\AuthController;
use App\Controllers\ExampleController;

$router = new Router();

$router->any([HomeController::class, 'index']);

$router->get("/example", [ExampleSuccessMiddleware::class], [ExampleController::class, 'example']);
$router->get("/example-fail", [ExampleFailMiddleware::class], [ExampleController::class, 'example']);

$router->get("/admin", [AdminController::class, 'index']);
$router->get("/admin/:any", [AdminController::class, 'show']);
$router->post("/admin", [AdminController::class, 'store']);
$router->put("/admin", [AdminController::class, 'update']);
$router->delete("/admin", [AdminController::class, 'delete']);

// default path
$router->post("/json/auth/register", [AuthController::class, 'register']);
$router->post("/json/auth/login", [AuthController::class, 'login']);
$router->get("/json/author/:username", [JSONController::class, 'author']);

// custom path
$router->get("/json/custom", [JSONController::class, 'index']);
$router->get("/json/custom/:any", [JSONController::class, 'show']);
$router->delete("/json/custom/:any", [JSONController::class, 'delete']);
$router->post("/json/custom/:any", [JSONController::class, 'store']);
$router->put("/json/custom/:any", [JSONController::class, 'update']);

$router->run();
