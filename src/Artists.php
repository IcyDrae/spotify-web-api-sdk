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
class Artists extends Client
{
    /**
     * Artists constructor.
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
     * @param array $options (optional) Request parameters
     * @throws GuzzleException
     * @return string
     */
    public function getMultiple(array $options = []): string
    {
        return $this->delegate("GET", SdkConstants::ARTISTS, $options);
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
     * @param string $id The artist id
     * @param array $options (optional) Request parameters
     * @throws GuzzleException
     * @return string
     */
    public function getSingle(string $id, array $options = []): string
    {
        return $this->delegate("GET", SdkConstants::ARTISTS . "/$id", $options);
    }

    /**
     * Get Spotify catalog information about an artist’s top tracks by country.
     *
     * Header:
     * - required
     *      - Authorization(string): A valid user access token or your client credentials.
     *
     * Path parameter:
     *  - required
     *      - {id}(string): The Spotify ID for the artist
     *
     * Query parameter:
     *  - required
     *      - market(string): An ISO 3166-1 alpha-2 country code or the string from_token. Synonym for country.
     *
     * If no country code is passed, the string 'from_token' will automatically be used.
     *
     * Response:
     *
     * On success, the HTTP status code in the response header is 200 OK and the response body contains an object whose key is "tracks" and whose value is an array of up to 10 track objects in JSON format.
     * On error, the header status code is an error code and the response body contains an error object.
     *
     * @param string $id The artist id
     * @param array $options (optional) Request parameters
     * @throws GuzzleException
     * @return string
     */
    public function getTopTracks(string $id, array $options = []): string
    {
        return $this->delegate("GET", SdkConstants::ARTISTS . "/$id/top-tracks", $options);
    }

    /**
     * Get Spotify catalog information about artists similar to a given artist. Similarity is based on analysis of the Spotify community’s listening history.
     *
     * Header:
     *  - required
     *      - Authorization(string): A valid user access token or your client credentials.
     *
     * Path parameter:
     *  - required
     *      - {id}(string): The Spotify ID for the artist
     *
     * Response:
     *
     * On success, the HTTP status code in the response header is 200 OK and the response body contains an object whose key is "artists" and whose value is an array of up to 20 artist objects in JSON format. On error, the header status code is an error code and the response body contains an error object.
     *
     * @param string $id The artist id
     * @param array $options (optional) Request parameters
     * @throws GuzzleException
     * @return string
     */
    public function getRelatedArtists(string $id, array $options = []): string
    {
        return $this->delegate("GET", SdkConstants::ARTISTS . "/$id/related-artists", $options);
    }

    /**
     * Get Spotify catalog information about an artist’s albums.
     *
     * Header:
     *  - required
     *      - Authorization(string): A valid user access token or your client credentials.
     *
     * Path parameter:
     *  - required
     *      - {id}(string): The Spotify ID for the artist.
     *
     * Query parameter:
     *  - optional
     *      - include_groups(string): A comma-separated list of keywords that will be used to filter the response. If not supplied, all album types will be returned. Valid values are: album, single, appears_on, compilation. For example: include_groups=album,single.
     *      - market(string): Synonym for country. An ISO 3166-1 alpha-2 country code or the string from_token. Supply this parameter to limit the response to one particular geographical market. For example, for albums available in Sweden: market=SE. If not given, results will be returned for all markets and you are likely to get duplicate results per album, one for each market in which the album is available!
     *      - limit(integer): The number of album objects to return. Default: 20. Minimum: 1. Maximum: 50. For example: limit=2
     *      - offset(integer): The index of the first album to return. Default: 0 (i.e., the first album). Use with limit to get the next set of albums.
     *
     * Response:
     *
     * On success, the HTTP status code in the response header is 200 OK and the response body contains an array of simplified album objects (wrapped in a paging object) in JSON format.
     * On error, the header status code is an error code and the response body contains an error object.
     *
     * @param string $id The artist id
     * @param array $options (optional) Request parameters
     * @throws GuzzleException
     * @return string
     */
    public function getAlbums(string $id, array $options = []): string
    {
        return $this->delegate("GET", SdkConstants::ARTISTS . "/$id/albums", $options);
    }

}
