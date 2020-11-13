<?php


namespace SpotifyAPI\Http\Controllers;

use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Message;
use SpotifyAPI\Http\Response;
use SpotifyAPI\Http\Client;
use Config\SecretsCollection;

class UserController
{
    /**
     * @var Client $client
     */
    private Client $client;

    /**
     * @var Response
     */
    private Response $response;

    /**
     * @var array $headers
     */
    private array $headers;

    /**
     * @var array $configs
     */
    private array $configs;

    public function __construct()
    {
        $this->client = new Client(
            SecretsCollection::$apiUri,
            1,
            ["track_redirects" => true]
        );

        $this->response = $this->client->getResponse();
        $this->headers = $this->client->getHeaders();
        $this->configs = $this->client->getConfigs();
    }

    /**
     * Fetches us the user profile after authenticating
     *
     * @return string
     */
    public function getProfile()
    {
        try {
            $request = $this->client->get( "/v1/me", [
                "headers" => [
                    "Accept" => $this->configs["headers"]["accept"],
                    "Content-Type" => $this->configs["headers"]["content_type"],
                    "Authorization" => sprintf("Bearer %s",  $this->configs["Access-Token"])
                ],
            ]);

            $body = $request->getBody();

            $body = json_decode($body, true);

            return $this->response->json([
                "body" => $body
            ]);

        } catch (RequestException $exception) {
            if ($exception->hasResponse()) {
                return $this->response->json([
                    "error" => $exception->getMessage(),
                    "request" => Message::toString($exception->getRequest())
                ], $exception->getCode());
            }
        }
    }

    /**
     * @return string
     */
    public function getPlaylists() {
        try {
            $request = $this->client->get("/v1/me/playlists", [
                "headers" => [
                    "Accept" => $this->configs["headers"]["accept"],
                    "Content-Type" => $this->configs["headers"]["content_type"],
                    "Authorization" => sprintf("Bearer %s",  $this->configs["Access-Token"])
                ]
            ]);

            $body = json_decode($request->getBody());

            return $this->response->json([
                "body" => $body
            ]);
        } catch (RequestException $exception) {
            if ($exception->hasResponse()) {
                return $this->response->json([
                    "error" => $exception->getMessage(),
                    "request" => Message::toString($exception->getRequest())
                ], $exception->getCode());
            }
        }
    }
}
