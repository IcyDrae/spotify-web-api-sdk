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
     * The current Spotify API version, prefixing all endpoints.
     */
    const API_VERSION = "/v1";

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
    const ME = self::API_VERSION . "/me";

    /**
     * Reflects another users' resources.
     */
    const USERS = self::API_VERSION . "/users";

    /**
     * The public playlists endpoint.
     */
    const PLAYLISTS = self::API_VERSION . "/playlists";

    /**
     * The artists endpoint.
     */
    const ARTISTS = self::API_VERSION . "/artists";

    /**
     * The browse endpoint.
     */
    const BROWSE = self::API_VERSION . "/browse";

    /**
     * The albums endpoint.
     */
    const ALBUMS = self::API_VERSION . "/albums";

    /**
     * The episodes endpoint.
     */
    const EPISODES = self::API_VERSION . "/episodes";

    /**
     * The Library endpoints, containing the albums, tracks and shows respectively.
     */
    const LIBRARY = [
        "ALBUMS" => self::ME . "/albums",
        "TRACKS" => self::ME . "/tracks",
        "EPISODES" => self::ME . "/episodes",
        "SHOWS" => self::ME . "/shows"
    ];

    /**
     * SdkConstants constructor.
     *
     * @throws Exception When instantiated, as it should only be read-only.
     */
    public function __construct()
    {
        throw new Exception("Cannot instantiate this class, it must only be used to read the constants.");
    }
}
