<?php


namespace Gjoni\SpotifyWebApiSdk;

use Gjoni\SpotifyWebApiSdk\Http\Client;
use Gjoni\SpotifyWebApiSdk\Interfaces\SdkInterface;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Responsible for communicating with the Player API.
 *
 * @package Gjoni\SpotifyWebApiSdk
 * @author Reard Gjoni <gjoni-r@hotmail.com>
 * @link https://developer.spotify.com/documentation/web-api/reference/#category-player
 */
class Player extends Client
{
    /**
     * Player constructor.
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
     * Get information about the user’s current playback state, including track or episode, progress, and active device.
     *
     * Header:
     * - required
     *      - Authorization(string): A valid access token from the Spotify Accounts service: see the Web API Authorization Guide for details.
     *
     * Query parameters:
     * - optional
     *      - market(string): An ISO 3166-1 alpha-2 country code or the string from_token. Provide this parameter if you want to apply Track Relinking.
     *      - additional_types(string): A comma-separated list of item types that your client supports besides the default track type. Valid types are: track and episode. An unsupported type in the response is expected to be represented as null value in the item field. Note: This parameter was introduced to allow existing clients to maintain their current behaviour and might be deprecated in the future. In addition to providing this parameter, make sure that your client properly handles cases of new
     *
     * Response:
     *
     * A successful request will return a 200 OK response code with a json payload that contains information about the current playback.
     * The information returned is for the last known state, which means an inactive device could be returned if it was the last one to execute playback.
     * When no available devices are found, the request will return a 200 OK response but with no data populated.
     *
     * @param array $options (optional) Request parameters
     * @throws GuzzleException
     * @return string|null
     */
    public function getCurrentPlayback(array $options = []): ?string
    {
        return $this->delegate("GET", SdkConstants::PLAYER, $options);
    }

    /**
     * Transfer playback to a new device and determine if it should start playing.
     *
     * Header:
     * - required
     *      - Authorization(string): A valid access token from the Spotify Accounts service: see the Web API Authorization Guide for details. The access token must have been issued on behalf of a user. The access token must have the user-modify-playback-state scope authorized in order to control playback.
     *
     * JSON body parameter:
     *
     * - required
     *      - device_ids(array[string]): A JSON array containing the ID of the device on which playback should be started/transferred. For example:{device_ids:["74ASZWbe4lXaubB36ztrGX"]} Note: Although an array is accepted, only a single device_id is currently supported. Supplying more than one will return 400 Bad Request
     * - optional
     *      - play(boolean): true: ensure playback happens on new device. false or not provided: keep the current playback state.
     *
     * Response:
     *
     * A completed request will return a 204 NO CONTENT response code, and then issue the command to the player.
     * Due to the asynchronous nature of the issuance of the command, you should use the Get Information About The User’s Current Playback endpoint to check that your issued command was handled correctly by the player.
     * If the device is not found, the request will return 404 NOT FOUND response code.
     * If the user making the request is non-premium, a 403 FORBIDDEN response code will be returned.
     *
     * @param array $options (optional) Request parameters
     * @throws GuzzleException
     * @return string|null
     */
    public function transferPlayback(array $options = []): ?string
    {
        return $this->delegate("PUT", SdkConstants::PLAYER, $options);
    }

    /**
     * Get information about a user’s available devices.
     *
     * Header:
     * - required
     *      - Authorization(string): A valid access token from the Spotify Accounts service: see the Web API Authorization Guide for details. The access token must have been issued on behalf of a user. The access token must have the user-read-playback-state scope authorized in order to read information.
     *
     * Response:
     *
     * A successful request will return a 200 OK response code with a json payload that contains the device objects (see below).
     * When no available devices are found, the request will return a 200 OK response with an empty devices list.
     *
     * @param array $options (optional) Request parameters
     * @throws GuzzleException
     * @return string|null
     */
    public function getAvailableDevices(array $options = []): ?string
    {
        return $this->delegate("GET", SdkConstants::PLAYER . "/devices", $options);
    }

