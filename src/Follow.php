<?php


namespace Gjoni\SpotifyWebApiSdk;

use Gjoni\SpotifyWebApiSdk\Http\Client;
use Gjoni\SpotifyWebApiSdk\Interfaces\SdkInterface;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Responsible for communicating with the Follow API.
 *
 * @package Gjoni\SpotifyWebApiSdk
 * @author Reard Gjoni <gjoni-r@hotmail.com>
 * @link https://developer.spotify.com/documentation/web-api/reference/#category-follow
 */
class Follow extends Client
{
    /**
     * Follow constructor.
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
     * Add the current user as a follower of a playlist.
     *
     * Header:
     * - required
     *      - Authorization(string): A valid user access token or your client credentials. Requires the user-follow-modify scope.
     *      - Content-Type(string): The content type of the request body: application/json
     *
     * Path parameter:
     * - required
     *      - {playlist_id}(string): The Spotify ID of the playlist. Any playlist can be followed, regardless of its public/private status, as long as you know its playlist ID.
     *
     * Json body parameter:
     * - optional
     *      - public(boolean): Defaults to true. If true the playlist will be included in user’s public playlists, if false it will remain private. To be able to follow playlists privately, the user must have granted the playlist-modify-private scope.
     *
     * Response:
     *
     * On success, the HTTP status code in the response header is 200 OK and the response body is empty.
     * On error, the header status code is an error code and the response body contains an error object.
     *
     * @param string $id The playlist id
     * @param array $options (optional) Request parameters
     * @throws GuzzleException
     * @return string
     */
    public function followPlaylist(string $id, array $options = []): string
    {
        return $this->delegate("PUT", SdkConstants::PLAYLISTS . "/$id/followers", $options);
    }

    /**
     * Remove the current user as a follower of a playlist.
     *
     * Header:
     * - required
     *      - Authorization(string): A valid access token from the Spotify Accounts service: see the Web API Authorization Guide for details. The access token must have been issued on behalf of the user. Unfollowing a publicly followed playlist for a user requires authorization of the playlist-modify-public scope; unfollowing a privately followed playlist requires the playlist-modify-private scope. See Using Scopes. Note that the scopes you provide relate only to whether the current user is following the playlist publicly or privately (i.e. showing others what they are following), not whether the playlist itself is public or private.
     *
     * Path parameter:
     * - required
     *      - {playlist_id}(string): The Spotify ID of the playlist that is to be no longer followed.
     *
     * Response:
     *
     * On success, the HTTP status code in the response header is 200 OK and the response body is empty.
     * On error, the header status code is an error code and the response body contains an error object.
     *
     * @param string $id The playlist id
     * @param array $options (optional) Request parameters
     * @throws GuzzleException
     * @return string
     */
    public function unfollowPlaylist(string $id, array $options = []): string
    {
        return $this->delegate("DELETE", SdkConstants::PLAYLISTS . "/$id/followers", $options);
    }

    /**
     * Check to see if one or more Spotify users are following a specified playlist.
     *
     * Header:
     * - required
     *      - Authorization(string): A valid user access token or your client credentials. Requires the playlist-read-private scope if a private playlist is requested.
     *
     * Path parameter:
     * - required
     *      - {playlist_id}(string): The Spotify ID of the playlist.
     *
     * Query parameter:
     *  - required
     *      - ids(string): A comma-separated list of Spotify User IDs ; the ids of the users that you want to check to see if they follow the playlist. Maximum: 5 ids.
     *
     * Response:
     *
     * On success, the HTTP status code in the response header is 200 OK and the response body contains a JSON array of true or false values, in the same order in which the ids were specified.
     * On error, the header status code is an error code and the response body contains an error object.
     *
     * @param string $id The playlist id
     * @param array $options (optional) Request parameters
     * @throws GuzzleException
     * @return string
     */
    public function usersFollowPlaylist(string $id, array $options = []): string
    {
        return $this->delegate("GET", SdkConstants::PLAYLISTS . "/$id/followers/contains", $options);
    }

    /**
     * Get the current user’s followed artists.
     *
     * Header:
     * - required
     *      - Authorization(string): A valid user access token or your client credentials. Requires the user-follow-read scope.
     *
     * Query parameter:
     *  - required
     *      - type(string): The ID type: currently only artist is supported.
     *  - optional
     *      - after(string): The last artist ID retrieved from the previous request.
     *      - limit(integer): The maximum number of items to return. Default: 20. Minimum: 1. Maximum: 50.
     *
     * Response:
     *
     * On success, the HTTP status code in the response header is 200 OK and the response body contains an artists object. The artists object in turn contains a cursor-based paging object of Artists.
     * On error, the header status code is an error code and the response body contains an error object.
     *
     * @param array $options (optional) Request parameters
     * @throws GuzzleException
     * @return string
     */
    public function getFollowing(array $options = []): string
    {
        return $this->delegate("GET", SdkConstants::ME . "/following", $options);
    }

