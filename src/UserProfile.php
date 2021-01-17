<?php


namespace Gjoni\SpotifyWebApiSdk;

use Gjoni\SpotifyWebApiSdk\Interfaces\SdkInterface;
use Gjoni\SpotifyWebApiSdk\Http\Response;
use Gjoni\SpotifyWebApiSdk\Http\Client;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Responsible for communicating with the Users Profile API.
 *
 * @package Gjoni\SpotifyWebApiSdk
 * @author Reard Gjoni <gjoni-r@hotmail.com>
 * @link https://developer.spotify.com/documentation/web-api/reference-beta/#category-users-profile
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
            "base_uri" => SdkConstants::API_URL,
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
     * Header:
     * - required
     *      - Authorization(string): A valid access token from the Spotify Accounts service.
     *
     * @throws GuzzleException
     * @return string
     */
    public function me(): string
    {
        return $this->client->fetch( "GET", SdkConstants::ME);
    }

    /**
     * Fetches a users' public profile.
     *
     * Header:
     * - required
     *      - Authorization(string): A valid access token from the Spotify Accounts service.
     *
     * Path Parameter:
     * - required
     *      - {user_id}(string): The user’s Spotify user ID.
     *
     * @param string $id The user id
     * @throws GuzzleException
     * @return string
     */
    public function getUserProfile(string $id): string
    {
        return $this->client->fetch( "GET", SdkConstants::USERS . "/$id");
    }
}