    /**
     * Get the object currently being played on the user’s Spotify account.
     *
     * Header:
     * - required
     *      - Authorization(string): A valid access token from the Spotify Accounts service: see the Web API Authorization Guide for details. The access token must have been issued on behalf of a user. The access token must have the user-read-currently-playing and/or user-read-playback-state scope authorized in order to read information.
     *
     * Query parameter:
     * - required
     *      - market(string): An ISO 3166-1 alpha-2 country code or the string from_token. Provide this parameter if you want to apply Track Relinking.
     * - optional
     *      - additional_types(string): A comma-separated list of item types that your client supports besides the default track type. Valid types are: track and episode. An unsupported type in the response is expected to be represented as null value in the item field. Note: This parameter was introduced to allow existing clients to maintain their current behaviour and might be deprecated in the future. In addition to providing this parameter, make sure that your client properly handles cases of new types in the future by checking against the currently_playing_type field.
     *
     * Response:
     *
     * A successful request will return a 200 OK response code with a json payload that contains information about the currently playing track or episode and its context (see below). The information returned is for the last known state, which means an inactive device could be returned if it was the last one to execute playback.
     * When no available devices are found, the request will return a 200 OK response but with no data populated.
     * When no track is currently playing, the request will return a 204 NO CONTENT response with no payload.
     * If private session is enabled the response will be a 204 NO CONTENT with an empty payload.
     *
     * @param array $options (optional) Request parameters
     * @throws GuzzleException
     * @return string|null
     */
    public function getCurrentlyPlaying(array $options = []): ?string
    {
        return $this->delegate("GET", SdkConstants::PLAYER . "/currently-playing", $options);
    }

    /**
     * Start a new context or resume current playback on the user’s active device.
     *
     * Header:
     * - required
     *      - Authorization(string): A valid access token from the Spotify Accounts service: see the Web API Authorization Guide for details. The access token must have been issued on behalf of a user. The access token must have the user-modify-playback-state scope authorized in order to control playback.
     *
     * Query parameter:
     * - optional
     *      - device_id(string): The id of the device this command is targeting. If not supplied, the user’s currently active device is the target.
     *
     * JSON body parameter:
     * - optional
     *      - context_uri(string): string
     *      - uris(array[string]): Array of URIs
     *      - offset(object): object
     *      - position_ms(integer): integer
     *
     * Response:
     *
     * A completed request will return a 204 NO CONTENT response code, and then issue the command to the player.
     * Due to the asynchronous nature of the issuance of the command, you should use the Get Information About The User’s Current Playback endpoint to check that your issued command was handled correctly by the player.
     * If the device is not found, the request will return 404 NOT FOUND response code.
     * If the user making the request is non-premium, a 403 FORBIDDEN response code will be returned.
     *
     * @param array $options (optional) Request parameters
     * @throws GuzzleException
     * @return string|null
     */
    public function startPlayback(array $options = []): ?string
    {
        return $this->delegate("PUT", SdkConstants::PLAYER . "/play", $options);
    }

    /**
     * Pause playback on the user’s account.
     *
     * Header:
     * - required
     *      - Authorization(string): A valid access token from the Spotify Accounts service: see the Web API Authorization Guide for details. The access token must have been issued on behalf of a user. The access token must have the user-modify-playback-state scope authorized in order to control playback.
     *
     * Query parameter:
     * - optional
     *      - device_id(string): The id of the device this command is targeting. If not supplied, the user’s currently active device is the target.
     *
     * Response:
     *
     * A completed request will return a 204 NO CONTENT response code, and then issue the command to the player.
     * Due to the asynchronous nature of the issuance of the command, you should use the Get Information About The User’s Current Playback endpoint to check that your issued command was handled correctly by the player.
     * If the device is not found, the request will return 404 NOT FOUND response code.
     * If the user making the request is non-premium, a 403 FORBIDDEN response code will be returned.
     *
     * @param array $options (optional) Request parameters
     * @throws GuzzleException
     * @return string|null
     */
    public function pausePlayback(array $options = []): ?string
    {
        return $this->delegate("PUT", SdkConstants::PLAYER . "/pause", $options);
    }

