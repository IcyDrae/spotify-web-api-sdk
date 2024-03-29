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
class Library extends Client
{
    /**
     * Library constructor.
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
        return $this->delegate("GET", SdkConstants::LIBRARY["ALBUMS"], $options);
    }

    /**
     * Save one or more albums to the current user’s ‘Your Music’ library.
     *
     * Header:
     * - required
     *      - Authorization(string): A valid access token from the Spotify Accounts service: see the Web API Authorization Guide for details. Modification of the current user’s “Your Music” collection requires authorization of the user-library-modify scope.
     * - optional
     *      - Content-Type(string): Required if the IDs are passed in the request body, otherwise ignored. The content type of the request body: application/json
     *
     * Query parameter:
     * - required
     *      - ids(string): A comma-separated list of the Spotify IDs. For example: ids=4iV5W9uYEdYUVa79Axb7Rh,1301WleyT98MSxVHPZCA6M. Maximum: 50 IDs.
     *
     * JSON body parameter:
     * - optional
     *      - ids(array[string]): A JSON array of the Spotify IDs. For example: ["4iV5W9uYEdYUVa79Axb7Rh", "1301WleyT98MSxVHPZCA6M"] A maximum of 50 items can be specified in one request. Note: if the ids parameter is present in the query string, any IDs listed here in the body will be ignored.
     *
     * Response:
     *
     * On success, the HTTP status code in the response header is 201 Created.
     * On error, the header status code is an error code and the response body contains an error object.
     * Trying to add an album when you do not have the user’s authorization returns error 403 Forbidden.
     *
     * @param array $options (optional) Request parameters
     * @throws GuzzleException
     * @return string
     */
    public function saveAlbums(array $options = []): string
    {
        return $this->delegate("PUT", SdkConstants::LIBRARY["ALBUMS"], $options);
    }

    /**
     * Remove one or more albums from the current user’s ‘Your Music’ library.
     *
     * Header:
     * - required
     *      - Authorization(string): A valid access token from the Spotify Accounts service: see the Web API Authorization Guide for details. Modification of the current user’s “Your Music” collection requires authorization of the user-library-modify scope.
     * - optional
     *      - Content-Type(string): Required if the IDs are passed in the request body, otherwise ignored. The content type of the request body: application/json
     *
     * Query parameter:
     * - required
     *      - ids(string): A comma-separated list of the Spotify IDs. For example: ids=4iV5W9uYEdYUVa79Axb7Rh,1301WleyT98MSxVHPZCA6M. Maximum: 50 IDs.
     *
     * JSON body parameter:
     * - optional
     *      - ids(array[string]): A JSON array of the Spotify IDs. For example: ["4iV5W9uYEdYUVa79Axb7Rh", "1301WleyT98MSxVHPZCA6M"] A maximum of 50 items can be specified in one request. Note: if the ids parameter is present in the query string, any IDs listed here in the body will be ignored.
     *
     * Response:
     *
     * On success, the HTTP status code in the response header is 200 Success.
     * On error, the header status code is an error code and the response body contains an error object.
     * Trying to remove an album when you do not have the user’s authorization returns error 403 Forbidden.
     *
     * @param array $options (optional) Request parameters
     * @throws GuzzleException
     * @return string
     */
    public function removeAlbums(array $options = []): string
    {
        return $this->delegate("DELETE", SdkConstants::LIBRARY["ALBUMS"], $options);
    }

    /**
     * Check if one or more albums is already saved in the current Spotify user’s ‘Your Music’ library.
     *
     * Header:
     * - required
     *      - Authorization(string): A valid access token from the Spotify Accounts service: see the Web API Authorization Guide for details. The user-library-read scope must have been authorized by the user.
     *
     * Query parameter:
     * - required
     *      - ids(string): A comma-separated list of the Spotify IDs for the albums. Maximum: 50 IDs.
     *
     * Response:
     *
     * On success, the HTTP status code in the response header is 200 OK and the response body contains a JSON array of true or false values, in the same order in which the ids were specified.
     * On error, the header status code is an error code and the response body contains an error object.
     *
     * @param array $options (optional) Request parameters
     * @throws GuzzleException
     * @return string
     */
    public function checkSavedAlbums(array $options = []): string
    {
        return $this->delegate("GET", SdkConstants::LIBRARY["ALBUMS"] . "/contains", $options);
    }

