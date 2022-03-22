<?php

namespace App\Controllers\Api\V1;

use App\Controllers\Controller;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Client;

class PokemonController extends Controller

{

    private $client;

    function __construct()
    {
        $this->client = new Client;
    }

    public function allPokemon()
    {
        try {

            $limit = !empty($_GET['limit']) ? $_GET['limit'] : 10;
            $offset = !empty($_GET['offset']) ? $_GET['offset'] : 0;
            $response = $this->client->get('https://pokeapi.co/api/v2/pokemon?offset=' . $offset . '&limit=' . $limit);
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

    public function pokemon($request)
    {
        try {
            $response = $this->client->get('https://pokeapi.co/api/v2/pokemon/' . $request->id);
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
