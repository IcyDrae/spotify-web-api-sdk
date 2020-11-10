<?php


namespace SpotifyAPI\Http;

class Response
{
    public function headers() {
        header("Access-Control-Allow-Origin: http://frontend.spotify-auth.com:1024");

        # *
        #header("Access-Control-Allow-Credentials: true");

        /**
         * Assuming this is a preflight request, this will need to be called at the top of index.php
         * Set the necessary headers, exit the sequence and continue with the actual request
         * If it's not a preflight request, the Router will take care of it
         */
        if($_SERVER["REQUEST_METHOD"] == "OPTIONS")
        {
            header("Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS");
            header("Access-Control-Allow-Headers: Content-Type, Authorization, Test-Vue, Test-Insomnia, Access-Token");

            exit(0);
        }

        return $this;
    }

    public function json(array $data, $statusCode = 200) {
        header("Content-Type: application/json", true, $statusCode);

        $data = json_encode($data);

        echo $data;

        return $this;
    }

}