    /**
     * Get a list of the songs saved in the current Spotify user’s ‘Your Music’ library.
     *
     * Header:
     * - required
     *      - Authorization(string): A valid access token from the Spotify Accounts service: see the Web API Authorization Guide for details. The user-library-read scope must have been authorized by the user.
     *
     * Query parameter:
     * - optional
     *      - market(string): An ISO 3166-1 alpha-2 country code or the string from_token. Provide this parameter if you want to apply Track Relinking.
     *      - limit(integer): The maximum number of objects to return. Default: 20. Minimum: 1. Maximum: 50.
     *      - offset(integer): The index of the first object to return. Default: 0 (i.e., the first object). Use with limit to get the next set of objects.
     *
     * Response:
     *
     * On success, the HTTP status code in the response header is 200 OK and the response body contains an array of saved track objects (wrapped in a paging object) in JSON format.
     * On error, the header status code is an error code and the response body contains an error object.
     *
     * @param array $options (optional) Request parameters
     * @throws GuzzleException
     * @return string
     */
    public function getSavedTracks(array $options = []): string
    {
        return $this->delegate("GET", SdkConstants::LIBRARY["TRACKS"], $options);
    }

    /**
     * Save one or more albums to the current user’s ‘Your Music’ library.
     *
     * Header:
     * - required
     *      - Authorization(string): A valid access token from the Spotify Accounts service: see the Web API Authorization Guide for details. Modification of the current user’s “Your Music” collection requires authorization of the user-library-modify scope.
     * - optional
     *      - Content-Type(string): Required if the IDs are passed in the request body, otherwise ignored. The content type of the request body: application/json
     *
     * Query parameter:
     * - required
     *      - ids(string): A comma-separated list of the Spotify IDs. For example: ids=4iV5W9uYEdYUVa79Axb7Rh,1301WleyT98MSxVHPZCA6M. Maximum: 50 IDs.
     *
     * JSON body parameter:
     * - optional
     *      - ids(array[string]): A JSON array of the Spotify IDs. For example: ["4iV5W9uYEdYUVa79Axb7Rh", "1301WleyT98MSxVHPZCA6M"] A maximum of 50 items can be specified in one request. Note: if the ids parameter is present in the query string, any IDs listed here in the body will be ignored.
     *
     * Response:
     *
     * On success, the HTTP status code in the response header is 200 OK.
     * On error, the header status code is an error code and the response body contains an error object.
     * Trying to add a track when you do not have the user’s authorization, or when you have over 10.000 tracks in Your Music, returns error 403 Forbidden.
     *
     * @param array $options (optional) Request parameters
     * @throws GuzzleException
     * @return string
     */
    public function saveTracks(array $options = []): string
    {
        return $this->delegate("PUT", SdkConstants::LIBRARY["TRACKS"], $options);
    }

    /**
     * Remove one or more tracks from the current user’s ‘Your Music’ library.
     *
     * Header:
     * - required
     *      - Authorization(string): A valid access token from the Spotify Accounts service: see the Web API Authorization Guide for details. Modification of the current user’s “Your Music” collection requires authorization of the user-library-modify scope.
     * - optional
     *      - Content-Type(string): Required if the IDs are passed in the request body, otherwise ignored. The content type of the request body: application/json
     *
     * Query parameter:
     * - required
     *      - ids(string): A comma-separated list of the Spotify IDs. For example: ids=4iV5W9uYEdYUVa79Axb7Rh,1301WleyT98MSxVHPZCA6M. Maximum: 50 IDs.
     *
     * JSON body parameter:
     * - optional
     *      - ids(array[string]): A JSON array of the Spotify IDs. For example: ["4iV5W9uYEdYUVa79Axb7Rh", "1301WleyT98MSxVHPZCA6M"] A maximum of 50 items can be specified in one request. Note: if the ids parameter is present in the query string, any IDs listed here in the body will be ignored.
     *
     * Response:
     *
     * On success, the HTTP status code in the response header is 200 Success.
     * On error, the header status code is an error code and the response body contains an error object.
     * Trying to remove an album when you do not have the user’s authorization returns error 403 Forbidden.
     *
     * @param array $options (optional) Request parameters
     * @throws GuzzleException
     * @return string
     */
    public function removeTracks(array $options = []): string
    {
        return $this->delegate("DELETE", SdkConstants::LIBRARY["TRACKS"], $options);
    }

