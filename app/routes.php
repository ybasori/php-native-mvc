<?php

use System\Router;

use App\Controllers\ReactJSController;
use App\Controllers\Api\V1\MarvelController;
use App\Controllers\Api\V1\MovieDBController;
use App\Controllers\Api\V1\PokemonController;
use App\Controllers\Api\V1\SpotifyController;

$router = new Router();

$router->any([ReactJSController::class, 'index']);

$router->get("/api/v1/marvel/characters", [MarvelController::class, 'characters']);
$router->get("/api/v1/marvel/comics", [MarvelController::class, 'comics']);
$router->get("/api/v1/marvel/creators", [MarvelController::class, 'creators']);
$router->get("/api/v1/marvel/events", [MarvelController::class, 'events']);
$router->get("/api/v1/marvel/series", [MarvelController::class, 'series']);
$router->get("/api/v1/marvel/stories", [MarvelController::class, 'stories']);

$router->get("/api/v1/moviedb/popular-movies", [MovieDBController::class, 'popularMovies']);
$router->get("/api/v1/moviedb/popular-tvs", [MovieDBController::class, 'popularTVs']);

$router->get("/api/v1/pokemon", [PokemonController::class, 'allPokemon']);
$router->get("/api/v1/pokemon/:id", [PokemonController::class, 'pokemon']);

$router->post("/api/v1/spotify/login", [SpotifyController::class, 'login']);
$router->post("/api/v1/spotify/refresh", [SpotifyController::class, 'refresh']);
$router->get("/api/v1/spotify/search", [SpotifyController::class, 'search']);

$router->run();
