<?php


namespace SpotifyAPI\Http\Controllers;

use DateTime;
use SpotifyAPI\Http\Cookie;
use SpotifyAPI\Http\Response;

class TestController
{
    public function __construct()
    {
        $response = new Response();
        $response->headers();
    }

    public function home()
    {
        $cookieConfig = [
            "data" => [],
            "path" => "/",
            "secure" => true,
            "httpOnly" => true,
            "sameSite" => "None",
            "domain" => "spotify-auth.com"
        ];

        $response = new Response();
        $response->headers();
    }

    public function customCookie()
    {
        $cookieConfig = [
            "data" => [],
            "path" => "/",
            "secure" => true,
            "httpOnly" => true,
            "sameSite" => "None",
            "domain" => "spotify-auth.com"
        ];

        $cookie = new Cookie($cookieConfig, 3600);
        $cookie->access_token = "test_access";
        $cookie_2 = new Cookie($cookieConfig);
        $cookie_2->refresh_token = "test_refresh";
    }

}
