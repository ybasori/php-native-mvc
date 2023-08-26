<?php

use System\Router;


use App\Middlewares\ExampleFailMiddleware;
use App\Middlewares\ExampleSuccessMiddleware;

use App\Controllers\HomeController;
use App\Controllers\AdminController;
use App\Controllers\JSONController;
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

$router->get("/json", [JSONController::class, 'index']);
$router->get("/json/:any", [JSONController::class, 'show']);
$router->delete("/json/:any", [JSONController::class, 'delete']);
$router->post("/json/:any", [JSONController::class, 'store']);
$router->put("/json/:any", [JSONController::class, 'update']);

$router->get("/docs/:any", [HomeController::class, 'docs_any']);

$router->run();
