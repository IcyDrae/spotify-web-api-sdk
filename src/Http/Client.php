<?php


namespace SpotifyAPI\Http;

use Config\SecretsCollection;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Message;
use Psr\Http\Message\ResponseInterface;

/**
 * Class Client
 * @package SpotifyAPI\Http
 * @author Reard Gjoni <gjoni-r@hotmail.com>
 *
 * Serves as a boilerplate with client and request parameters for our specific needs
 */
class Client extends GuzzleClient
{
    /**
     * @var array $configs
     */
    public array $configs;

    /**
     * @var array $headers
     */
    public array $headers;

    /**
     * @var Response $response
     */
    private Response $response;

    /**
     * Client constructor.
     *
     * Initializes the Guzzle Client, configs, headers and the response object
     *
     * @param array $options
     */
    public function __construct (array $options = [])
    {
        $this->setConfigs();
        $this->setHeaders(getallheaders());
        $this->setResponse(new Response());

        parent::__construct($options);
    }

    /**
     * Initializes a config blueprint to be used in most of the requests
     *
     * @return $this
     */
    private function setConfigs(): Client
    {
        $this->configs = [
            "client_id" => SecretsCollection::$id,
            "response_type" => "code",
            "redirect_uri" => SecretsCollection::$frontend,
            "scope" => "user-read-private user-read-email playlist-read-private", // add other scopes
            "grant_type" => "authorization_code",
            "headers" => [
                "accept" => "application/json",
                "content_type_json" => "application/json",
                "content_type_urlencoded" => "application/x-www-form-urlencoded",
                "authorization_access" => sprintf("Basic %s", base64_encode(SecretsCollection::$id . ":" . SecretsCollection::$secret)),
            ],
            "query" => [
                "limit" => 25,
            ],
        ];

        return $this;
    }

    /**
     * @return array
     */
    public function getConfigs(): array
    {
        return $this->configs;
    }

    /**
     * @param array $headers
     * @return $this
     */
    private function setHeaders(array $headers): Client
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @param Response $response
     * @return $this
     */
    private function setResponse(Response $response): Client
    {
        $this->response = $response;

        return $this;
    }

    /**
     * @return Response
     */
    public function getResponse(): Response
    {
        return $this->response;
    }

    /**
     * This method is built to be as reusable as possible, used as a base request method for user resources.
     *
     * @param $method
     * @param $uri
     * @param array $options
     * @return ResponseInterface|string
     * @throws GuzzleException
     */
    public function fetch($method,
                          $uri,
                          array $options = []) {
        $response = $this->getResponse();
        $accessToken = $this->headers["Access-Token"] ?? '';

        # Set default headers for a typical user request. Includes the access token
        if (empty($options["headers"])) {
            $options["headers"] = [
                "Accept" => $this->configs["headers"]["accept"],
                "Content-Type" => $this->configs["headers"]["content_type_json"],
                "Authorization" => sprintf("Bearer %s",  $accessToken)
            ];
        }

        try {
            $request = parent::request($method, $uri, $options);

            $body = $request->getBody();
            $body = json_decode($body);

            return $response->json([
                "data" => [
                    "body" => $body
                ]
            ]);
        } catch (RequestException $exception) {
            if ($exception->hasResponse()) {
                $request = Message::toString($exception->getRequest());
            }

            return $this->response->json([
                "error" => $exception->getMessage(),
                "request" => $request ?? ''
            ], $exception->getCode());
        }
    }
}
