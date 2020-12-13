<?php


namespace SpotifyAPI\Http\Controllers;

use Exception;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Message;
use SpotifyAPI\Http\Response;
use SpotifyAPI\Http\Client;
use SpotifyAPI\Http\Cookie;
use Config\SecretsCollection;

/**
 * Class AuthenticationController
 * @package SpotifyAPI\Http\Controllers
 * @author Reard Gjoni <gjoni-r@hotmail.com>
 *
 * Responsible for
 *      (1) generating a request URL where the user will be prompted to give access,
 *      (2) requesting access/refresh tokens &
 *      (3) refreshing the expired access token in order to stay logged in and not repeat the authorization process
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
     * @var array $cookieConfig
     */
    private array $cookieConfig;

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

        $this->cookieConfig = [
            "data" => [],
            "path" => "/",
            "secure" => true,
            "httpOnly" => true,
            "sameSite" => "None",
            "domain" => "spotify-auth.com",
            "expires" => 3600
        ];

        $this->response = $this->client->getResponse();
        $this->headers = $this->client->getHeaders();
        $this->configs = $this->client->getConfigs();
    }

    /**
     * Builds the URL the user will be redirected to give authorization
     *
     * @return string
     */
    public function buildAuthorizationUrl(): string {
        try {
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
     * Using an authentication code from the client(as a header), will request an access token.
     *
     * If successful, returns:
     *  (1) access token
     *  (2) refresh token
     *  (3) expiry timestamp for the access token
     *
     * This method sets the tokens as httpOnly cookies with different expiry times(refresh token should have a longer lifespan)
     *
     * @return string
     */
    public function requestAccessToken(): string {
        try {
            $request = $this->client->post("/api/token", [
                "headers" => [
                    "Authorization" => $this->configs["headers"]["authorization_access"],
                    "Content-Type" => $this->configs["headers"]["content_type_urlencoded"]
                ],
                "form_params" => [
                    "grant_type" => $this->configs["grant_type"],
                    "code" => $this->headers["Auth-Code"],
                    "redirect_uri" => $this->configs["redirect_uri"],
                ],
            ]);

            $body = $request->getBody();

            $body = json_decode($body, true);
            $timestamp = time() + $body["expires_in"];

            $body["expires_in"] = $timestamp;

            $cookie = new Cookie($this->cookieConfig);
            $cookie->access_token = $body["access_token"] ?? '';

            # Use the default expiry time
            unset($this->cookieConfig["expires"]);
            $cookie_2 = new Cookie($this->cookieConfig);
            $cookie_2->refresh_token = $body["refresh_token"] ?? '';

            return $this->response->json([
                "body" => $body,
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
     * This method requests a new access token based on a refresh token which should be sent as a httpOnly cookie
     *
     * Will:
     *  (1) Delete the current access token
     *  (2) Set the new one(plus its expiry timestamp)
     *  (3) If a new refresh token was sent, delete and reset it
     *
     * @return string
     */
    public function refreshAccessToken(): string {
        $refreshToken = $_COOKIE["refresh_token"] ?? '';

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

            $cookie = new Cookie($this->cookieConfig);

            # Delete the current access_token
            $cookie->access_token = '';

            # Set the new one
            $cookie->access_token = $body["access_token"] ?? '';

            # Unset and reset the refresh token if a new one was sent
            if (isset($body["refresh_token"])) {
                unset($this->cookieConfig["expires"]);

                $cookie_2 = new Cookie($this->cookieConfig);
                $cookie_2->refresh_token = '';
                $cookie_2->refresh_token = $body["refresh_token"];
            }

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
