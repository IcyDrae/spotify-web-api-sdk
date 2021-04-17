<?php


namespace Gjoni\SpotifyWebApiSdk;

use Gjoni\SpotifyWebApiSdk\Http\Client;
use Gjoni\SpotifyWebApiSdk\Interfaces\SdkInterface;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Responsible for communicating with the Episodes API.
 *
 * @package Gjoni\SpotifyWebApiSdk
 * @author Reard Gjoni <gjoni-r@hotmail.com>
 * @link https://developer.spotify.com/documentation/web-api/reference/#category-episodes
 */
class Episodes extends Client
{
    /**
     * Episodes constructor.
     *
     * Initializes the client object, response, headers and client parameters.
     *
     * @param SdkInterface $sdk
     */
    public function __construct(SdkInterface $sdk)
    {
        parent::__construct($sdk);
    }

    /**
     * Get Spotify catalog information for several episodes based on their Spotify IDs.
     *
     * Header:
     * - required
     *      - Authorization(string): A valid user access token or your client credentials.
     *
     * Query parameter:
     * - required
     *      - ids(string): A comma-separated list of the Spotify IDs for the episodes. Maximum: 50 IDs.
     *
     * - optional
     *      - market(string): An ISO 3166-1 alpha-2 country code. If a country code is specified, only shows and episodes that are available in that market will be returned. If a valid user access token is specified in the request header, the country associated with the user account will take priority over this parameter. Note: If neither market or user country are provided, the content is considered unavailable for the client. Users can view the country that is associated with their account in the account settings.
     *
     * Response:
     *
     * On success, the HTTP status code in the response header is 200 OK and the response body contains an object whose key is episodes and whose value is an array of episode objects in JSON format.
     * Objects are returned in the order requested. If an object is not found, a null value is returned in the appropriate position. Duplicate ids in the query will result in duplicate objects in the response. If an episode is unavailable in the given market, a null value is returned.
     * On error, the header status code is an error code and the response body contains an error object.
     *
     * @param array $options (optional) Request parameters
     * @throws GuzzleException
     * @return string
     */
    public function getMultiple(array $options = []): string
    {
        return $this->delegate("GET", SdkConstants::EPISODES, $options);
    }

    /**
     * Get Spotify catalog information for a single episode identified by its unique Spotify ID.
     *
     * Header:
     * - required
     *      - Authorization(string): A valid user access token or your client credentials.
     *
     * Path parameter:
     *  - required
     *      - {id}(string): The Spotify ID for the episode.
     *
     * Query parameter:
     * - optional
     *      - market(string): An ISO 3166-1 alpha-2 country code. If a country code is specified, only shows and episodes that are available in that market will be returned. If a valid user access token is specified in the request header, the country associated with the user account will take priority over this parameter. Note: If neither market or user country are provided, the content is considered unavailable for the client. Users can view the country that is associated with their account in the account settings.
     *
     * Response:
     *
     * On success, the HTTP status code in the response header is 200 OK and the response body contains an episode object in JSON format.
     * On error, the header status code is an error code and the response body contains an error object.
     * If an episode is unavailable in the given market the HTTP status code in the response header is 404 NOT FOUND.
     *
     * @param string $id The episode id
     * @param array $options (optional) Request parameters
     * @throws GuzzleException
     * @return string
     */
    public function getSingle(string $id, array $options = []): string
    {
        return $this->delegate("GET", SdkConstants::EPISODES . "/$id", $options);
    }

}
