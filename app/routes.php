<?php

use System\Router;


use App\Middlewares\ExampleFailMiddleware;
use App\Middlewares\ExampleSuccessMiddleware;

use App\Controllers\HomeController;
use App\Controllers\AdminController;
use App\Controllers\JSON\CustomController;
use App\Controllers\JSON\DefaultController;
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
$router->post("/json/v1/auth/register", [AuthController::class, 'register']);
$router->post("/json/v1/auth/login", [AuthController::class, 'login']);
$router->get("/json/v1/author/:username", [DefaultController::class, 'author']);

// custom path
$router->get("/json/v1/custom", [CustomController::class, 'index']);
$router->get("/json/v1/custom/:any", [CustomController::class, 'show']);
$router->delete("/json/v1/custom/:any", [CustomController::class, 'delete']);
$router->post("/json/v1/custom/:any", [CustomController::class, 'store']);
$router->put("/json/v1/custom/:any", [CustomController::class, 'update']);

$router->run();
