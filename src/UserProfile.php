<?php


namespace Gjoni\SpotifyWebApiSdk;

use Gjoni\SpotifyWebApiSdk\Interfaces\SdkInterface;
use Gjoni\SpotifyWebApiSdk\Http\Response;
use Gjoni\SpotifyWebApiSdk\Http\Client;
use Gjoni\SpotifyWebApiSdk\Config\SecretsCollection;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Contains basic user resource requests(profile, playlists et cetera)
 *
 * @package Gjoni\SpotifyWebApiSdk
 * @author Reard Gjoni <gjoni-r@hotmail.com>
 */
class UserProfile
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
     * @var SdkInterface $sdk Sdk object
     */
    private SdkInterface $sdk;

    /**
     * UserController constructor.
     *
     * Initializes the client object, response, headers and client parameters.
     *
     * @param SdkInterface $sdk
     */
    public function __construct(SdkInterface $sdk)
    {
        $this->client = new Client($sdk , [
            "base_uri" => SecretsCollection::$apiUri,
            "timeout" => 1,
            "allow_redirects" => ["track_redirects" => true]
        ]);

        $this->response = $this->client->getResponse();
        $this->headers = $this->client->getHeaders();
        $this->parameters = $this->client->getParameters();
    }

    /**
     * Fetches the current users' profile.
     *
     * @throws GuzzleException
     * @return string
     */
    public function me(): string
    {
        return $this->client->fetch( "GET", "/v1/me");
    }

    /**
     * Fetches a users' public profile.
     *
     * @param string $id The user id
     * @throws GuzzleException
     * @return string
     */
    public function getUserProfile(string $id): string
    {
        return $this->client->fetch( "GET", "/v1/users/$id");
    }
}
