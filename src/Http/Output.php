<?php


namespace Gjoni\SpotifyWebApiSdk\Http;

/**
 * This class defines the format of the JSON output needed for the response.
 *
 * @package SpotifyAPI\Http
 * @author Reard Gjoni
 */
class Output implements \JsonSerializable
{
    /**
     * @var mixed $data Passed data to be serialized.
     */
    private mixed $data;

    /**
     * @var array $error The error representation, if one occurred.
     */
    private array $error;

    /**
     * Output constructor.
     *
     * @param array $data
     * @param array $error
     */
    public function __construct(array $data, array $error)
    {
        $this->data = $data;
        $this->error = $error;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return [
            "data" => $this->data,
            "error" => $this->error
        ];
    }
}
