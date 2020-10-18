<?php

namespace SpotifyAPI\Controllers;

use SpotifyAPI\Secrets\Secrets;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7;

/**
 * Class Authentication
 * Authenticates the user and gives access and refresh tokens
 */
class Authentication {
    /**
     * @var Client $client
     */
    private $client;

    /**
     * @var array $tokens
     */
    private $tokens = [
        "access_token",
        "refresh_token"
    ];

    /**
     * Requests authorization code, gets it and calls getAccessToken() with it
     */
    public function requestAuthCode() {
        $secrets = new Secrets;
        $this->client = new Client($secrets->getBaseUri(), 1);

        try {
            $this->client->makeRequest("GET", "/authorize", $this->client->getAuthParams());
            $this->getAccessToken();
        } catch (RequestException $exception) {
            echo Psr7\str($exception->getRequest());
            if ($exception->hasResponse()) {
                echo Psr7\str($exception->getResponse());
            }
        }
    }

    /**
     * Calls getUserProfile() with the access token
     */
    public function getAccessToken() {
        $currentUrl = $_SERVER["REQUEST_URI"];

        if (preg_match("/\/callback\?code\=([A-z0-9-]+)/", $currentUrl)) { // Check if we got redirected at /callback with the auth code
            $secrets = new Secrets;

            $this->client = new Client($secrets->getBaseUri(), 1);

            try {
                $request = $this->client->makeRequest("POST", "/api/token", $this->client->getAccessParams());

                /*setrawcookie("access_token", json_decode($request->getBody()->getContents(), true)["access_token"]);*/
                $this->tokens["access_token"] = json_decode($request->getBody()->getContents(), true)["access_token"];

                $this->getUserProfile();
            } catch (RequestException $exception) {
                echo Psr7\str($exception->getRequest());
                if ($exception->hasResponse()) {
                    echo Psr7\str($exception->getResponse());
                }
            }
        }
    }

    /**
     * Fetches us the user profile after authenticating
     */
    public function getUserProfile()
    {
        try {
            $secrets = new Secrets;
            $this->client = new Client($secrets->getApiUri(), 1);
            $request = $this->client->makeRequest("GET", "/v1/me", [
                "headers" => [
                    "Accept" => "application/json",
                    "Content-Type" => "application/json",
                    "Authorization" => sprintf("Bearer %s",  $this->tokens["access_token"]),
                ]
            ]);
            var_dump(json_decode($request->getBody()->getContents(), true));
        } catch (RequestException $exception) {
            echo Psr7\str($exception->getRequest());
            if ($exception->hasResponse()) {
                echo Psr7\str($exception->getResponse());
            }
        }
    }

    public function getUserPlaylists() {
        try {
            $secrets = new Secrets;
            $this->client = new Client($secrets->getApiUri(), 1);

            $request = $this->client->makeRequest("GET", "/v1/me/playlists", $this->client->getParams());
        } catch (RequestException $exception) {
            echo Psr7\str($exception->getRequest());
            if ($exception->hasResponse()) {
                echo Psr7\str($exception->getResponse());
            }
        }
    }
}