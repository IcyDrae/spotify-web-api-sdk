<?php


namespace SpotifyAPI\Http\Controllers;

use Exception;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Message;
use SpotifyAPI\Http\Response;
use SpotifyAPI\Http\Client;
use Config\SecretsCollection;

/**
 * Class AuthenticationController
 * @package SpotifyAPI\Http\Controllers
 * @author Reard Gjoni
 *
 * Responsible for
 *      (1) generating a request URL where the user will be prompted to give access,
 *      (2) requesting access/refresh tokens &
*       (3) refreshing the expired access token in order to stay logged in
 * and not repeat the authorization process
 *
 */
class AuthenticationController {
    /**
     * @var Client $client
     */
    private Client $client;

    /**
     * @var Response $response
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

        $this->response = $this->client->getResponse();
        $this->headers = $this->client->getHeaders();
        $this->configs = $this->client->getConfigs();
    }

    /**
     * Requests authorization code
     *
     * @return string
     */
    public function buildAuthorizationUrl() {
        try {
            $config = $this->client->getConfigs();

            $options = [
                "response_type" => $this->configs["response_type"],
                "client_id" => $this->configs["client_id"],
                "redirect_uri" => $this->configs["redirect_uri"],
                "scope" => $this->configs["scope"]
            ];

            $url = SecretsCollection::$baseUri . "/authorize?" . http_build_query($options, null, "&");

            return $this->response->json([
                "url" => $url,
            ]);
        } catch (Exception $exception) {
            return $this->response->json([
                "error" => $exception->getMessage()
            ]);
        }
    }

    /**
     * @return string
     */
    public function requestAccessToken() {
        try {
            $request = $this->client->post("/api/token", [
                "headers" => [
                    "Authorization" => $this->configs["headers"]["authorization_access"],
                ],
                "form_params" => [
                    "grant_type" => $this->configs["grant_type"],
                    "code" => $this->headers["Auth-Code"],
                    "redirect_uri" => $this->configs["redirect_uri"],
                ],
            ]);

            setrawcookie("access_token", json_decode($request->getBody()->getContents(), true)["access_token"]);

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
    public function refreshAccessToken() {
        try {
            $request = $this->client->post("/api/token", [
                "headers" => [
                    "Authorization" => $this->configs["headers"]["authorization_access"],
                ],
                "form_params" => [
                    "grant_type" => "refresh_token",
                    "refresh_token" => $this->headers["Refresh-Token"],
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
}
