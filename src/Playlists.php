<?php


namespace Gjoni\SpotifyWebApiSdk;

use Gjoni\SpotifyWebApiSdk\Http\Client;
use Gjoni\SpotifyWebApiSdk\Http\Response;
use Gjoni\SpotifyWebApiSdk\Interfaces\SdkInterface;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Responsible for communicating with the Playlists API
 *
 * @package Gjoni\SpotifyWebApiSdk
 * @author Reard Gjoni <gjoni-r@hotmail.com>
 * @link https://developer.spotify.com/documentation/web-api/reference/#category-playlists
 */
class Playlists
{
    /**
     * @var Client $client Custom client object
     */
    private Client $client;

    /**
     * @var Response Response object
     */
    private Response $response;

    /**
     * @var array $headers Client headers
     */
    private array $headers;

    /**
     * @var array $parameters Client parameters
     */
    private array $parameters;

    /**
     * @var SdkInterface $sdk Sdk object
     */
    private SdkInterface $sdk;

    /**
     * Playlists constructor.
     *
     * Initializes the client object, response, headers and client parameters.
     *
     * @param SdkInterface $sdk
     */
    public function __construct(SdkInterface $sdk)
    {
        $this->client = new Client($sdk , [
            "base_uri" => SdkConstants::API_URL,
            "timeout" => 1,
            "allow_redirects" => ["track_redirects" => true]
        ]);

        $this->response = $this->client->getResponse();
        $this->headers = $this->client->getHeaders();
        $this->parameters = $this->client->getParameters();
    }

    /**
     * Get a list of the playlists owned or followed by the current Spotify user.
     *
     *
     * Header:
     * - required
     *      - Authorization(string): A valid access token from the Spotify Accounts service.
     *
     * Query parameter:
     * - optional
     *      - limit(integer): ‘The maximum number of playlists to return. Default: 20. Minimum: 1. Maximum: 50.’
     *      - offset(integer): ‘The index of the first playlist to return. Default: 0 (the first object). Maximum offset: 100.000. Use with limit to get the next set of playlists.’
     *
     * Response:
     *
     * On success, the HTTP status code in the response header is 200 OK and the response body contains an array of simplified
     * playlist objects (wrapped in a paging object) in JSON format. On error, the header status code is an error code and the
     * response body contains an error object. Please note that the access token has to be tied to a user.
     *
     * @param array $options (optional) Request parameters
     * @throws GuzzleException
     * @return string
     */
    public function getPlaylists(array $options = []): string {
        return $this->client->delegate("GET", SdkConstants::ME . "/playlists", $options);
    }

    /**
     * Get a list of the playlists owned or followed by a Spotify user.
     *
     *
     * Header:
     * - required
     *      - Authorization(string): A valid access token from the Spotify Accounts service.
     *
     * Path parameter:
     * - required
     *      - {user_id}(string): The user’s Spotify user ID.
     *
     * Query parameter:
     * - optional
     *      - limit(integer): ‘The maximum number of playlists to return. Default: 20. Minimum: 1. Maximum: 50.’
     *      - offset(integer): ‘The index of the first playlist to return. Default: 0 (the first object). Maximum offset: 100.000. Use with limit to get the next set of playlists.’
     *
     * Response:
     *
     * On success, the HTTP status code in the response header is 200 OK and the response body contains an array of simplified
     * playlist objects (wrapped in a paging object) in JSON format. On error, the header status code is an error code and the
     * response body contains an error object.
     *
     * @param string $id The users' id
     * @param array $options (optional) Request parameters
     * @throws GuzzleException
     * @return string
     */
    public function getUserPlaylists(string $id, array $options = []): string {
        return $this->client->delegate("GET", SdkConstants::USERS . "/$id/playlists", $options);
    }

    /**
     * Create a playlist for a Spotify user. (The playlist will be empty until you add tracks.)
     *
     *
     * Header:
     * - required
     *      - Authorization(string): A valid access token from the Spotify Accounts service.
     * - optional:
     *      - Content-Type(string): The content type of the request body: application/json
     *
     * Path parameter:
     * - required
     *      - {user_id}(string): The users Spotify user ID.
     *
     * JSON body parameter:
     * - required:
     *      - name(string): The name for the new playlist, for example "Your Coolest Playlist" . This name does not need to be unique; a user may have several playlists with the same name.
     * - optional:
     *      - public(boolean): Defaults to true . If true the playlist will be public, if false it will be private. To be able to create private playlists, the user must have granted the playlist-modify-private scope
     *      - collaborative(boolean): Defaults to false . If true the playlist will be collaborative. Note that to create a collaborative playlist you must also set public to false . To create collaborative playlists you must have granted playlist-modify-private and playlist-modify-public scopes .
     *      - description(string): value for playlist description as displayed in Spotify Clients and in the Web API.
     *
     * Response:
     *
     * On success, the response body contains the created playlist object in JSON format and the HTTP status code in the response header
     * is 200 OK or 201 Created. There is also a Location response header giving the Web API endpoint for the new playlist.
     *
     * On error, the header status code is an error code and the response body contains an error object.
     * Trying to create a playlist when you do not have the user’s authorization returns error 403 Forbidden.
     *
     * @param string $id The users' id
     * @param array $options (optional) Request parameters
     * @throws GuzzleException
     * @return string
     */
    public function createPlaylist(string $id, array $options = []): string {
        return $this->client->delegate("POST", SdkConstants::USERS . "/$id/playlists", $options);
    }

