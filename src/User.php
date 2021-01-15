<?php


namespace Gjoni\SpotifyWebApiSdk;

use GuzzleHttp\Exception\GuzzleException;
use Gjoni\SpotifyWebApiSdk\Http\Response;
use Gjoni\SpotifyWebApiSdk\Http\Client;
use Gjoni\SpotifyWebApiSdk\Config\SecretsCollection;

/**
 * Contains basic user resource requests(profile, playlists et cetera)
 *
 * @package Gjoni\SpotifyWebApiSdk
 * @author Reard Gjoni <gjoni-r@hotmail.com>
 */
class User
{
    /**
     * @var Client $client Custom client object
     */
    private Client $client;

    /**
     * @var Response Response object
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
     * UserController constructor.
     *
     * Initializes the client object, response, headers and client parameters
     */
    public function __construct()
    {
        $this->client = new Client([
            "base_uri" => SecretsCollection::$apiUri,
            "timeout" => 1,
            "allow_redirects" => ["track_redirects" => true]
        ]);

        $this->response = $this->client->getResponse();
        $this->headers = $this->client->getHeaders();
        $this->parameters = $this->client->getParameters();
    }

    /**
     * Gets the user profile
     *
     * @throws GuzzleException
     * @return string
     */
    public function getProfile(): string
    {
        return $this->client->fetch( "GET", "/v1/me");
    }

    /**
     * Gets all the user playlists
     *
     * @throws GuzzleException
     * @return string
     */
    public function getPlaylists(): string {
        return $this->client->fetch("GET", "/v1/me/playlists", [
            "query" => $this->parameters["query"]
        ]);
    }
}
