<?php


namespace Gjoni\SpotifyWebApiSdk;

use Gjoni\SpotifyWebApiSdk\Interfaces\SdkInterface;
use Gjoni\SpotifyWebApiSdk\Http\Client;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Responsible for communicating with the Users Profile API.
 *
 * @package Gjoni\SpotifyWebApiSdk
 * @author Reard Gjoni <gjoni-r@hotmail.com>
 * @link https://developer.spotify.com/documentation/web-api/reference-beta/#category-users-profile
 */
class UsersProfile extends Client
{
    /**
     * UsersProfile constructor.
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
     * Get detailed profile information about the current user (including the current user’s username).
     *
     * Header:
     * - required
     *      - Authorization(string): A valid access token from the Spotify Accounts service.
     *
     * Response:
     *
     * On success, the HTTP status code in the response header is 200 OK and the response body contains a user object in JSON format.
     * On error, the header status code is an error code and the response body contains an error object.
     * When requesting fields that you don’t have the user’s authorization to access, it will return error 403 Forbidden.
     *
     * Important! If the user-read-email scope is authorized, the returned JSON will include the email address that was
     * entered when the user created their Spotify account. This email address is unverified; do not assume that the email address belongs to the user.
     *
     * @param array $options (optional) Request parameters
     * @throws GuzzleException
     * @return string
     */
    public function me($options = []): string
    {
        return $this->delegate("GET", SdkConstants::ME, $options);
    }

    /**
     * Get public profile information about a Spotify user.
     *
     * Header:
     * - required
     *      - Authorization(string): A valid access token from the Spotify Accounts service.
     *
     * Path Parameter:
     * - required
     *      - {user_id}(string): The user’s Spotify user ID.
     *
     * Response:
     *
     * On success, the HTTP status code in the response header is 200 OK and the response body contains a user object in JSON format.
     *
     * On error, the header status code is an error code and the response body contains an error object.
     * If a user with that user_id doesn't exist, the status code is 404 NOT FOUND.
     *
     * @param string $id The user id
     * @param array $options (optional) Request parameters
     * @throws GuzzleException
     * @return string
     */
    public function getUserProfile(string $id, $options = []): string
    {
        return $this->delegate("GET", SdkConstants::USERS . "/$id", $options);
    }
}