    /**
     * Get a playlist owned by a Spotify user.
     *
     * Header:
     * - required
     *      - Authorization(string): A valid access token from the Spotify Accounts service.
     *
     * Path parameter:
     * - required
     *      - {playlist_id}(string): The Spotify ID for the playlist.
     *
     * Query parameter:
     * - optional
     *      - market(string): An ISO 3166-1 alpha-2 country code or the string from_token. Provide this parameter if you want to apply Track Relinking. For episodes, if a valid user access token is specified in the request header, the country associated with the user account will take priority over this parameter. Note: If neither market or user country are provided, the episode is considered unavailable for the client.
     *      - fields(string): Filters for the query: a comma-separated list of the fields to return. If omitted, all fields are returned. For example, to get just the playlist’’s description and URI: fields=description,uri. A dot separator can be used to specify non-reoccurring fields, while parentheses can be used to specify reoccurring fields within objects. For example, to get just the added date and user ID of the adder: fields=tracks.items(added_at,added_by.id). Use multiple parentheses to drill down into nested objects, for example: fields=tracks.items(track(name,href,album(name,href))). Fields can be excluded by prefixing them with an exclamation mark, for example: fields=tracks.items(track(name,href,album(!name,href)))
     *      - additional_types(string): A comma-separated list of item types that your client supports besides the default track type. Valid types are: track and episode. Note: This parameter was introduced to allow existing clients to maintain their current behaviour and might be deprecated in the future. In addition to providing this parameter, make sure that your client properly handles cases of new types in the future by checking against the type field of each object.
     *
     * Response:
     *
     * On success, the response body contains a playlist object in JSON format and the HTTP status code in the response header is 200 OK.
     * If an episode is unavailable in the given market, its information will not be included in the response.
     *
     * On error, the header status code is an error code and the response body contains an error object.
     * Requesting playlists that you do not have the user’s authorization to access returns error 403 Forbidden.
     *
     * @param string $id The playlist id
     * @param array $options (optional) Request parameters
     * @throws GuzzleException
     * @return string
     */
    public function getPlaylist(string $id, array $options = []): string {
        return $this->client->delegate("GET", SdkConstants::PLAYLISTS . "/$id", $options);
    }

    /**
     * Change a playlist’s name and public/private state. (The user must, of course, own the playlist.)
     *
     * Header:
     * - required
     *      - Authorization(string): A valid access token from the Spotify Accounts service.
     *      - Content-Type(string): The content type of the request body: application/json
     *
     * Path parameter:
     * - required
     *      - {playlist_id}(string): The Spotify ID for the playlist.
     *
     * JSON body parameter:
     * - optional
     *      - name(string): The new name for the playlist, for example "My New Playlist Title"
     *      - public(boolean): If true the playlist will be public, if false it will be private.
     *      - collaborative(boolean): If true , the playlist will become collaborative and other users will be able to modify the playlist in their Spotify client. Note: You can only set collaborative to true on non-public playlists.
     *      - description(string): Value for playlist description as displayed in Spotify Clients and in the Web API.
     *
     * Response:
     *
     * On success the HTTP status code in the response header is 200 OK.
     *
     * On error, the header status code is an error code and the response body contains an error object.
     * Trying to change a playlist when you do not have the user’s authorization returns error 403 Forbidden.
     *
     * @param string $id The playlist id
     * @param array $options (optional) Request parameters
     * @throws GuzzleException
     * @return string
     */
    public function changePlaylistDetails(string $id, array $options = []): string {
        return $this->client->delegate("PUT", SdkConstants::PLAYLISTS . "/$id", $options);
    }

    /**
     * Get full details of the items of a playlist owned by a Spotify user.
     *
     * Header:
     * - required
     *      - Authorization(string): A valid access token from the Spotify Accounts service.
     * Path parameter:
     * - required
     *      - {playlist_id}(string): The Spotify ID for the playlist.
     *
     * Query parameter:
     * - required
     *      - market(string): An ISO 3166-1 alpha-2 country code or the string from_token. Provide this parameter if you want to apply Track Relinking. For episodes, if a valid user access token is specified in the request header, the country associated with the user account will take priority over this parameter. Note: If neither market or user country are provided, the episode is considered unavailable for the client.
     *
     * - optional
     *      - fields(string): Filters for the query: a comma-separated list of the fields to return. If omitted, all fields are returned. For example, to get just the total number of items and the request limit: fields=total,limit A dot separator can be used to specify non-reoccurring fields, while parentheses can be used to specify reoccurring fields within objects. For example, to get just the added date and user ID of the adder: fields=items(added_at,added_by.id) Use multiple parentheses to drill down into nested objects, for example: fields=items(track(name,href,album(name,href))) Fields can be excluded by prefixing them with an exclamation mark, for example: fields=items.track.album(!external_urls,images)
     *      - limit(integer): The maximum number of items to return. Default: 100. Minimum: 1. Maximum: 100.
     *      - offset(integer): The index of the first item to return. Default: 0 (the first object).
     *      - additional_types(string): A comma-separated list of item types that your client supports besides the default track type. Valid types are: track and episode. Note: This parameter was introduced to allow existing clients to maintain their current behaviour and might be deprecated in the future. In addition to providing this parameter, make sure that your client properly handles cases of new types in the future by checking against the type field of each object.
     *
     * Response:
     *
     * On success, the response body contains an array of track objects and episode objects (depends on the additional_types parameter),
     * wrapped in a paging object in JSON format and the HTTP status code in the response header is 200 OK.
     * If an episode is unavailable in the given market, its information will not be included in the response.
     *
     * On error, the header status code is an error code and the response body contains an error object.
     * Requesting playlists that you do not have the user’s authorization to access returns error 403 Forbidden.
     *
     * @param string $id
     * @param array $options
     * @throws GuzzleException
     * @return string
     */
    public function getPlaylistItems(string $id, array $options = []): string {
        return $this->client->delegate("GET", SdkConstants::PLAYLISTS . "/$id/tracks", $options);
    }
}
