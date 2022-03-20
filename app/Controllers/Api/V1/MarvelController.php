<?php

namespace App\Controllers\Api\V1;

use App\Controllers\Controller;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Client;

class MarvelController extends Controller

{
    private $client;

    function __construct()
    {
        $this->client = new Client;
        $this->ts = "1625134934096";
        $this->publicKey = "2937e16ac1c161b358cc7f72096477cb";
        $this->hash = "15fccf32d2c1eb7feedd98f15797cd80";
    }

    public function characters()
    {
        try {

            $limit = !empty($_GET['limit']) ? $_GET['limit'] : 10;
            $offset = !empty($_GET['offset']) ? $_GET['offset'] : 0;
            $response = $this->client->get('https://gateway.marvel.com/v1/public/characters?limit=' . $limit . '&offset=' . $offset . '&ts=' . $this->ts . '&hash=' . $this->hash . '&apikey=' . $this->publicKey);
            $body = $response->getBody();

            return $this->json([
                "message" => "Success",
                "data" => json_decode($body->getContents())->data
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
    public function comics()
    {
        try {

            $limit = !empty($_GET['limit']) ? $_GET['limit'] : 10;
            $offset = !empty($_GET['offset']) ? $_GET['offset'] : 0;
            $response = $this->client->get('https://gateway.marvel.com/v1/public/comics?limit=' . $limit . '&offset=' . $offset . '&ts=' . $this->ts . '&hash=' . $this->hash . '&apikey=' . $this->publicKey);
            $body = $response->getBody();

            return $this->json([
                "message" => "Success",
                "data" => json_decode($body->getContents())->data
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
    public function creators()
    {
        try {

            $limit = !empty($_GET['limit']) ? $_GET['limit'] : 10;
            $offset = !empty($_GET['offset']) ? $_GET['offset'] : 0;
            $response = $this->client->get('https://gateway.marvel.com/v1/public/creators?limit=' . $limit . '&offset=' . $offset . '&ts=' . $this->ts . '&hash=' . $this->hash . '&apikey=' . $this->publicKey);
            $body = $response->getBody();

            return $this->json([
                "message" => "Success",
                "data" => json_decode($body->getContents())->data
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
    public function events()
    {
        try {

            $limit = !empty($_GET['limit']) ? $_GET['limit'] : 10;
            $offset = !empty($_GET['offset']) ? $_GET['offset'] : 0;
            $response = $this->client->get('https://gateway.marvel.com/v1/public/events?limit=' . $limit . '&offset=' . $offset . '&ts=' . $this->ts . '&hash=' . $this->hash . '&apikey=' . $this->publicKey);
            $body = $response->getBody();

            return $this->json([
                "message" => "Success",
                "data" => json_decode($body->getContents())->data
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
    public function series()
    {
        try {

            $limit = !empty($_GET['limit']) ? $_GET['limit'] : 10;
            $offset = !empty($_GET['offset']) ? $_GET['offset'] : 0;
            $response = $this->client->get('https://gateway.marvel.com/v1/public/series?limit=' . $limit . '&offset=' . $offset . '&ts=' . $this->ts . '&hash=' . $this->hash . '&apikey=' . $this->publicKey);
            $body = $response->getBody();

            return $this->json([
                "message" => "Success",
                "data" => json_decode($body->getContents())->data
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
    public function stories()
    {
        try {

            $limit = !empty($_GET['limit']) ? $_GET['limit'] : 10;
            $offset = !empty($_GET['offset']) ? $_GET['offset'] : 0;
            $response = $this->client->get('https://gateway.marvel.com/v1/public/stories?limit=' . $limit . '&offset=' . $offset . '&ts=' . $this->ts . '&hash=' . $this->hash . '&apikey=' . $this->publicKey);
            $body = $response->getBody();

            return $this->json([
                "message" => "Success",
                "data" => json_decode($body->getContents())->data
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
