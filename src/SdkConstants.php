<?php


namespace Gjoni\SpotifyWebApiSdk;


use Exception;

/**
 * Class containing all the constants such as urls, endpoints etc.
 *
 * @package Gjoni\SpotifyWebApiSdk
 * @author Reard Gjoni <gjoni-r@hotmail.com>
 */
final class SdkConstants
{
    /**
     * The base API url where the resources reside.
     */
    const API_URL = "https://api.spotify.com";

    /**
     *  The accounts url, used for authorization.
     */
    const ACCOUNTS_URL = "https://accounts.spotify.com";

    /**
     * The current users' url.
     */
    const ME = "/v1/me";

    /**
     * Reflects another users' resources.
     */
    const USERS = "/v1/users";

    /**
     * The public playlists endpoint.
     */
    const PLAYLISTS = "/v1/playlists";

    /**
     * SdkConstants constructor.
     * @throws Exception When instantiated, as it should only be read-only.
     */
    public function __construct()
    {
        throw new Exception("Cannot instantiate this class, it must only be used to read the constants.");
    }
}
