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
class Player
{
    /**
     * @var Client $client Custom client object
     */
    private Client $client;

    /**
     * Player constructor.
     *
     * Initializes the client object, response, headers and client parameters.
     *
     * @param SdkInterface $sdk
     */
    public function __construct(SdkInterface $sdk)
    {
        $this->client = new Client($sdk, [
            "base_uri" => SdkConstants::API_URL,
            "timeout" => 2,
            "allow_redirects" => ["track_redirects" => true]
        ]);
    }

    /**
     * Get the current user’s top artists or tracks based on calculated affinity.
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
     * @return string
     */
    public function getCurrentPlayback(array $options = []): string
    {
        return $this->client->delegate("GET", SdkConstants::PLAYER, $options);
    }

}