    /**
     * Skips to next track in the user’s queue.
     *
     * Header:
     * - required
     *      - Authorization(string): A valid access token from the Spotify Accounts service: see the Web API Authorization Guide for details. The access token must have been issued on behalf of a user. The access token must have the user-modify-playback-state scope authorized in order to control playback.
     *
     * Query parameter:
     * - optional
     *      - device_id(string): The id of the device this command is targeting. If not supplied, the user’s currently active device is the target.
     *
     * Response:
     *
     * A completed request will return a 204 NO CONTENT response code, and then issue the command to the player.
     * Due to the asynchronous nature of the issuance of the command, you should use the Get Information About The User’s Current Playback endpoint to check that your issued command was handled correctly by the player.
     * If the device is not found, the request will return 404 NOT FOUND response code.
     * If the user making the request is non-premium, a 403 FORBIDDEN response code will be returned.
     *
     * @param array $options (optional) Request parameters
     * @throws GuzzleException
     * @return string|null
     */
    public function skipPlayback(array $options = []): ?string
    {
        return $this->delegate("POST", SdkConstants::PLAYER . "/next", $options);
    }

    /**
     * Seeks to the given position in the user’s currently playing track.
     *
     * Header:
     * - required
     *      - Authorization(string): A valid access token from the Spotify Accounts service: see the Web API Authorization Guide for details. The access token must have been issued on behalf of a user. The access token must have the user-modify-playback-state scope authorized in order to control playback.
     *
     * Query parameter:
     * - required
     *      - position_ms(integer): The position in milliseconds to seek to. Must be a positive number. Passing in a position that is greater than the length of the track will cause the player to start playing the next song.
     * - optional
     *      - device_id(string):The id of the device this command is targeting. If not supplied, the user’s currently active device is the target.
     *
     * Response:
     *
     * A completed request will return a 204 NO CONTENT response code, and then issue the command to the player.
     * Due to the asynchronous nature of the issuance of the command, you should use the Get Information About The User’s Current Playback endpoint to check that your issued command was handled correctly by the player.
     * If the device is not found, the request will return 404 NOT FOUND response code.
     * If the user making the request is non-premium, a 403 FORBIDDEN response code will be returned.
     *
     * @param array $options (optional) Request parameters
     * @throws GuzzleException
     * @return string|null
     */
    public function seekTrackPosition(array $options = []): ?string
    {
        return $this->delegate("PUT", SdkConstants::PLAYER . "/seek", $options);
    }

    /**
     * Set the repeat mode for the user’s playback. Options are repeat-track, repeat-context, and off.
     *
     * Header:
     * - required
     *      - Authorization(string): A valid access token from the Spotify Accounts service: see the Web API Authorization Guide for details. The access token must have been issued on behalf of a user. The access token must have the user-modify-playback-state scope authorized in order to control playback.
     *
     * Query parameter:
     * - required
     *      - state(string): track, context or off. track will repeat the current track. context will repeat the current context. off will turn repeat off.
     * - optional
     *      - device_id(string):The id of the device this command is targeting. If not supplied, the user’s currently active device is the target.
     *
     * Response:
     *
     * A completed request will return a 204 NO CONTENT response code, and then issue the command to the player. Due to the asynchronous nature of the issuance of the command, you should use the Get Information About The User’s Current Playback endpoint to check that your issued command was handled correctly by the player.
     * If the device is not found, the request will return 404 NOT FOUND response code.
     * If the user making the request is non-premium, a 403 FORBIDDEN response code will be returned.
     *
     * @param array $options (optional) Request parameters
     * @throws GuzzleException
     * @return string|null
     */
    public function setRepeatMode(array $options = []): ?string
    {
        return $this->delegate("PUT", SdkConstants::PLAYER . "/repeat", $options);
    }

    /**
     * Set the volume for the user’s current playback device.
     *
     * Header:
     * - required
     *      - Authorization(string): A valid access token from the Spotify Accounts service: see the Web API Authorization Guide for details. The access token must have been issued on behalf of a user. The access token must have the user-modify-playback-state scope authorized in order to control playback.
     *
     * Query parameter:
     * - required
     *      - volume_percent(integer): The volume to set. Must be a value from 0 to 100 inclusive.
     * - optional
     *      - device_id(string): The id of the device this command is targeting. If not supplied, the user’s currently active device is the target.
     *
     * Response:
     *
     * A completed request will return a 204 NO CONTENT response code, and then issue the command to the player.
     * Due to the asynchronous nature of the issuance of the command, you should use the Get Information About The User’s Current Playback endpoint to check that your issued command was handled correctly by the player.
     * If the device is not found, the request will return 404 NOT FOUND response code.
     * If the user making the request is non-premium, a 403 FORBIDDEN response code will be returned.
     *
     * @param array $options (optional) Request parameters
     * @throws GuzzleException
     * @return string|null
     */
    public function setVolume(array $options = []): ?string
    {
        return $this->delegate("PUT", SdkConstants::PLAYER . "/volume", $options);
    }

