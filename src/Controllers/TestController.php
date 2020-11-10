<?php


namespace SpotifyAPI\Controllers;

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
        $output = new Response();

        return $output->json([
            getallheaders()
        ]);
    }

}
