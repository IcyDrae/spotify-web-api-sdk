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
 * @link https://developer.spotify.com/documentation/web-api/reference/#category-artists
 */
class Albums
{
    /**
     * @var Client $client Custom client object
     */
    private Client $client;

    /**
     * Albums constructor.
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
        return $this->client->delegate("GET", SdkConstants::ALBUMS, $options);
    }

}
