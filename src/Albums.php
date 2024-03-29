<?php


namespace Gjoni\SpotifyWebApiSdk;

use Gjoni\SpotifyWebApiSdk\Http\Client;
use Gjoni\SpotifyWebApiSdk\Interfaces\SdkInterface;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Responsible for communicating with the Albums API.
 *
 * @package Gjoni\SpotifyWebApiSdk
 * @author Reard Gjoni <gjoni-r@hotmail.com>
 * @link https://developer.spotify.com/documentation/web-api/reference/#category-albums
 */
class Albums extends Client
{
    /**
     * Albums constructor.
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
     * Get Spotify catalog information for multiple albums identified by their Spotify IDs.
     *
     * Header:
     * - required
     *      - Authorization(string): A valid user access token or your client credentials.
     *
     * Query parameter:
     * - required
     *      - ids(string): A comma-separated list of the Spotify IDs for the artists. Maximum: 50 IDs.
     *
     * - optional
     *      - market(string): An ISO 3166-1 alpha-2 country code or the string from_token. Provide this parameter if you want to apply Track Relinking.
     *
     * Response:
     *
     * On success, the HTTP status code in the response header is 200 OK and the response body contains an object whose key is "albums" and whose value is an array of album objects in JSON format.
     * Objects are returned in the order requested. If an object is not found, a null value is returned in the appropriate position. Duplicate ids in the query will result in duplicate objects in the response.
     * On error, the header status code is an error code and the response body contains an error object.
     *
     * @param array $options (optional) Request parameters
     * @throws GuzzleException
     * @return string
     */
    public function getMultiple(array $options = []): string
    {
        return $this->delegate("GET", SdkConstants::ALBUMS, $options);
    }

    /**
     * Get Spotify catalog information for a single album.
     *
     * Header:
     * - required
     *      - Authorization(string): A valid user access token or your client credentials.
     *
     * Path parameter:
     *  - required:
     *      - {id}(string): The Spotify ID of the album.
     *
     * Query parameter:
     * - optional
     *      - market(string): The market you’d like to request. Synonym for country.
     *
     * Response:
     *
     * On success, the HTTP status code in the response header is 200 OK and the response body contains an album object in JSON format.
     * On error, the header status code is an error code and the response body contains an error object.
     *
     * @param string $id The album id
     * @param array $options (optional) Request parameters
     * @throws GuzzleException
     * @return string
     */
    public function getSingle(string $id, array $options = []): string
    {
        return $this->delegate("GET", SdkConstants::ALBUMS . "/$id", $options);
    }

    /**
     * Get Spotify catalog information about an album’s tracks. Optional parameters can be used to limit the number of tracks returned.
     *
     * Header:
     * - required
     *      - Authorization(string): A valid user access token or your client credentials.
     *
     * Path parameter:
     *  - required:
     *      - {id}(string): The Spotify ID of the album.
     *
     * Query parameter:
     * - optional
     *      - market(string): An ISO 3166-1 alpha-2 country code or the string from_token. Provide this parameter if you want to apply Track Relinking.
     *      - limit(integer): The maximum number of tracks to return. Default: 20. Minimum: 1. Maximum: 50.
     *      - offset(integer): The index of the first track to return. Default: 0 (the first object). Use with limit to get the next set of tracks.
     *
     * Response:
     *
     * On success, the HTTP status code in the response header is 200 OK and the response body contains an album object in JSON format.
     * On error, the header status code is an error code and the response body contains an error object.
     *
     * @param string $id The album id
     * @param array $options (optional) Request parameters
     * @throws GuzzleException
     * @return string
     */
    public function getTracks(string $id, array $options = []): string
    {
        return $this->delegate("GET", SdkConstants::ALBUMS . "/$id/tracks", $options);
    }

}
