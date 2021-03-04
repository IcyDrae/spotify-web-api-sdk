<?php


namespace Gjoni\SpotifyWebApiSdk\Http;

use Gjoni\SpotifyWebApiSdk\Interfaces\SdkInterface;
use Gjoni\SpotifyWebApiSdk\Config\SecretsCollection;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Message;

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
     * @param array $options The request parameters. <br>
     * Use <b>$options['query_params']</b> for optional query parameters such as limit, offset etc.
     * @return string|null Json response
     * @throws GuzzleException
     */
    public function delegate(string $method,
                          string $path,
                          array $options): ?string
    {
        $response = $this->getResponse();

        if (!$accessToken = $this->sdk->getAccessToken()) {
            return $response->json([
                "error" => "Access token has expired."
            ]);
        }

        if (!isset($options["headers"])) {
            $options["headers"] = [];
        }

        # Set default headers for a typical user request. Includes the access token
        # Mix with possibly existing values
        $options["headers"] = array_merge($options["headers"], [
            "Accept" => $options["headers"]["accept"] ?? $this->parameters["headers"]["accept"],
            "Content-Type" => $options["headers"]["content_type"] ?? $this->parameters["headers"]["ctype_json"] ,
            "Authorization" => sprintf("Bearer %s",  $accessToken)
        ]);

        # Take care of optional query parameters
        if (isset($options["query_params"])) {
            $params = http_build_query($options["query_params"], "", "&",  PHP_QUERY_RFC3986);

            $path .= "/?" . $params;
            unset($options["query_params"]);
        }

        try {
            $request = parent::request($method, $path, $options);

            if ($request->getStatusCode() === 204) {
                $this->response->setHeader(204);
                return null;
            }

            $body = $request->getBody();
            $body = json_decode($body);

            return $response->json([
                "data" => $body
            ], $request->getStatusCode());
        } catch (RequestException $exception) {
            if ($exception->hasResponse()) {
                $request = Message::toString($exception->getRequest());
            }

            return $response->json([
                "error" => [
                    "message" => $exception->getMessage(),
                    "code" => $exception->getCode(),
                    "request" => $request ?? '',
                    "trace" => $exception->getTrace()
                ]
            ], $exception->getCode());
        }
    }
}
