<?php


namespace Gjoni\SpotifyWebApiSdk\Http;

use Gjoni\SpotifyWebApiSdk\Interfaces\SdkInterface;
use Gjoni\SpotifyWebApiSdk\Config\SecretsCollection;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Message;
use Psr\Http\Message\ResponseInterface;

/**
 * Serves as a boilerplate with client and request parameters for our specific needs
 *
 * @package SpotifyAPI\Http
 * @author Reard Gjoni <gjoni-r@hotmail.com>
 */
class Client extends GuzzleClient
{
    /**
     * @var array $parameters Base Guzzle client parameters
     */
    public array $parameters;

    /**
     * @var array $headers Request headers
     */
    public array $headers;

    /**
     * @var Response $response Response object
     */
    private Response $response;

    /**
     * @var SdkInterface $sdk Sdk object
     */
    private SdkInterface $sdk;

    /**
     * Client constructor.
     *
     * Initializes the Sdk object, Guzzle Client, configs, headers and the response object.
     *
     * @param SdkInterface $sdk
     * @param array $options
     */
    public function __construct (SdkInterface $sdk, array $options = [])
    {
        $this->sdk = $sdk;

        $this->setParameters();
        $this->setHeaders(getallheaders());
        $this->setResponse(new Response());

        parent::__construct($options);
    }

    /**
     * Initializes base parameters to be used in most of the requests
     *
     * @return $this
     */
    private function setParameters(): Client
    {
        $this->parameters = [
            "client_id" => $this->sdk->getClientId(),
            "response_type" => "code",
            "redirect_uri" => SecretsCollection::$frontend,
            "scope" => "user-read-private user-read-email playlist-read-private", // add other scopes
            "grant_type" => "authorization_code",
            "headers" => [
                "accept" => "application/json",
                "ctype_json" => "application/json",
                "ctype_urlencoded" => "application/x-www-form-urlencoded",
                "authorization_access" => sprintf("Basic %s", base64_encode($this->sdk->getClientId() . ":" . $this->sdk->getClientSecret())),
            ],
            "query" => [
                "limit" => 25,
            ],
        ];

        return $this;
    }

    /**
     * Returns the parameters array.
     *
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * Sets the request headers.
     *
     * @param array $headers Request headers
     * @return $this
     */
    private function setHeaders(array $headers): Client
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * Returns the request headers.
     *
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Sets the Response object.
     *
     * @param Response $response
     * @return $this
     */
    private function setResponse(Response $response): Client
    {
        $this->response = $response;

        return $this;
    }

    /**
     * Returns the Response object.
     *
     * @return Response
     */
    public function getResponse(): Response
    {
        return $this->response;
    }

    /**
     * Base request method for all API resources. Sets base headers such as the access token, content type and accept type.
     *
     * @param string $method The Http method
     * @param string $path The request path
     * @param array $options The request parameters
     * @return string Json response
     * @throws GuzzleException
     */
    public function fetch(string $method,
                          string $path,
                          array $options = []): string
    {
        $response = $this->getResponse();
        $accessToken = $this->headers["Access-Token"] ?? '';

        if (!isset($options["headers"])) {
            $options["headers"] = [];
        }

        # Set default headers for a typical user request. Includes the access token
        # Mix with possibly existing values
        $options["headers"] = array_merge($options["headers"], [
            "Accept" => $this->parameters["headers"]["accept"],
            "Content-Type" => $this->parameters["headers"]["ctype_json"],
            "Authorization" => sprintf("Bearer %s",  $accessToken)
        ]);

        try {
            $request = parent::request($method, $path, $options);

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
