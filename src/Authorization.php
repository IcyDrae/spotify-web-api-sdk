<?php


namespace Gjoni\SpotifyWebApiSdk;

use Exception;
use Gjoni\SpotifyWebApiSdk\Interfaces\SdkInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Message;
use Gjoni\SpotifyWebApiSdk\Http\Response;
use Gjoni\SpotifyWebApiSdk\Http\Client;
use Gjoni\SpotifyWebApiSdk\Config\SecretsCollection;

/**
 * Responsible for
 *      1. generating a request URL where the user will be prompted to give access,
 *      2. requesting access/refresh tokens &
 *      3. refreshing the expired access token in order to stay logged in and not repeat the authorization process
 *
 * @package Gjoni\SpotifyWebApiSdk
 * @author Reard Gjoni <gjoni-r@hotmail.com>
 *
 */
class Authorization {
    /**
     * @var Client $client Client object
     */
    private Client $client;

    /**
     * @var Response $response Response object
     */
    private Response $response;

    /**
     * @var array $headers Client headers
     */
    private array $headers;

    /**
     * @var array $parameters Client parameters
     */
    private array $parameters;

    /**
     * @var SdkInterface $sdk Sdk object
     */
    private SdkInterface $sdk;

    /**
     * Authorization constructor.
     *
     * Initializes the SDK object, client, response, headers and client config
     *
     * @param SdkInterface $sdk An SDK object
     */
    public function __construct(SdkInterface $sdk)
    {
        $this->sdk = $sdk;

        $this->client = new Client($sdk, [
            "base_uri" => SecretsCollection::$baseUri,
            "timeout" => 1,
            "allow_redirects" => ["track_redirects" => true]
        ]);

        $this->response = $this->client->getResponse();
        $this->headers = $this->client->getHeaders();
        $this->parameters = $this->client->getParameters();
    }

    /**
     * Builds the URL the user will be redirected to for authorization
     *
     * @return string
     */
    public function buildUrl(): string {
        try {
            $options = [
                "response_type" => $this->parameters["response_type"],
                "client_id" => $this->sdk->getClientId(),
                "redirect_uri" => $this->parameters["redirect_uri"],
                "scope" => $this->parameters["scope"]
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
                    "Authorization" => $this->parameters["headers"]["authorization_access"],
                    "Content-Type" => $this->parameters["headers"]["ctype_urlencoded"]
                ],
                "form_params" => [
                    "grant_type" => $this->parameters["grant_type"],
                    "code" => $authCode,
                    "redirect_uri" => $this->parameters["redirect_uri"],
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
                    "Authorization" => $this->parameters["headers"]["authorization_access"],
                    "Content-Type" => $this->parameters["headers"]["ctype_urlencoded"]
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
