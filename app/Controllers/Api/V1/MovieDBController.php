<?php

namespace App\Controllers\Api\V1;

use App\Controllers\Controller;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Client;

class MovieDBController extends Controller

{

    private $client;

    function __construct()
    {
        $this->client = new Client;
        $this->apiKey = "af1878ff2a4c7a3b7bdce9c0e439fe48";
    }

    public function popularMovies()
    {
        try {

            $page = !empty($_GET['page']) ? $_GET['page'] : 1;
            $response = $this->client->get('https://api.themoviedb.org/3/movie/popular?page=' . $page . '&api_key=' . $this->apiKey);
            $body = $response->getBody();

            return $this->json([
                "message" => "Success",
                "data" => json_decode($body->getContents())
            ]);
        } catch (ClientException $e) {
            $response = $e->getResponse();
            $body = $response->getBody();

            return $this->json([
                "message" => "Fail",
                "error" => json_decode($body->getContents())
            ], 400);
        }
    }

    public function popularTVs()
    {
        try {

            $page = !empty($_GET['page']) ? $_GET['page'] : 1;
            $response = $this->client->get('https://api.themoviedb.org/3/tv/popular?page=' . $page . '&api_key=' . $this->apiKey);
            $body = $response->getBody();

            return $this->json([
                "message" => "Success",
                "data" => json_decode($body->getContents())
            ]);
        } catch (ClientException $e) {
            $response = $e->getResponse();
            $body = $response->getBody();

            return $this->json([
                "message" => "Fail",
                "error" => json_decode($body->getContents())
            ], 400);
        }
    }
}
