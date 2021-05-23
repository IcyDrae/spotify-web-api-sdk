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
     * Output constructor.
     *
     * @param array|null $data
     */
    public function __construct(?array $data)
    {
        $this->data = $data;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return [
            "data" => $this->data,
        ];
    }
}
