<?php


namespace SpotifyAPI\Controllers;

use GuzzleHttp\RedirectMiddleware;
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
     * @var array $tokens
     */
    private $tokens = [
        "access_token",
        "refresh_token"
    ];

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
     * Requests authorization code, gets it and calls getAccessToken() with it
     *
     * @return Response
     */
    public function requestAuthCode() {
        try {
            $config = $this->client->configArray;

            $response = $this->client->get( "/authorize", [
                "query" => [
                    "response_type" => $config["response_type"],
                    "client_id" => $config["client_id"],
                    "redirect_uri" => $config["redirect_uri"],
                    "scope" => $config["scope"]
                ]
            ]);

            $redirect = $response->getHeader(RedirectMiddleware::HISTORY_HEADER);

            if (isset($redirect[0]) && is_string($redirect[0])) {
                $output = new Response();

                return $output->json([
                    "url" => $redirect[0]
                ]);
            }
            #$this->getAccessToken();

        } catch (RequestException $exception) {
            echo $exception->getMessage();
            if ($exception->hasResponse()) {
                echo Psr7\str($exception->getResponse());
            }
        }
    }

    /**
     * Calls getUserProfile() with the access token
     * @param $code
     * @return mixed
     */
    public function getAccessToken($code) {
        $config = $this->client->configArray;
        $output = new Response();

        try {
            $request = $this->client->post("/api/token", [
                "headers" => [
                    "Authorization" => $config["headers"]["authorization_access"],
                ],
                "form_params" => [
                    "grant_type" => $config["grant_type"],
                    "code" => $code,
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
