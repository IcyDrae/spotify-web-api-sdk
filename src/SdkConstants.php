<?php


namespace Gjoni\SpotifyWebApiSdk;


use Exception;

final class SdkConstants
{
    const API_URL = "https://api.spotify.com";

    const ACCOUNTS_URL = "https://accounts.spotify.com";

    const ME = "/v1/me";

    const USERS = "/v1/users";

    public function __construct()
    {
        throw new Exception("Constants only");
    }
}
