<?php


namespace SpotifyAPI\Http;

use Config\SecretsCollection;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\RequestException;

/**
 * Class Client
 * Serves as a boilerplate with client and request parameters for our specific needs
 */
class Client extends GuzzleClient
{
    /**
     * @var string $baseUri
     */
    private string $baseUri;

    /**
     * @var string $timeout
     */
    private $timeout;

    /**
     * @var array $configs
     */
    public array $configs;

    /**
     * @var array $allowRedirects
     */
    private array $allowRedirects;

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
     * @param string $baseUri
     * @param integer $timeout
     * @param array $allowRedirects
     */
    public function __construct (string $baseUri, int $timeout, array $allowRedirects = [])
    {
        $this->baseUri = $baseUri;
        $this->timeout = $timeout;
        $this->allowRedirects = $allowRedirects;

        $this->setConfigs();
        $this->setHeaders(getallheaders());
        $this->setResponse(new Response());

        parent::__construct([
            "base_uri" => $this->baseUri,
            "timeout" => $this->timeout,
            "allow_redirects" => $this->allowRedirects
        ]);
    }

    /**
     * @return $this
     */
    private function setConfigs() {
        $this->configs = [
            "client_id" => SecretsCollection::$id,
            "response_type" => "code",
            "redirect_uri" => "http://frontend.spotify-auth.com:1024",
            "scope" => "user-read-private user-read-email playlist-read-private", // add other scopes
            "grant_type" => "authorization_code",
            "headers" => [
                "accept" => "application/json",
                "content_type" => "application/json",
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
    public function getConfigs()
    {
        return $this->configs;
    }

    /**
     * @param array $headers
     * @return $this
     */
    private function setHeaders(array $headers) {
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
    private function setResponse(Response $response) {
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

    /*public function makeRequest() {
        $output = new Response();
        $reqHeaders = getallheaders();

        try {
            $config = $this->client->getConfigs();

            $request = $this->client->get("/v1/me/playlists", [
                "headers" => [
                    "Accept" => $config["headers"]["accept"],
                    "Content-Type" => $config["headers"]["content_type"],
                    "Authorization" => sprintf("Bearer %s",  $reqHeaders["Access-Token"])
                ]
            ]);

            $body = json_decode($request->getBody());

            return $output->json([
                "body" => $body
            ]);
        } catch (RequestException $exception) {
            echo Psr7\str($exception->getRequest());
            if ($exception->hasResponse()) {
                echo Psr7\str($exception->getResponse());
            }
        }
    }*/
}
