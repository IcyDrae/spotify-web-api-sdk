<?php


namespace SpotifyAPI\Http\Controllers;

use Exception;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Message;
use SpotifyAPI\Http\Response;
use SpotifyAPI\Http\Client;
use Config\SecretsCollection;

/**
 * Class Authentication
 * Authenticates the user and gives access and refresh tokens
 */
class AuthenticationController {
    /**
     * @var Client $client
     */
    private $client;

    /**
     * @var \GuzzleHttp\Client
     */
    private $response;

    /**
     * Authentication constructor.
     */
    public function __construct()
    {
        $this->client = new Client(
            SecretsCollection::$baseUri,
            1,
            ["track_redirects" => true]
        );
    }

    /**
     * Requests authorization code
     *
     * @return Response
     */
    public function buildAuthorizationUrl() {
        $response = new Response();

        try {
            $config = $this->client->configArray;

            $options = [
                "response_type" => $config["response_type"],
                "client_id" => $config["client_id"],
                "redirect_uri" => $config["redirect_uri"],
                "scope" => $config["scope"]
            ];

            $url = SecretsCollection::$baseUri . "/authorize?" . http_build_query($options, null, "&");

            return $response->json([
                "url" => $url,
            ]);
        } catch (Exception $exception) {
            return $response->json([
                "error" => $exception->getMessage()
            ]);
        }
    }

    /**
     * @return mixed
     */
    public function requestAccessToken() {
        $config = $this->client->configArray;
        $output = new Response();
        $reqHeaders = getallheaders();

        try {
            $request = $this->client->post("/api/token", [
                "headers" => [
                    "Authorization" => $config["headers"]["authorization_access"],
                ],
                "form_params" => [
                    "grant_type" => $config["grant_type"],
                    "code" => $reqHeaders["Auth-Code"],
                    "redirect_uri" => $config["redirect_uri"],
                ],
            ]);

            setrawcookie("access_token", json_decode($request->getBody()->getContents(), true)["access_token"]);

            $body = $request->getBody();

            $body = json_decode($body, true);

            return $output->json([
                "body" => $body
            ]);
        } catch (RequestException $exception) {
            if ($exception->hasResponse()) {
                $output->json([
                    "error" => $exception->getMessage(),
                    "request" => Message::toString($exception->getRequest())
                ], $exception->getCode());
            }
        }
    }
}
