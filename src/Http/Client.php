<?php


namespace Gjoni\SpotifyWebApiSdk\Http;

use Gjoni\SpotifyWebApiSdk\Interfaces\SdkInterface;
use Gjoni\SpotifyWebApiSdk\Config\SecretsCollection;
use Gjoni\SpotifyWebApiSdk\SdkConstants;
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
    protected array $parameters;

    /**
     * @var array $headers Request headers
     */
    protected array $headers;

    /**
     * @var Response $response Response object
     */
    protected Response $response;

    /**
     * @var SdkInterface $sdk Sdk object
     */
    private SdkInterface $sdk;

    /**
     * Client constructor.
     *
     * Initializes the Sdk object, Guzzle Client, parameters, headers and the response object.
     *
     * @param SdkInterface $sdk
     * @param string $url
     */
    public function __construct (SdkInterface $sdk, string $url = SdkConstants::API_URL)
    {
        $this->sdk = $sdk;

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

        $this->headers = getallheaders();
        $this->response = new Response();

        $options = [
            "base_uri" => $url,
            "timeout" => 2
        ];

        parent::__construct($options);
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
        if (!$accessToken = $this->sdk->getAccessToken()) {
            return $this->response->json([
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

            return $this->response->json([
                "data" => $body
            ], $request->getStatusCode());
        } catch (RequestException $exception) {
            if ($exception->hasResponse()) {
                $request = Message::toString($exception->getRequest());
            }

            return $this->response->json([
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