    /**
     * Toggle shuffle on or off for user’s playback.
     *
     * Header:
     * - required
     *      - Authorization(string): A valid access token from the Spotify Accounts service: see the Web API Authorization Guide for details. The access token must have been issued on behalf of a user. The access token must have the user-modify-playback-state scope authorized in order to control playback.
     *
     * Query parameter:
     * - required
     *      - state(string[boolean]): true : Shuffle user’s playback. false : Do not shuffle user’s playback.
     * - optional
     *      - device_id(string): The id of the device this command is targeting. If not supplied, the user’s currently active device is the target.
     *
     * Response:
     *
     * A completed request will return a 204 NO CONTENT response code, and then issue the command to the player.
     * Due to the asynchronous nature of the issuance of the command, you should use the Get Information About The User’s Current Playback endpoint to check that your issued command was handled correctly by the player.
     * If the device is not found, the request will return 404 NOT FOUND response code.
     * If the user making the request is non-premium, a 403 FORBIDDEN response code will be returned.
     *
     * @param array $options (optional) Request parameters
     * @throws GuzzleException
     * @return string|null
     */
    public function toggleShuffle(array $options = []): ?string
    {
        return $this->delegate("PUT", SdkConstants::PLAYER . "/shuffle", $options);
    }

    /**
     * Get tracks from the current user’s recently played tracks. Note: Currently doesn't support podcast episodes.
     *
     * Header:
     * - required
     *      - Authorization(string): A valid access token from the Spotify Accounts service: see the Web API Authorization Guide for details. The access token must have been issued on behalf of a user. The access token must have the user-read-recently-played scope authorized in order to read the user’’s recently played track.’
     *
     * Query parameter:
     * - optional
     *      - limit(integer): The maximum number of items to return. Default: 20. Minimum: 1. Maximum: 50.
     *      - after(integer): A Unix timestamp in milliseconds. Returns all items after (but not including) this cursor position. If after is specified, before must not be specified.
     *      - before(integer): A Unix timestamp in milliseconds. Returns all items before (but not including) this cursor position. If before is specified, after must not be specified.
     *
     * Response:
     *
     * On success, the HTTP status code in the response header is 200 OK and the response body contains an array of play history objects (wrapped in a cursor-based paging object) in JSON format.
     * The play history items each contain the context the track was played from (e.g. playlist, album), the date and time the track was played, and a track object (simplified).
     * On error, the header status code is an error code and the response body contains an error object.
     * If private session is enabled the response will be a 204 NO CONTENT with an empty payload.
     *
     * @param array $options (optional) Request parameters
     * @throws GuzzleException
     * @return string|null
     */
    public function getRecentlyPlayed(array $options = []): ?string
    {
        return $this->delegate("GET", SdkConstants::PLAYER . "/recently-played", $options);
    }

    /**
     * Add an item to the end of the user’s current playback queue.
     *
     * Header:
     * - required
     *      - Authorization(string): A valid access token from the Spotify Accounts service: see the Web API Authorization Guide for details. The access token must have been issued on behalf of a user. The access token must have the user-modify-playback-state scope authorized in order to control playback
     *
     * Query parameter:
     * - required
     *      - uri(string): The uri of the item to add to the queue. Must be a track or an episode uri EG. "spotify:track:1RlnzIazwomBpkg7vHVs2s".
     * - optional
     *      - device_id(string): The id of the device this command is targeting. If not supplied, the user’s currently active device is the target.
     *
     * Response:
     *
     * A completed request will return a 204 NO CONTENT response code, and then issue the command to the player.
     * Due to the asynchronous nature of the issuance of the command, you should use the Get Information About The User’s Current Playback endpoint to check that your issued command was handled correctly by the player.
     * If the device is not found, the request will return 404 NOT FOUND response code.
     * If the user making the request is non-premium, a 403 FORBIDDEN response code will be returned.
     *
     * @param array $options (optional) Request parameters
     * @throws GuzzleException
     * @return string|null
     */
    public function addToQueue(array $options = []): ?string
    {
        return $this->delegate("POST", SdkConstants::PLAYER . "/queue", $options);
    }

}
