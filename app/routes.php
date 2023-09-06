<?php

use System\Router;


use App\Middlewares\ExampleFailMiddleware;
use App\Middlewares\ExampleSuccessMiddleware;
use App\Middlewares\AuthorizationCheck;
use App\Middlewares\SuperAdminCheck;

use App\Controllers\HomeController;
use App\Controllers\Admin\AdminController;
use App\Controllers\Admin\UserController as AdminUserController;
use App\Controllers\JSON\CustomController;
use App\Controllers\JSON\DefaultController;
use App\Controllers\JSON\AuthController;
use App\Controllers\JSON\AdminController as JSONAdminController;
use App\Controllers\JSON\UserController as JSONUserController;
use App\Controllers\ExampleController;

$router = new Router();

$router->any([HomeController::class, 'index']);

$router->get("/example", [ExampleSuccessMiddleware::class], [ExampleController::class, 'example']);
$router->get("/example-fail", [ExampleFailMiddleware::class], [ExampleController::class, 'example']);

$router->get("/admin", [AdminController::class, 'index']);
$router->get("/admin/login", [AdminController::class, 'login']);
$router->get("/admin/users", [AdminUserController::class, 'index']);
$router->get("/admin/users/:id", [AdminUserController::class, 'show']);
$router->get("/admin/try/:any", [AdminController::class, 'show']);

// default path
$router->get("/json/v1/admin/users", [
    AuthorizationCheck::class,
    SuperAdminCheck::class
], [JSONUserController::class, 'index']);

$router->get("/json/v1/admin/users/:id", [
    AuthorizationCheck::class,
    SuperAdminCheck::class
], [JSONUserController::class, 'show']);

$router->put("/json/v1/admin/users/:id", [
    AuthorizationCheck::class,
    SuperAdminCheck::class
], [JSONUserController::class, 'update']);

$router->delete("/json/v1/admin/users/:id", [
    AuthorizationCheck::class,
    SuperAdminCheck::class
], [JSONUserController::class, 'delete']);

$router->post("/json/v1/admin/path", [
    AuthorizationCheck::class,
    SuperAdminCheck::class
], [JSONAdminController::class, 'store']);
$router->put("/json/v1/admin/path/:id", [
    AuthorizationCheck::class,
    SuperAdminCheck::class
], [JSONAdminController::class, 'update']);
$router->delete("/json/v1/admin/path/:id", [
    AuthorizationCheck::class,
    SuperAdminCheck::class
], [JSONAdminController::class, 'delete']);

$router->post("/json/v1/auth/register", [AuthController::class, 'register']);
$router->post("/json/v1/auth/login", [AuthController::class, 'login']);
$router->get("/json/v1/author/:username", [DefaultController::class, 'author']);

// custom path
$router->get("/json/v1/custom", [CustomController::class, 'index']);
$router->get("/json/v1/custom/:any", [CustomController::class, 'show']);

$router->delete("/json/v1/custom/:any", [
    AuthorizationCheck::class
], [CustomController::class, 'delete']);
$router->post("/json/v1/custom/:any", [
    AuthorizationCheck::class
], [CustomController::class, 'store']);
$router->put("/json/v1/custom/:any", [
    AuthorizationCheck::class
], [CustomController::class, 'update']);

$router->run();
