<?php


namespace Gjoni\SpotifyWebApiSdk\Http;

use Gjoni\SpotifyWebApiSdk\Config\SecretsCollection;
use JsonSerializable;

/**
 * Responsible for serializing data into json and setting headers.
 *
 * @package SpotifyAPI\Http
 * @author Reard Gjoni
 */
class Response implements JsonSerializable
{
    /**
     * @var array $data Passed data to be serialized
     */
    private array $data;

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return $this->data;
    }

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
     * Sets predefined headers & handles preflight OPTIONS requests.
     *
     * @return $this
     */
    public function headers(): Response
    {
        if (isset($_SERVER["HTTP_ORIGIN"]) && in_array($_SERVER["HTTP_ORIGIN"], SecretsCollection::ALLOWED_ORIGINS)) {
            header("Access-Control-Allow-Origin: " . $_SERVER["HTTP_ORIGIN"]);
        }

        header("Access-Control-Allow-Credentials: true");

        /**
         * Assuming this is a preflight request, this will need to be called at the top of index.php.
         *
         * Set the necessary headers, exit the sequence and continue with the actual request.
         * If it's not a preflight request, the Router will take care of it.
         */
        if($_SERVER["REQUEST_METHOD"] == "OPTIONS")
        {
            header("Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS");
            header("Access-Control-Allow-Headers: Access-Token, Authorization, Auth-Code, Cookie, Refresh-Token, Content-Type, Test-Vue, Test-Insomnia");

            exit(0);
        }

        return $this;
    }

    /**
     * Serializes a given array into JSON and prints it to the client.
     *
     * @param array $data Content being converted
     * @param int $statusCode Status code
     * @return string
     */
    public function json(array $data, $statusCode = 200): string {
        $this->data = $data;

        $this->setHeader($statusCode);

        $data = json_encode($data);

        echo $data;

        return $data;
    }
}
