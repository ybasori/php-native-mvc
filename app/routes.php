<?php

use System\Router;

use App\Controllers\HomeController;
use App\Controllers\Api\V1\MarvelController;

use App\Controllers\Api\V1\MovieDBController;

$router = new Router();

$router->any([HomeController::class, 'index']);

$router->get("/api/v1/marvel/characters", [MarvelController::class, 'characters']);
$router->get("/api/v1/marvel/comics", [MarvelController::class, 'comics']);
$router->get("/api/v1/marvel/creators", [MarvelController::class, 'creators']);
$router->get("/api/v1/marvel/events", [MarvelController::class, 'events']);
$router->get("/api/v1/marvel/series", [MarvelController::class, 'series']);
$router->get("/api/v1/marvel/stories", [MarvelController::class, 'stories']);

$router->get("/api/v1/moviedb/popular-movies", [MovieDBController::class, 'popularMovies']);
$router->get("/api/v1/moviedb/popular-tvs", [MovieDBController::class, 'popularTVs']);

$router->run();
