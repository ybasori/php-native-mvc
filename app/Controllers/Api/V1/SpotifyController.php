<?php

namespace App\Controllers\Api\V1;

use App\Controllers\Controller;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Client;

class SpotifyController extends Controller

{
    private $client;
    private $clientId;
    private $clientSecret;

    function __construct()
    {
        $this->client = new Client;
        $this->clientId = $_ENV['SPOTIFY_CLIENT_ID'];
        $this->clientSecret = $_ENV['SPOTIFY_CLIENT_SECRET'];
    }

    public function login()
    {

        try {
            $response = $this->client->post("https://accounts.spotify.com/api/token", [
                'headers' => [
                    'Authorization' => 'Basic ' . base64_encode($this->clientId . ':' . $this->clientSecret),
                ],
                'form_params' => [
                    'code' => $_POST['code'],
                    'redirect_uri' => $_ENV['SPOTIFY_REDIRECT_URI'],
                    'grant_type' => 'authorization_code'
                ]
            ]);
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
                "error" => json_decode($body->getContents()),
            ], 400);
        }
    }

    public function refresh()
    {
        try {
            $response = $this->client->post("https://accounts.spotify.com/api/token", [
                'headers' => [
                    'Authorization' => 'Basic ' . base64_encode($this->clientId . ':' . $this->clientSecret),
                ],
                'form_params' => [
                    'grant_type' => 'refresh_token',
                    'refresh_token' => $_POST['refresh_token']
                ]
            ]);
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
                "error" => json_decode($body->getContents()),
            ], 400);
        }
    }


    public function search()
    {
        try {
            $response = $this->client->get('https://api.spotify.com/v1/search?q=' . $_GET['q'] . '&type=' . $_GET['type'], [
                'headers' => [
                    'Authorization' => $_SERVER['HTTP_AUTHORIZATION'],
                ],
            ]);
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
                "error" => json_decode($body->getContents()),
            ], 400);
        }
    }
}
