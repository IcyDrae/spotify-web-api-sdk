<?php


namespace Gjoni\SpotifyWebApiSdk;

use Gjoni\SpotifyWebApiSdk\Http\Client;
use Gjoni\SpotifyWebApiSdk\Interfaces\SdkInterface;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Responsible for communicating with the Artists API.
 *
 * @package Gjoni\SpotifyWebApiSdk
 * @author Reard Gjoni <gjoni-r@hotmail.com>
 * @link https://developer.spotify.com/documentation/web-api/reference/#category-artists
 */
class Artists
{
    /**
     * @var Client $client Custom client object
     */
    private Client $client;

    /**
     * Playlists constructor.
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
     * - required
     *      -  ids(string): A comma-separated list of the Spotify IDs for the artists. Maximum: 50 IDs.
     *
     * Response:
     *
     * On success, the HTTP status code in the response header is 200 OK and the response body contains an object whose key is "artists" and whose value is an array of artist objects in JSON format.
     * Objects are returned in the order requested. If an object is not found, a null value is returned in the appropriate position.
     * Duplicate ids in the query will result in duplicate objects in the response.
     * On error, the header status code is an error code and the response body contains an error object.
     *
     * @param array $ids An array containing indexed artist id's. Ex: ["0oSGxfWSnnOXhD2fKuz2Gy", "4BgFW9XAMsJMkMZQJ6lGD9"]
     * @param array $options (optional) Request parameters
     * @throws GuzzleException
     * @return string
     */
    public function getMultiple(array $ids, array $options = []): string
    {
        $ids = implode(",", $ids);

        return $this->client->delegate("GET", SdkConstants::ARTISTS . "?ids=$ids", $options);
    }

    /**
     * Get Spotify catalog information for a single artist identified by their unique Spotify ID.
     *
     * Header:
     * - required
     *      - Authorization(string): A valid user access token or your client credentials.
     *
     * Path parameter:
     * - required
     *      - {artist_id}(string): The Spotify ID of the artist.
     *
     * Response:
     *
     * On success, the HTTP status code in the response header is 200 OK and the response body contains an artist object in JSON format.
     * On error, the header status code is an error code and the response body contains an error object.
     *
     * @param string $id
     * @param array $options
     * @throws GuzzleException
     * @return string
     */
    public function getSingle(string $id, array $options = []): string
    {
        return $this->client->delegate("GET", SdkConstants::ARTISTS . "/$id");
    }

}