    /**
     * Check if one or more tracks is already saved in the current Spotify user’s ‘Your Music’ library.
     *
     * Header:
     * - required
     *      - Authorization(string): A valid access token from the Spotify Accounts service: see the Web API Authorization Guide for details. The user-library-read scope must have been authorized by the user.
     *
     * Query parameter:
     * - required
     *      - ids(string): A comma-separated list of the Spotify IDs. For example: ids=4iV5W9uYEdYUVa79Axb7Rh,1301WleyT98MSxVHPZCA6M. Maximum: 50 IDs.
     *
     * Response:
     *
     * On success, the HTTP status code in the response header is 200 OK and the response body contains a JSON array of true or false values, in the same order in which the ids were specified.
     * On error, the header status code is an error code and the response body contains an error object.
     *
     * @param array $options (optional) Request parameters
     * @throws GuzzleException
     * @return string
     */
    public function checkSavedTracks(array $options = []): string
    {
        return $this->delegate("GET", SdkConstants::LIBRARY["TRACKS"] . "/contains", $options);
    }

    /**
     * Get a list of the episodes saved in the current Spotify user’s library.
     * This API endpoint is in beta and could change without warning.
     *
     * Header:
     * - required
     *      - Authorization(string): A valid access token from the Spotify Accounts service: see the Web API Authorization Guide for details. The user-library-read scope must have been authorized by the user.
     *
     * Query parameter:
     * - optional
     *      - market(string): An ISO 3166-1 alpha-2 country code. If a country code is specified, only episodes that are available in that market will be returned. If a valid user access token is specified in the request header, the country associated with the user account will take priority over this parameter. Note: If neither market or user country are provided, the content is considered unavailable for the client. Users can view the country that is associated with their account in the account settings.
     *      - limit(integer): The maximum number of objects to return. Default: 20. Minimum: 1. Maximum: 50.
     *      - offset(integer): The index of the first object to return. Default: 0 (i.e., the first object). Use with limit to get the next set of objects.
     *
     * Response:
     *
     * On success, the HTTP status code in the response header is 200 OK and the response body contains an array of saved episode objects (wrapped in a paging object) in JSON format.
     * On error, the header status code is an error code and the response body contains an error object.
     * Trying to get more than 2000 episodes for a user will return results for 2000 episodes.
     * Only the 2000 returned episodes are sorted. This limitation will be removed in the near future.
     *
     * @param array $options (optional) Request parameters
     * @throws GuzzleException
     * @return string
     */
    public function getSavedEpisodes(array $options = []): string
    {
        return $this->delegate("GET", SdkConstants::LIBRARY["EPISODES"], $options);
    }

    /**
     * Save one or more episodes to the current user’s library.
     * This API endpoint is in beta and could change without warning.
     *
     * Header:
     * - required
     *      - Authorization(string): A valid access token from the Spotify Accounts service: see the Web API Authorization Guide for details. Modification of the current user’s “Your Music” collection requires authorization of the user-library-modify scope.
     * - optional
     *      - Content-Type(string): Required if the IDs are passed in the request body, otherwise ignored. The content type of the request body: application/json
     *
     * Query parameter:
     * - required
     *      - ids(string): A comma-separated list of the Spotify IDs. For example: ids=4iV5W9uYEdYUVa79Axb7Rh,1301WleyT98MSxVHPZCA6M. Maximum: 50 IDs.
     *
     * JSON body parameter:
     * - optional
     *      - ids(array[string]): A JSON array of the Spotify IDs. For example: ["4iV5W9uYEdYUVa79Axb7Rh", "1301WleyT98MSxVHPZCA6M"] A maximum of 50 items can be specified in one request. Note: if the ids parameter is present in the query string, any IDs listed here in the body will be ignored.
     *
     * Response:
     *
     * On success, the HTTP status code in the response header is 200 OK.
     * On error, the header status code is an error code and the response body contains an error object.
     * Trying to add an episode when you do not have the user’s authorization, returns error 403 Forbidden.
     *
     * @param array $options (optional) Request parameters
     * @throws GuzzleException
     * @return string
     */
    public function saveEpisodes(array $options = []): string
    {
        return $this->delegate("PUT", SdkConstants::LIBRARY["EPISODES"], $options);
    }

