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
        $this->ts = time() * 1000;
        $this->publicKey = $_ENV['MARVEL_PUBLIC_KEY'];
        $this->hash = md5($this->ts . $_ENV["MARVEL_PRIVATE_KEY"] . $this->publicKey);
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
                "time" => time(),
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
