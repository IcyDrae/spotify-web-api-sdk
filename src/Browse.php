<?php


namespace Gjoni\SpotifyWebApiSdk;

use Gjoni\SpotifyWebApiSdk\Http\Client;
use Gjoni\SpotifyWebApiSdk\Interfaces\SdkInterface;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Responsible for communicating with the Browse API.
 *
 * @package Gjoni\SpotifyWebApiSdk
 * @author Reard Gjoni <gjoni-r@hotmail.com>
 * @link https://developer.spotify.com/documentation/web-api/reference/#category-browse
 */
class Browse
{
    /**
     * @var Client $client Custom client object
     */
    private Client $client;

    /**
     * Browse constructor.
     *
     * Initializes the client object, response, headers and client parameters.
     *
     * @param SdkInterface $sdk
     */
    public function __construct(SdkInterface $sdk)
    {
        $this->client = new Client($sdk, [
            "base_uri" => SdkConstants::API_URL,
            "timeout" => 1,
            "allow_redirects" => ["track_redirects" => true]
        ]);
    }

    /**
     * Get Spotify catalog information for several artists based on their Spotify IDs.
     *
     * Header:
     * - required
     *      - Authorization(string): A valid user access token or your client credentials.
     *
     * Query parameter:
     * - optional
     *      - country(string): A country: an ISO 3166-1 alpha-2 country code. Provide this parameter if you want the list of returned items to be relevant to a particular country. If omitted, the returned items will be relevant to all countries.
     *      - limit(integer): The maximum number of items to return. Default: 20. Minimum: 1. Maximum: 50.
     *      - offset(integer): The index of the first item to return. Default: 0 (the first object). Use with limit to get the next set of items.
     *
     * Response:
     *
     * On success, the HTTP status code in the response header is 200 OK and the response body contains a message and an albums object. The albums object contains an array of simplified album objects (wrapped in a paging object) in JSON format. On error, the header status code is an error code and the response body contains an error object.
     * Once you have retrieved the list, you can use Get an Album’s Tracks to drill down further.
     * The results are returned in an order reflected within the Spotify clients, and therefore may not be ordered by date.
     *
     * @param array $options (optional) Request parameters
     * @throws GuzzleException
     * @return string
     */
    public function getNewReleases(array $options = []): string
    {
        return $this->client->delegate("GET", SdkConstants::BROWSE . "/new-releases", $options);
    }

}
