<?php


namespace Gjoni\SpotifyWebApiSdk;

use Gjoni\SpotifyWebApiSdk\Http\Client;
use Gjoni\SpotifyWebApiSdk\Interfaces\SdkInterface;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Responsible for communicating with the Tracks API.
 *
 * @package Gjoni\SpotifyWebApiSdk
 * @author Reard Gjoni <gjoni-r@hotmail.com>
 * @link https://developer.spotify.com/documentation/web-api/reference/#category-tracks
 */
class Tracks
{
    /**
     * @var Client $client Custom client object
     */
    private Client $client;

    /**
     * Tracks constructor.
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
     * Get Spotify catalog information for multiple tracks based on their Spotify IDs.
     *
     * Header:
     * - required
     *      - Authorization(string): A valid access token from the Spotify Accounts service: see the Web API Authorization Guide for details.
     *
     * Query parameter:
     * - required
     *      - ids(string): A comma-separated list of the Spotify IDs for the tracks. Maximum: 50 IDs.
     * - optional
     *      - market(string): An ISO 3166-1 alpha-2 country code or the string from_token. Provide this parameter if you want to apply Track Relinking.
     *
     * Response:
     *
     * On success, the HTTP status code in the response header is 200 OK and the response body contains an object whose key is tracks and whose value is an array of track objects in JSON format.
     * Objects are returned in the order requested. If an object is not found, a null value is returned in the appropriate position.
     * Duplicate ids in the query will result in duplicate objects in the response. On error, the header status code is an error code and the response body contains an error object.
     *
     * @param array $options (optional) Request parameters
     * @throws GuzzleException
     * @return string
     */
    public function getMultiple(array $options = []): string
    {
        return $this->client->delegate("GET", SdkConstants::TRACKS, $options);
    }

    /**
     * Get Spotify catalog information for a single track identified by its unique Spotify ID.
     *
     * Header:
     * - required
     *      - Authorization(string): A valid access token from the Spotify Accounts service: see the Web API Authorization Guide for details.
     *
     * Path parameter:
     * - required
     *      - {id}(string): The Spotify ID for the track.
     *
     * Query parameter:
     * - optional
     *      - market(string): An ISO 3166-1 alpha-2 country code or the string from_token. Provide this parameter if you want to apply Track Relinking.
     *
     * Response:
     *
     * On success, the HTTP status code in the response header is 200 OK and the response body contains a track object in JSON format.
     * On error, the header status code is an error code and the response body contains an error object.
     *
     * @param string $id The track id
     * @param array $options (optional) Request parameters
     * @return string
     *@throws GuzzleException
     */
    public function getSingle(string $id, array $options = []): string
    {
        return $this->client->delegate("GET", SdkConstants::TRACKS . "/$id", $options);
    }

    /**
     * Get audio features for multiple tracks based on their Spotify IDs.
     *
     * Header:
     * - required
     *      - Authorization(string): A valid access token from the Spotify Accounts service: see the Web API Authorization Guide for details.
     *
     * Query parameter:
     * - required
     *      - ids(string): A comma-separated list of the Spotify IDs for the tracks. Maximum: 100 IDs.
     *
     * Response:
     *
     * On success, the HTTP status code in the response header is 200 OK and the response body contains an object whose key is "audio_features" and whose value is an array of audio features objects in JSON format.
     * Objects are returned in the order requested. If an object is not found, a null value is returned in the appropriate position.
     * Duplicate ids in the query will result in duplicate objects in the response.
     * On error, the header status code is an error code and the response body contains an error object.
     *
     * @param array $options (optional) Request parameters
     * @throws GuzzleException
     * @return string
     */
    public function getAudioFeaturesForMultiple(array $options = []): string
    {
        return $this->client->delegate("GET", SdkConstants::API_VERSION . "/audio-features", $options);
    }

    /**
     * Get audio feature information for a single track identified by its unique Spotify ID.
     *
     * Header:
     * - required
     *      - Authorization(string): A valid access token from the Spotify Accounts service: see the Web API Authorization Guide for details.
     *
     * Path parameter:
     * - required
     *      - {id}(string): The Spotify ID for the track.
     *
     * Response:
     *
     * On success, the HTTP status code in the response header is 200 OK and the response body contains an audio features object in JSON format.
     * On error, the header status code is an error code and the response body contains an error object.
     *
     * @param string $id The track id
     * @param array $options (optional) Request parameters
     * @return string
     * @throws GuzzleException
     */
    public function getAudioFeaturesForSingle(string $id, array $options = []): string
    {
        return $this->client->delegate("GET", SdkConstants::API_VERSION . "/audio-features/$id", $options);
    }

    /**
     * Get a detailed audio analysis for a single track identified by its unique Spotify ID.
     *
     * Header:
     * - required
     *      - Authorization(string): A valid access token from the Spotify Accounts service: see the Web API Authorization Guide for details.
     *
     * Path parameter:
     * - required
     *      - {id}(string): The Spotify ID for the track.
     *
     * Response:
     *
     * On success, the HTTP status code in the response header is 200 OK and the response body contains an audio analysis object in JSON format.
     * On error, the header status code is an error code and the response body contains an error object.
     *
     * @param string $id The track id
     * @param array $options (optional) Request parameters
     * @return string
     * @throws GuzzleException
     */
    public function getAudioAnalysisForSingle(string $id, array $options = []): string
    {
        return $this->client->delegate("GET", SdkConstants::API_VERSION . "/audio-analysis/$id", $options);
    }

}
