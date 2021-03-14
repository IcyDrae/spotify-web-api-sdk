<?php


namespace Gjoni\SpotifyWebApiSdk;

use Gjoni\SpotifyWebApiSdk\Http\Client;
use Gjoni\SpotifyWebApiSdk\Interfaces\SdkInterface;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Responsible for communicating with the Personalization API.
 *
 * @package Gjoni\SpotifyWebApiSdk
 * @author Reard Gjoni <gjoni-r@hotmail.com>
 * @link https://developer.spotify.com/documentation/web-api/reference/#category-personalization
 */
class Personalization
{
    /**
     * @var Client $client Custom client object
     */
    private Client $client;

    /**
     * Personalization constructor.
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
     * Get the current user’s top artists or tracks based on calculated affinity.
     *
     * Header:
     * - required
     *      - Authorization(string): A valid access token from the Spotify Accounts service: see the Web API Authorization Guide for details. The access token must have been issued on behalf of the current user. Getting details of a user’s top artists and tracks requires authorization of the user-top-read scope. See Using Scopes.
     *
     * Path parameter:
     * - required
     *      - {type}(string): The type of entity to return. Valid values: artists or tracks
     *
     * Query parameters:
     * - optional
     *      - time_range(string): Over what time frame the affinities are computed. Valid values: long_term (calculated from several years of data and including all new data as it becomes available), medium_term (approximately last 6 months), short_term (approximately last 4 weeks). Default: medium_term
     *      - limit(integer): The number of entities to return. Default: 20. Minimum: 1. Maximum: 50. For example: limit=2
     *      - offset(integer): The index of the first entity to return. Default: 0 (i.e., the first track). Use with limit to get the next set of entities.
     *
     * Response:
     *
     * On success, the HTTP status code in the response header is 200 OK and the response body contains a paging object of Artists or Tracks.
     * On error, the header status code is an error code and the response body contains an error object.
     *
     * @param string $type The type of entity.
     * @param array $options (optional) Request parameters
     * @throws GuzzleException
     * @return string
     */
    public function getTop(string $type, array $options = []): string
    {
        return $this->client->delegate("GET", SdkConstants::ME . "/top/$type", $options);
    }

}