    /**
     * Remove one or more episodes from the current user’s library.
     * This API endpoint is in beta and could change without warning.
     *
     * Header:
     * - required
     *      - Authorization(string): A valid access token from the Spotify Accounts service: see the Web API Authorization Guide for details. Modification of the current user’s collection requires authorization of the user-library-modify scope.
     * - optional
     *      - Content-Type(string): Required if the IDs are passed in the request body, otherwise ignored. The content type of the request body: application/json
     *
     * Query parameter:
     * - required
     *      - ids(string): A comma-separated list of the Spotify IDs. For example: ids=4iV5W9uYEdYUVa79Axb7Rh,1301WleyT98MSxVHPZCA6M. Maximum: 50 IDs.
     *
     * JSON body parameter:
     * - optional
     *      - ids(array[string]): A JSON array of the Spotify IDs. For example: ["4iV5W9uYEdYUVa79Axb7Rh", "1301WleyT98MSxVHPZCA6M"] A maximum of 50 items can be specified in one request. Note: if the ids parameter is present in the query string, any IDs listed here in the body will be ignored.
     *
     * Response:
     *
     * On success, the HTTP status code in the response header is 200 Success.
     * On error, the header status code is an error code and the response body contains an error object.
     * Trying to remove an episode when you do not have the user’s authorization returns error 403 Forbidden.
     *
     * @param array $options (optional) Request parameters
     * @throws GuzzleException
     * @return string
     */
    public function removeEpisodes(array $options = []): string
    {
        return $this->delegate("DELETE", SdkConstants::LIBRARY["EPISODES"], $options);
    }

    /**
     * Check if one or more episodes is already saved in the current Spotify user’s ‘Your Episodes’ library.
     *
     * Header:
     * - required
     *      - Authorization(string): A valid access token from the Spotify Accounts service: see the Web API Authorization Guide for details. The user-library-read scope must have been authorized by the user.
     *
     * Query parameter:
     * - required
     *      - ids(string): A comma-separated list of the Spotify IDs. For example: ids=4iV5W9uYEdYUVa79Axb7Rh,1301WleyT98MSxVHPZCA6M. Maximum: 50 IDs.
     *
     * Response:
     *
     * On success, the HTTP status code in the response header is 200 OK and the response body contains a JSON array of true or false values, in the same order in which the ids were specified.
     * On error, the header status code is an error code and the response body contains an error object.
     *
     * @param array $options (optional) Request parameters
     * @throws GuzzleException
     * @return string
     */
    public function checkSavedEpisodes(array $options = []): string
    {
        return $this->delegate("GET", SdkConstants::LIBRARY["EPISODES"] . "/contains", $options);
    }

    /**
     * Get a list of shows saved in the current Spotify user’s library. Optional parameters can be used to limit the number of shows returned.
     *
     * Header:
     * - required
     *      - Authorization(string): A valid access token from the Spotify Accounts service: see the Web API Authorization Guide for details. The access token must have been isued on behalf of the user. The user-libary-read scope must have been authorised by the user.
     *
     * Query parameter:
     * - optional
     *      - limit(integer): The maximum number of objects to return. Default: 20. Minimum: 1. Maximum: 50.
     *      - offset(integer): The index of the first show to return. Default: 0 (the first object). Use with limit to get the next set of shows.
     *
     * Response:
     *
     * On success, the HTTP status code in the response header is 200 OK and the response body contains an array of saved show objects (wrapped in a paging object) in JSON format.
     * If the current user has no shows saved, the response will be an empty array. If a show is unavailable in the given market it is filtered out.
     * The total field in the paging object represents the number of all items, filtered or not, and thus might be larger than the actual total number of observable items.
     * On error, the header status code is an error code and the response body contains an error object.
     *
     * @param array $options (optional) Request parameters
     * @throws GuzzleException
     * @return string
     */
    public function getSavedShows(array $options = []): string
    {
        return $this->delegate("GET", SdkConstants::LIBRARY["SHOWS"], $options);
    }

