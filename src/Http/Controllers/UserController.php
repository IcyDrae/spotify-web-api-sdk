<?php


namespace SpotifyAPI\Http\Controllers;

use GuzzleHttp\Exception\GuzzleException;
use SpotifyAPI\Http\Response;
use SpotifyAPI\Http\Client;
use Config\SecretsCollection;

/**
 * Class UserController
 * @package SpotifyAPI\Http\Controllers
 * @author Reard Gjoni <gjoni-r@hotmail.com>
 *
 * Contains basic user resource requests(profile, playlists et cetera)
 */
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

    /**
     * UserController constructor.
     *
     * Initializes the client object, response, headers and client config
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
        $this->configs = $this->client->getConfigs();
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
            "query" => $this->configs["query"]
        ]);
    }
}
