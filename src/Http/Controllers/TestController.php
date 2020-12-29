<?php


namespace SpotifyAPI\Http\Controllers;

use DateTime;
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

}