    /**
     * Save one or more shows to current Spotify user’s library.
     *
     * Header:
     * - required
     *      - Authorization(string): A valid access token from the Spotify Accounts service: see the Web API Authorization Guide for details. The access token must have been issued on behalf of the user. The user-library-modify scope must have been authorized by the user.
     *
     * Query parameter:
     * - required
     *      - ids(string): A comma-separated list of the Spotify IDs. For example: ids=4iV5W9uYEdYUVa79Axb7Rh,1301WleyT98MSxVHPZCA6M. Maximum: 50 IDs.
     *
     * Response:
     *
     * On success, the HTTP status code in the response header is 200 OK.
     * On error, the header status code is an error code and the response body contains an error object.
     * A 403 Forbidden while trying to add a show when you do not have the user’s authorisation or when the user already has have over 10,000 items saved in library.
     *
     * @param array $options (optional) Request parameters
     * @throws GuzzleException
     * @return string
     */
    public function saveShows(array $options = []): string
    {
        return $this->delegate("PUT", SdkConstants::LIBRARY["SHOWS"], $options);
    }

    /**
     * Delete one or more shows from current Spotify user’s library.
     *
     * Header:
     * - required
     *      - Authorization(string): A valid access token from the Spotify Accounts service: see the Web API Authorization Guide for details. The access token must have been issued on behalf of the user. The user-library-modify scope must have been authorized by the user.
     *
     * Query parameter:
     * - required
     *      - ids(string): A comma-separated list of the Spotify IDs. For example: ids=4iV5W9uYEdYUVa79Axb7Rh,1301WleyT98MSxVHPZCA6M. Maximum: 50 IDs.
     *
     * - optional
     *      - market(string): An ISO 3166-1 alpha-2 country code. If a country code is specified, only shows that are available in that market will be removed. If a valid user access token is specified in the request header, the country associated with the user account will take priority over this parameter. Note: If neither market or user country are provided, the content is considered unavailable for the client. Users can view the country that is associated with their account in the account settings.
     *
     * Response:
     *
     * On success, the HTTP status code in the response header is 200 OK. On error, the header status code is an error code and the response body contains an error object.
     * A 403 Forbidden while trying to add a show when you do not have the user’s authorisation.
     *
     * @param array $options (optional) Request parameters
     * @throws GuzzleException
     * @return string
     */
    public function removeShows(array $options = []): string
    {
        return $this->delegate("DELETE", SdkConstants::LIBRARY["SHOWS"], $options);
    }

    /**
     * Check if one or more shows is already saved in the current Spotify user’s library.
     *
     * Header:
     * - required
     *      - Authorization(string): A valid access token from the Spotify Accounts service: see the Web API Authorization Guide for details. The access token must have been isued on behalf of the user. The user-libary-read scope must have been authorised by the user.
     *
     * Query parameter:
     * - required
     *      - ids(string): A comma-separated list of the Spotify IDs. For example: ids=4iV5W9uYEdYUVa79Axb7Rh,1301WleyT98MSxVHPZCA6M. Maximum: 50 IDs.
     *
     * Response:
     *
     * On success, the HTTP status code in the response header is 200 OK and the response body contains a JSON array of trueor false values, in the same order in which the ids were specified.
     * On error, the header status code is an error code and the response body contains an error object.
     *
     * @param array $options (optional) Request parameters
     * @throws GuzzleException
     * @return string
     */
    public function checkSavedShows(array $options = []): string
    {
        return $this->delegate("GET", SdkConstants::LIBRARY["SHOWS"] . "/contains", $options);
    }

}
