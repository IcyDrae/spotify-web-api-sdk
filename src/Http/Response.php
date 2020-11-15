<?php


namespace SpotifyAPI\Http;

use Config\SecretsCollection;
use JsonSerializable;

/**
 * Class Response
 * @package SpotifyAPI\Http
 * @author Reard Gjoni
 *
 * Responsible for serializing data into json and setting headers
 */
class Response implements JsonSerializable
{
    /**
     * @var array $data
     */
    private array $data;

    public function jsonSerialize()
    {
        return $this->data;
    }

    /**
     * Sets basic headers & handles preflight OPTIONS requests
     *
     * @return $this
     */
    public function headers() {
        if (in_array($_SERVER["HTTP_ORIGIN"], SecretsCollection::ALLOWED_ORIGINS)) {
            header("Access-Control-Allow-Origin: " . $_SERVER["HTTP_ORIGIN"]);
        }

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
            header("Access-Control-Allow-Headers: Content-Type, Authorization, Access-Token, Refresh-Token, Auth-Code, Test-Vue, Test-Insomnia");

            exit(0);
        }

        return $this;
    }

    /**
     * Serializes a given array into JSON and prints it to the client
     *
     * @param array $data
     * @param int $statusCode
     * @return string
     */
    public function json(array $data, $statusCode = 200): string {
        $this->data = $data;

        header("Content-Type: application/json", true, $statusCode);

        $data = json_encode($data);

        echo $data;

        return $data;
    }
}
