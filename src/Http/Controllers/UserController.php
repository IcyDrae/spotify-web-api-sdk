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
     * @throws GuzzleException
     */
    public function getProfile()
    {
        $this->client->fetch( "GET", "/v1/me");
    }

    /**
     * @throws GuzzleException
     */
    public function getPlaylists() {
        $this->client->fetch("GET", "/v1/me/playlists", [
            "query" => $this->configs["query"]
        ]);
    }
}
