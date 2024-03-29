<?php


namespace Gjoni\SpotifyWebApiSdk\Http;

use function array_merge;
use function sprintf;
use function http_build_query;
use function json_decode;
use Gjoni\SpotifyWebApiSdk\Interfaces\SdkInterface;
use Gjoni\SpotifyWebApiSdk\SdkConstants;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Gjoni\SpotifyWebApiSdk\Exception\AccessTokenExpiredException;

/**
 * Main request class, handling every request for every API, providing a reusable delegate method.
 *
 * @package SpotifyAPI\Http
 * @author Reard Gjoni <gjoni-r@hotmail.com>
 */
class Client
{
    protected GuzzleClient $client;

    /**
     * @var Response $response Response object.
     */
    protected Response $response;

    /**
     * @var array|null $data The response data.
     */
    protected ?array $data = [];

    /**
     * @var int $statusCode The response status code.
     */
    protected int $statusCode = 200;

    /**
     * @var SdkInterface $sdk Sdk object.
     */
    protected SdkInterface $sdk;

    /**
     * Client constructor.
     *
     * Initializes the Sdk object, Guzzle Client and the Response object.
     *
     * @param SdkInterface $sdk
     * @param string $url
     */
    public function __construct (SdkInterface $sdk, string $url = SdkConstants::API_URL)
    {
        $this->sdk = $sdk;

        $this->response = new Response();

        $options = [
            "base_uri" => $url,
            "timeout" => 2
        ];

        $this->client = new GuzzleClient($options);
    }

    /**
     * Base request method for all API resources. Sets base headers such as the access token, content type and accept type.
     *
     * @param string $method The Http method
     * @param string $path The request path
     * @param array $options The request parameters. <br>
     * Use <b>$options['query_params']</b> for optional query parameters such as limit, offset etc.
     * @return string|null Json response
     * @throws GuzzleException
     * @throws AccessTokenExpiredException
     */
    public function delegate(string $method,
                             string $path,
                             array $options): ?string
    {
        if (empty($this->sdk->getAccessToken())) {
            throw new AccessTokenExpiredException();
        }

        $accessToken = $this->sdk->getAccessToken();

        if (!isset($options["headers"])) {
            $options["headers"] = [];
        }

        # Set default headers for a typical user request. Includes the access token
        # Mix with possibly existing values
        $options["headers"] = array_merge($options["headers"], [
            "Accept" => $options["headers"]["accept"] ?? "application/json",
            "Content-Type" => $options["headers"]["content_type"] ?? "application/json" ,
            "Authorization" => sprintf("Bearer %s",  $accessToken)
        ]);

        # Take care of optional query parameters
        if (isset($options["query_params"])) {
            $params = http_build_query($options["query_params"], "", "&",  PHP_QUERY_RFC3986);

            $path .= "/?" . $params;
            unset($options["query_params"]);
        }

        try {
            $request = $this->client->request($method, $path, $options);

            if ($request->getStatusCode() === 204) {
                $this->response->setHeader(204);
                return null;
            }

            $body = $request->getBody();
            $body = json_decode($body, true);

            $this->data = $body;
            $this->statusCode = $request->getStatusCode();
        } catch (RequestException $exception) {
            throw $exception;
        }

        return $this->response->json(
            new Output($this->data),
            $this->statusCode
        );
    }
}
