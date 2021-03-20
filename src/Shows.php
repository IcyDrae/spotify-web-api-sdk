<?php


namespace Gjoni\SpotifyWebApiSdk;

use Gjoni\SpotifyWebApiSdk\Http\Client;
use Gjoni\SpotifyWebApiSdk\Interfaces\SdkInterface;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Responsible for communicating with the Shows API.
 *
 * @package Gjoni\SpotifyWebApiSdk
 * @author Reard Gjoni <gjoni-r@hotmail.com>
 * @link https://developer.spotify.com/documentation/web-api/reference/#category-shows
 */
class Shows
{
    /**
     * @var Client $client Custom client object
     */
    private Client $client;

    /**
     * Shows constructor.
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
     * Get Spotify catalog information for several shows based on their Spotify IDs.
     *
     * Header:
     * - required
     *      - Authorization(string): A valid access token from the Spotify Accounts service: see the Web API Authorization Guide for details.
     *
     * Query parameter:
     * - required
     *      - ids(string): A comma-separated list of the Spotify IDs for the shows. Maximum: 50 IDs.
     * - optional
     *      - market(string): An ISO 3166-1 alpha-2 country code. If a country code is specified, only shows and episodes that are available in that market will be returned. If a valid user access token is specified in the request header, the country associated with the user account will take priority over this parameter. Note: If neither market or user country are provided, the content is considered unavailable for the client. Users can view the country that is associated with their account in the account settings.
     *
     * Response:
     *
     * On success, the HTTP status code in the response header is 200 OK and the response body contains an object whose key is shows and whose value is an array of simple show object in JSON format.
     * Objects are returned in the order requested. If an object is not found, a null value is returned in the appropriate position. If a show is unavailable in the given market, a null value is returned.
     * Duplicate ids in the query will result in duplicate objects in the response.
     * On error, the header status code is an error code and the response body contains an error object.
     *
     * @param array $options (optional) Request parameters
     * @throws GuzzleException
     * @return string
     */
    public function getMultiple(array $options = []): string
    {
        return $this->client->delegate("GET", SdkConstants::SHOWS, $options);
    }

    /**
     * Get Spotify catalog information for a single show identified by its unique Spotify ID.
     *
     * Header:
     * - required
     *      - Authorization(string): Get Spotify catalog information for a single show identified by its unique Spotify ID.
     *
     * Path parameter:
     * - required
     *      - {id}(string): The Spotify ID for the show.
     *
     * Query parameter:
     * - optional
     *      - market(string): An ISO 3166-1 alpha-2 country code. If a country code is specified, only shows and episodes that are available in that market will be returned. If a valid user access token is specified in the request header, the country associated with the user account will take priority over this parameter. Note: If neither market or user country are provided, the content is considered unavailable for the client. Users can view the country that is associated with their account in the account settings.
     *
     * Response:
     *
     * On success, the HTTP status code in the response header is 200 OK and the response body contains a show object in JSON format.
     * On error, the header status code is an error code and the response body contains an error object.
     * If a show is unavailable in the given market the HTTP status code in the response header is 404 NOT FOUND.
     *
     * @param string $id The show id
     * @param array $options (optional) Request parameters
     * @throws GuzzleException
     * @return string
     */
    public function getSingle(string $id, array $options = []): string
    {
        return $this->client->delegate("GET", SdkConstants::SHOWS . "/$id", $options);
    }

    /**
     * Get Spotify catalog information about an show’s episodes. Optional parameters can be used to limit the number of episodes returned.
     *
     * Header:
     * - required
     *      - Authorization(string): Get Spotify catalog information for a single show identified by its unique Spotify ID.
     *
     * Path parameter:
     * - required
     *      - {id}(string): The Spotify ID for the show.
     *
     * Query parameter:
     * - optional
     *      - market(string): An ISO 3166-1 alpha-2 country code. If a country code is specified, only shows and episodes that are available in that market will be returned. If a valid user access token is specified in the request header, the country associated with the user account will take priority over this parameter. Note: If neither market or user country are provided, the content is considered unavailable for the client. Users can view the country that is associated with their account in the account settings.
     *      - limit(integer): The maximum number of episodes to return. Default: 20. Minimum: 1. Maximum: 50.
     *      - offset(integer): The index of the first episode to return. Default: 0 (the first object). Use with limit to get the next set of episodes.
     *
     * Response:
     *
     * On success, the HTTP status code in the response header is 200 OK and the response body contains an array of simplified episode objects (wrapped in a paging object) in JSON format.
     * On error, the header status code is an error code and the response body contains an error object.
     * If a show is unavailable in the given market the HTTP status code in the response header is 404 NOT FOUND.
     * Unavailable episodes are filtered out.
     *
     * @param string $id The show id
     * @param array $options (optional) Request parameters
     * @throws GuzzleException
     * @return string
     */
    public function getEpisodes(string $id, array $options = []): string
    {
        return $this->client->delegate("GET", SdkConstants::SHOWS . "/$id/episodes", $options);
    }

}
