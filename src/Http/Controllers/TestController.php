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

    public function customHeader()
    {
        if (isset($_COOKIE['access_test_token'])) {
            unset($_COOKIE['access_test_token']);
        }
        date_default_timezone_set(date_default_timezone_get());
        setcookie("access_test_token", "1", [
            "expires" => time() + 3600,
            "domain" => "spotify-auth.com",
            "path" => "/",
            "secure" => true,
            "httponly" => true,
            "samesite" => "None"
        ]);

        /*$cookie = new Cookie([], "/", true, true, "None", "spotify-auth.com", 3600);
        $cookie->access_token = "adasdswd";

        $output = new Response();

        return $output->json([$cookie->access_token]);*/
    }

    public function customCookie()
    {
        /*if (isset($_COOKIE['access_test_token'])) {
            unset($_COOKIE['access_test_token']);
        }

        setcookie("access_test_token", "wwwwwwwwwww", [
            "expires" => time() + 3600 + 3600,
            "domain" => "spotify-auth.com",
            "path" => "/",
            "secure" => true,
            "httponly" => true,
            "samesite" => "None"
        ]);*/
        $cookie = new Cookie($_COOKIE, 7200, "spotify-auth.com");
        $cookie->access_token = "wwwwwwwwwww";

        $output = new Response();

        return $output->json([$cookie->access_token]);
    }

}
