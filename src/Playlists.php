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
     * Gets the current users' playlists.
     *
     *
     * Header:
     * - required
     *      - Authorization(string): A valid access token from the Spotify Accounts service.
     *
     * Query Parameter:
     * - optional
     *      - limit(integer): ‘The maximum number of playlists to return. Default: 20. Minimum: 1. Maximum: 50.’
     *      - offset(integer): ‘The index of the first playlist to return. Default: 0 (the first object). Maximum offset: 100.000. Use with limit to get the next set of playlists.’
     *
     * @param array $options (optional) Request parameters
     * @throws GuzzleException
     * @return string
     */
    public function getPlaylists(array $options = []): string {
        return $this->client->delegate("GET", SdkConstants::ME . "/playlists", $options);
    }

    /**
     * Gets a users' playlists.
     *
     *
     * Header:
     * - required
     *      - Authorization(string): A valid access token from the Spotify Accounts service.
     *
     * Path Parameter:
     * - required
     *      - {user_id}(string): The user’s Spotify user ID.
     *
     * Query Parameter:
     * - optional
     *      - limit(integer): ‘The maximum number of playlists to return. Default: 20. Minimum: 1. Maximum: 50.’
     *      - offset(integer): ‘The index of the first playlist to return. Default: 0 (the first object). Maximum offset: 100.000. Use with limit to get the next set of playlists.’
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
     * Creates a playlist for a user.
     *
     *
     * Header:
     * - required
     *      - Authorization(string): A valid access token from the Spotify Accounts service.
     * - optional:
     *      - Content-Type(string): The content type of the request body: application/json
     *
     * Path Parameter:
     * - required
     *      - {user_id}(string): The users Spotify user ID.
     *
     * JSON Body Parameter:
     * - required:
     *      - name(string): The name for the new playlist, for example "Your Coolest Playlist" . This name does not need to be unique; a user may have several playlists with the same name.
     * - optional:
     *      - public(boolean): Defaults to true . If true the playlist will be public, if false it will be private. To be able to create private playlists, the user must have granted the playlist-modify-private scope
     *      - collaborative(boolean): Defaults to false . If true the playlist will be collaborative. Note that to create a collaborative playlist you must also set public to false . To create collaborative playlists you must have granted playlist-modify-private and playlist-modify-public scopes .
     *      - description(string): value for playlist description as displayed in Spotify Clients and in the Web API.
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
     * Fetches a public playlist using the playlist id.
     *
     * Header:
     * - required
     *      - Authorization(string): A valid access token from the Spotify Accounts service.
     *
     * Path Parameter:
     * - required
     *      - {playlist_id}(string): The Spotify ID for the playlist.
     *
     * Query parameter:
     * - optional
     *      - market(string): An ISO 3166-1 alpha-2 country code or the string from_token. Provide this parameter if you want to apply Track Relinking. For episodes, if a valid user access token is specified in the request header, the country associated with the user account will take priority over this parameter. Note: If neither market or user country are provided, the episode is considered unavailable for the client.
     *      - fields(string): Filters for the query: a comma-separated list of the fields to return. If omitted, all fields are returned. For example, to get just the playlist’’s description and URI: fields=description,uri. A dot separator can be used to specify non-reoccurring fields, while parentheses can be used to specify reoccurring fields within objects. For example, to get just the added date and user ID of the adder: fields=tracks.items(added_at,added_by.id). Use multiple parentheses to drill down into nested objects, for example: fields=tracks.items(track(name,href,album(name,href))). Fields can be excluded by prefixing them with an exclamation mark, for example: fields=tracks.items(track(name,href,album(!name,href)))
     *      - additional_types(string): A comma-separated list of item types that your client supports besides the default track type. Valid types are: track and episode. Note: This parameter was introduced to allow existing clients to maintain their current behaviour and might be deprecated in the future. In addition to providing this parameter, make sure that your client properly handles cases of new types in the future by checking against the type field of each object.
     *
     * @param string $id The playlist id
     * @param array $options (optional) Request parameters
     * @throws GuzzleException
     * @return string
     */
    public function getPlaylist(string $id, array $options = []): string {
        return $this->client->delegate("GET", SdkConstants::PLAYLISTS . "/$id", $options);
    }
}
