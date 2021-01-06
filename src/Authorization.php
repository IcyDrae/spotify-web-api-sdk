<?php


namespace Gjoni\SpotifyWebApiSdk;

use Exception;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Message;
use Gjoni\SpotifyWebApiSdk\Http\Response;
use Gjoni\SpotifyWebApiSdk\Http\Client;
use Config\SecretsCollection;

/**
 * Class Authorization
 * @package Gjoni\SpotifyWebApiSdk
 * @author Reard Gjoni <gjoni-r@hotmail.com>
 *
 * Responsible for
 *      (1) generating a request URL where the user will be prompted to give access,
 *      (2) requesting access/refresh tokens &
 *      (3) refreshing the expired access token in order to stay logged in and not repeat the authorization process
 *
 */
class Authorization {
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
     * AuthorizationController constructor.
     *
     * Initializes the client object, response, headers and client config
     */
    public function __construct()
    {
        $this->client = new Client([
            "base_uri" => SecretsCollection::$baseUri,
            "timeout" => 1,
            "allow_redirects" => ["track_redirects" => true]
        ]);

        $this->response = $this->client->getResponse();
        $this->headers = $this->client->getHeaders();
        $this->configs = $this->client->getConfigs();
    }

    /**
     * Builds the URL the user will be redirected to give authorization
     *
     * @return string
     */
    public function buildUrl(): string {
        try {
            $options = [
                "response_type" => $this->configs["response_type"],
                "client_id" => $this->configs["client_id"],
                "redirect_uri" => $this->configs["redirect_uri"],
                "scope" => $this->configs["scope"]
            ];

            $url = SecretsCollection::$baseUri . "/authorize?" . http_build_query($options, null, "&");

            return $this->response->json([
                "data" => [
                    "url" => $url,
                ]
            ]);
        } catch (Exception $exception) {
            return $this->response->json([
                "error" => $exception->getMessage()
            ]);
        }
    }

    /**
     * Using an authorization code from the client(as a header), will request an access token.
     *
     * If successful, returns:
     *  (1) access token
     *  (2) refresh token
     *  (3) expiry timestamp for the access token
     *
     * @return string
     */
    public function requestAccessToken(): string {
        $authCode = $this->headers["Auth-Code"] ?? '';

        try {
            $request = $this->client->post("/api/token", [
                "headers" => [
                    "Authorization" => $this->configs["headers"]["authorization_access"],
                    "Content-Type" => $this->configs["headers"]["content_type_urlencoded"]
                ],
                "form_params" => [
                    "grant_type" => $this->configs["grant_type"],
                    "code" => $authCode,
                    "redirect_uri" => $this->configs["redirect_uri"],
                ],
            ]);

            $body = $request->getBody();

            $body = json_decode($body, true);
            $timestamp = time() + $body["expires_in"];

            $body["expires_in"] = $timestamp;

            return $this->response->json([
                "data" => [
                    "body" => $body,
                ]
            ]);
        } catch (RequestException $exception) {
            if ($exception->hasResponse()) {
                $request = Message::toString($exception->getRequest());
            }

            return $this->response->json([
                "error" => $exception->getMessage(),
                "request" => $request ?? ''
            ], $exception->getCode());
        }
    }

    /**
     * This method requests a new access token based on the refresh token
     *
     * @return string
     */
    public function refreshAccessToken(): string {
        $refreshToken = $this->headers["Refresh-Token"] ?? '';

        try {
            $request = $this->client->post("/api/token", [
                "headers" => [
                    "Authorization" => $this->configs["headers"]["authorization_access"],
                    "Content-Type" => $this->configs["headers"]["content_type_urlencoded"]
                ],
                "form_params" => [
                    "grant_type" => "refresh_token",
                    "refresh_token" => $refreshToken
                ],
            ]);

            $body = $request->getBody();

            $body = json_decode($body, true);

            $timestamp = time() + $body["expires_in"];

            $body["expires_in"] = $timestamp;

            return $this->response->json([
                "data" => [
                    "body" => $body
                ]
            ]);
        } catch (RequestException $exception) {
            if ($exception->hasResponse()) {
                $request = Message::toString($exception->getRequest());
            }

            return $this->response->json([
                "error" => $exception->getMessage(),
                "request" => $request ?? ''
            ], $exception->getCode());
        }
    }
}
