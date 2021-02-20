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
     * Get a list of new album releases featured in Spotify (shown, for example, on a Spotify player’s “Browse” tab).
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

    /**
     * Get a list of Spotify featured playlists (shown, for example, on a Spotify player’s ‘Browse’ tab).
     *
     * Header:
     * - required
     *      - Authorization(string): A valid user access token or your client credentials.
     *
     * Query parameter:
     * - optional
     *      - country(string): A country: an ISO 3166-1 alpha-2 country code. Provide this parameter if you want the list of returned items to be relevant to a particular country. If omitted, the returned items will be relevant to all countries.
     *      - locale(string): The desired language, consisting of a lowercase ISO 639-1 language code and an uppercase ISO 3166-1 alpha-2 country code, joined by an underscore. For example: es_MX, meaning “Spanish (Mexico)”. Provide this parameter if you want the results returned in a particular language (where available). Note that, if locale is not supplied, or if the specified language is not available, all strings will be returned in the Spotify default language (American English). The locale parameter, combined with the country parameter, may give odd results if not carefully matched. For example country=SE&locale=de_DE will return a list of categories relevant to Sweden but as German language strings.
     *      - timestamp(string): A timestamp in ISO 8601 format: yyyy-MM-ddTHH:mm:ss. Use this parameter to specify the user’s local time to get results tailored for that specific date and time in the day. If not provided, the response defaults to the current UTC time. Example: “2014-10-23T09:00:00” for a user whose local time is 9AM. If there were no featured playlists (or there is no data) at the specified time, the response will revert to the current UTC time.
     *      - limit(integer): The maximum number of items to return. Default: 20. Minimum: 1. Maximum: 50.
     *      - offset(integer): The index of the first item to return. Default: 0 (the first object). Use with limit to get the next set of items.
     *
     * Response:
     *
     * On success, the HTTP status code in the response header is 200 OK and the response body contains a message and a playlists object. The playlists object contains an array of simplified playlist objects (wrapped in a paging object) in JSON format.
     * On error, the header status code is an error code and the response body contains an error object.
     * Once you have retrieved the list of playlist objects, you can use Get a Playlist and Get a Playlist’s Tracks to drill down further.
     *
     * @param array $options (optional) Request parameters
     * @throws GuzzleException
     * @return string
     */
    public function getFeaturedPlaylists(array $options = []): string
    {
        return $this->client->delegate("GET", SdkConstants::BROWSE . "/featured-playlists", $options);
    }

    /**
     * Get a list of categories used to tag items in Spotify (on, for example, the Spotify player’s “Browse” tab).
     *
     * Header:
     * - required
     *      - Authorization(string): A valid user access token or your client credentials.
     *
     * Query parameter:
     * - optional
     *      - country(string): A country: an ISO 3166-1 alpha-2 country code. Provide this parameter if you want the list of returned items to be relevant to a particular country. If omitted, the returned items will be relevant to all countries.
     *      - locale(string): The desired language, consisting of a lowercase ISO 639-1 language code and an uppercase ISO 3166-1 alpha-2 country code, joined by an underscore. For example: es_MX, meaning “Spanish (Mexico)”. Provide this parameter if you want the results returned in a particular language (where available). Note that, if locale is not supplied, or if the specified language is not available, all strings will be returned in the Spotify default language (American English). The locale parameter, combined with the country parameter, may give odd results if not carefully matched. For example country=SE&locale=de_DE will return a list of categories relevant to Sweden but as German language strings.
     *      - limit(integer): The maximum number of items to return. Default: 20. Minimum: 1. Maximum: 50.
     *      - offset(integer): The index of the first item to return. Default: 0 (the first object). Use with limit to get the next set of items.
     *
     * Response:
     *
     * On success, the HTTP status code in the response header is 200 OK and the response body contains an object with a categories field, with an array of category objects (wrapped in a paging object) in JSON format.
     * On error, the header status code is an error code and the response body contains an error object.
     * Once you have retrieved the list, you can use Get a Category to drill down further.
     *
     * @param array $options (optional) Request parameters
     * @throws GuzzleException
     * @return string
     */
    public function getCategories(array $options = []): string
    {
        return $this->client->delegate("GET", SdkConstants::BROWSE . "/categories", $options);
    }

    /**
     * Get a list of categories used to tag items in Spotify (on, for example, the Spotify player’s “Browse” tab).
     *
     * Header:
     * - required
     *      - Authorization(string): A valid user access token or your client credentials.
     *
     * Query parameter:
     * - optional
     *      - country(string): A country: an ISO 3166-1 alpha-2 country code. Provide this parameter if you want the list of returned items to be relevant to a particular country. If omitted, the returned items will be relevant to all countries.
     *      - locale(string): The desired language, consisting of a lowercase ISO 639-1 language code and an uppercase ISO 3166-1 alpha-2 country code, joined by an underscore. For example: es_MX, meaning “Spanish (Mexico)”. Provide this parameter if you want the results returned in a particular language (where available). Note that, if locale is not supplied, or if the specified language is not available, all strings will be returned in the Spotify default language (American English). The locale parameter, combined with the country parameter, may give odd results if not carefully matched. For example country=SE&locale=de_DE will return a list of categories relevant to Sweden but as German language strings.
     *      - limit(integer): The maximum number of items to return. Default: 20. Minimum: 1. Maximum: 50.
     *      - offset(integer): The index of the first item to return. Default: 0 (the first object). Use with limit to get the next set of items.
     *
     * Response:
     *
     * On success, the HTTP status code in the response header is 200 OK and the response body contains an object with a categories field, with an array of category objects (wrapped in a paging object) in JSON format.
     * On error, the header status code is an error code and the response body contains an error object.
     * Once you have retrieved the list, you can use Get a Category to drill down further.
     *
     * @param string $id The category id
     * @param array $options (optional) Request parameters
     * @throws GuzzleException
     * @return string
     */
    public function getCategory(string $id, array $options = []): string
    {
        return $this->client->delegate("GET", SdkConstants::BROWSE . "/categories/$id", $options);
    }

}
