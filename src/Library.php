<?php


namespace Gjoni\SpotifyWebApiSdk;

use Gjoni\SpotifyWebApiSdk\Http\Client;
use Gjoni\SpotifyWebApiSdk\Interfaces\SdkInterface;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Responsible for communicating with the Library API.
 *
 * @package Gjoni\SpotifyWebApiSdk
 * @author Reard Gjoni <gjoni-r@hotmail.com>
 * @link https://developer.spotify.com/documentation/web-api/reference/#category-library
 */
class Library
{
    /**
     * @var Client $client Custom client object
     */
    private Client $client;

    /**
     * Library constructor.
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
     * Get a list of the albums saved in the current Spotify user’s ‘Your Music’ library.
     *
     * Header:
     * - required
     *      - Authorization(string): A valid access token from the Spotify Accounts service: see the Web API Authorization Guide for details. The user-library-read scope must have been authorized by the user.
     *
     * Query parameter:
     * - optional
     *      - limit(integer): The maximum number of objects to return. Default: 20. Minimum: 1. Maximum: 50.
     *      - offset(integer): The index of the first object to return. Default: 0 (i.e., the first object). Use with limit to get the next set of objects.
     *      - market(string): An ISO 3166-1 alpha-2 country code or the string from_token. Provide this parameter if you want to apply Track Relinking.
     *
     * Response:
     *
     * On success, the HTTP status code in the response header is 200 OK and the response body contains an array of saved album objects (wrapped in a paging object) in JSON format. Each album object is accompanied by a timestamp (added_at) to show when it was added.
     * There is also an etag in the header that can be used in future conditional requests.
     * On error, the header status code is an error code and the response body contains an error object.
     *
     * @param array $options (optional) Request parameters
     * @throws GuzzleException
     * @return string
     */
    public function getSavedAlbums(array $options = []): string
    {
        return $this->client->delegate("GET", SdkConstants::LIBRARY["ALBUMS"], $options);
    }

}