    /**
     * Add the current user as a follower of one or more artists or other Spotify users.
     *
     * Header:
     * - required
     *      - Authorization(string): A valid user access token or your client credentials. Requires the user-follow-modify scope.
     * - optional
     *      - Content-Type(string): Required if IDs are passed in the request body, otherwise ignored. The content type of the request body: application/json
     *
     * Query parameter:
     *  - required
     *      - type(string): The ID type: either artist or user.
     *      - ids(string): A comma-separated list of the artist or the user Spotify IDs. For example: ids=74ASZWbe4lXaubB36ztrGX,08td7MxkoHQkXnWAYD8d6Q. A maximum of 50 IDs can be sent in one request.
     *
     * JSON body parameter:
     *  - required
     *      - ids(array[string]): {ids:["74ASZWbe4lXaubB36ztrGX", "08td7MxkoHQkXnWAYD8d6Q"]}. A maximum of 50 IDs can be sent in one request. Note: if the ids parameter is present in the query string, any IDs listed here in the body will be ignored.
     *
     * Response:
     *
     * On success, the HTTP status code in the response header is 204 No Content and the response body is empty.
     * On error, the header status code is an error code and the response body contains an error object.
     *
     * @param array $options (optional) Request parameters
     * @throws GuzzleException
     * @return string|null
     */
    public function follow(array $options = []): ?string
    {
        return $this->delegate("PUT", SdkConstants::ME . "/following", $options);
    }

    /**
     * Remove the current user as a follower of one or more artists or other Spotify users.
     *
     * Header:
     * - required
     *      - Authorization(string): A valid user access token or your client credentials. Requires the user-follow-modify scope.
     * - optional
     *      - Content-Type(string): Required if IDs are passed in the request body, otherwise ignored. The content type of the request body: application/json.
     *
     * Query parameter:
     *  - required
     *      - type(string): The ID type: either artist or user.
     *      - ids(string): A comma-separated list of the artist or the user Spotify IDs. For example: ids=74ASZWbe4lXaubB36ztrGX,08td7MxkoHQkXnWAYD8d6Q. A maximum of 50 IDs can be sent in one request.
     *
     * JSON body parameter:
     *  - optional
     *      - ids(array[string]): A JSON array of the artist or user Spotify IDs. For example: {ids:["74ASZWbe4lXaubB36ztrGX", "08td7MxkoHQkXnWAYD8d6Q"]}. A maximum of 50 IDs can be sent in one request. Note: if the ids parameter is present in the query string, any IDs listed here in the body will be ignored.
     *
     * Response:
     *
     * On success, the HTTP status code in the response header is 204 No Content and the response body is empty.
     * On error, the header status code is an error code and the response body contains an error object.
     *
     * @param array $options (optional) Request parameters
     * @throws GuzzleException
     * @return string|null
     */
    public function unfollow(array $options = []): ?string
    {
        return $this->delegate("DELETE", SdkConstants::ME . "/following", $options);
    }

    /**
     * Get Following State for Artists/Users
     *
     * Check to see if the current user is following one or more artists or other Spotify users.
     *
     * Header:
     * - required
     *      - Authorization(string): A valid user access token or your client credentials. Requires the user-follow-read scope.
     *
     * Query parameter:
     *  - required
     *      - type(string): The ID type: either artist or user.
     *      - ids(string): A comma-separated list of the artist or the user Spotify IDs to check. For example: ids=74ASZWbe4lXaubB36ztrGX,08td7MxkoHQkXnWAYD8d6Q. A maximum of 50 IDs can be sent in one request.
     *
     * Response:
     *
     * On success, the HTTP status code in the response header is 200 OK and the response body contains a JSON array of true or false values, in the same order in which the ids were specified.
     * On error, the header status code is an error code and the response body contains an error object.
     *
     * @param array $options (optional) Request parameters
     * @throws GuzzleException
     * @return string|null
     */
    public function followingState(array $options = []): ?string
    {
        return $this->delegate("GET", SdkConstants::ME . "/following/contains", $options);
    }

}
