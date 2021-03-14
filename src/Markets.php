<?php


namespace Gjoni\SpotifyWebApiSdk;

use Gjoni\SpotifyWebApiSdk\Http\Client;
use Gjoni\SpotifyWebApiSdk\Interfaces\SdkInterface;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Responsible for communicating with the Markets API.
 *
 * @package Gjoni\SpotifyWebApiSdk
 * @author Reard Gjoni <gjoni-r@hotmail.com>
 * @link https://developer.spotify.com/documentation/web-api/reference/#category-markets
 */
class Markets
{
    /**
     * @var Client $client Custom client object
     */
    private Client $client;

    /**
     * Markets constructor.
     *
     * Initializes the client object, response, headers and client parameters.
     *
     * @param SdkInterface $sdk
     */
    public function __construct(SdkInterface $sdk)
    {
        $this->client = new Client($sdk, [
            "base_uri" => SdkConstants::API_URL,
            "timeout" => 2,
            "allow_redirects" => ["track_redirects" => true]
        ]);
    }

    /**
     * Get the list of markets where Spotify is available.
     *
     * Header:
     * - required
     *      - Authorization(string): A valid user access token or your client credentials.
     *
     * Response:
     *
     * On success, the HTTP status code in the response header is 200 OK and the response body contains a list of the countries in which Spotify is available, identified by their ISO 3166-1 alpha-2 country code with additional country codes for special territories.
     * On error, the header status code is an error code and the response body contains an error object.
     *
     * @param array $options (optional) Request parameters
     * @throws GuzzleException
     * @return string
     */
    public function getAvailable(array $options = []): string
    {
        return $this->client->delegate("GET", SdkConstants::MARKETS, $options);
    }

}
