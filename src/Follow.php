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
class Follow
{
    /**
     * @var Client $client Custom client object
     */
    private Client $client;

    /**
     * Episodes constructor.
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
     * Add the current user as a follower of a playlist.
     *
     * Header:
     * - required
     *      - Authorization(string): A valid user access token or your client credentials.
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
        return $this->client->delegate("PUT", SdkConstants::PLAYLISTS . "/$id/followers", $options);
    }

}
