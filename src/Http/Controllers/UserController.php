<?php


namespace SpotifyAPI\Http\Controllers;

use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7;
use SpotifyAPI\Http\Response;
use SpotifyAPI\Http\Client;
use Config\SecretsCollection;

class UserController
{
    /**
     * @var Client $client
     */
    private Client $client;

    public function __construct()
    {
        $this->client = new Client(
            SecretsCollection::$apiUri,
            1,
            ["track_redirects" => true]
        );
    }

    /**
     * Fetches us the user profile after authenticating
     * @return Response
     */
    public function getProfile()
    {
        $config = $this->client->getConfigArray();
        $output = new Response();
        $reqHeaders = getallheaders();

        try {
            $request = $this->client->get( "/v1/me", [
                "headers" => [
                    "Accept" => $config["headers"]["accept"],
                    "Content-Type" => $config["headers"]["content_type"],
                    "Authorization" => sprintf("Bearer %s",  $reqHeaders["Access-Token"])
                ],
            ]);

            $body = $request->getBody();

            $body = json_decode($body, true);

            return $output->json([
                "body" => $body
            ]);

        } catch (RequestException $exception) {
            if ($exception->hasResponse()) {
                $output->json([
                    "error" => $exception->getMessage()
                ]);
            }
        }
    }

    public function getPlaylists() {
        $output = new Response();
        $reqHeaders = getallheaders();

        try {
            $config = $this->client->getConfigArray();

            $request = $this->client->get("/v1/me/playlists", [
                "headers" => [
                    "Accept" => $config["headers"]["accept"],
                    "Content-Type" => $config["headers"]["content_type"],
                    "Authorization" => sprintf("Bearer %s",  $reqHeaders["Access-Token"])
                ]
            ]);

            $body = json_decode($request->getBody());

            return $output->json([
                "body" => $body
            ]);
        } catch (RequestException $exception) {
            echo Psr7\str($exception->getRequest());
            if ($exception->hasResponse()) {
                echo Psr7\str($exception->getResponse());
            }
        }
    }

}
