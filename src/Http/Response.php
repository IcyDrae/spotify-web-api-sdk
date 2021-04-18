<?php


namespace Gjoni\SpotifyWebApiSdk\Http;

/**
 * Used to convert an output to JSON & to set a custom HTTP header.
 *
 * @package SpotifyAPI\Http
 * @author Reard Gjoni
 */
class Response
{
    /**
     * Sets a custom header to the response.
     *
     * @param int $statusCode The status code
     * @param string $header The header string, defaults at application/json
     * @return void
     */
    public function setHeader(int $statusCode, string $header = "Content-Type: application/json; charset=utf-8")
    {
        header($header, true, $statusCode);
    }

    /**
     * Serializes a given array into JSON and prints it to the client.
     *
     * @param Output $data Content being converted
     * @param int $statusCode Status code
     * @return string
     */
    public function json(Output $data, int $statusCode = 200): string {
        $this->setHeader($statusCode);

        $data = json_encode($data);

        echo $data;

        return $data;
    }
}
