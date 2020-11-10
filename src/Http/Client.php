<?php


namespace SpotifyAPI\Http;

use Config\SecretsCollection;
use GuzzleHttp\Client as GuzzleClient;

/**
 * Class Client
 * Serves as a boilerplate with client and request parameters for our specific needs
 */
class Client extends GuzzleClient
{
    /**
     * @var string $baseUri
     */
    private $baseUri;

    /**
     * @var string $timeout
     */
    private $timeout;

    /**
     * @var string $httpMethod
     */
    private $httpMethod;

    /**
     * @var string $url
     */
    private $url;

    /**
     * @var array $parametersArray
     */
    private $parametersArray;

    /**
     * @var array $configArray
     */
    public array $configArray;

    /**
     * @var array $authParams
     */
    private $authParams;

    /**
     * @var array $accessParams
     */
    private $accessParams;

    /**
     * @var array $getParams
     */
    private $getParams;

    /**
     * @var string $authCode
     */
    private $authCode;

    /**
     * @var array $allowRedirects
     */
    private array $allowRedirects;

    /**
     * Client constructor.
     * @param string $baseUri
     * @param integer $timeout
     * @param array $allowRedirects
     */
    public function __construct (string $baseUri, int $timeout, array $allowRedirects = [])
    {
        $this->setConfigs();

        $this->baseUri = $baseUri;
        $this->timeout = $timeout;
        $this->allowRedirects = $allowRedirects;

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
        $this->configArray = [
            "client_id" => SecretsCollection::$id,
            "response_type" => "code",
            "redirect_uri" => "http://frontend.spotify-auth.com:1024",
            "scope" => "user-read-private user-read-email playlist-read-private", // add other scopes
            "grant_type" => "authorization_code",
            "code" => $this->authCode,
            "headers" => [
                "accept" => "application/json",
                "content_type" => "application/json",
                "authorization" => sprintf("Bearer %s",  SecretsCollection::$tokens["access_token"]),
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
    public function getConfigArray()
    {
        if (isset($_GET["code"])) {
            $this->authCode = $_GET["code"];
        }

        $this->configArray = [
            "client_id" => SecretsCollection::$id,
            "response_type" => "code",
            "redirect_uri" => "http://frontend.spotify-auth.com:1024",
            "scope" => "user-read-private user-read-email playlist-read-private", // add other scopes
            "request_headers" => [
                "accept" => "application/json",
                "content-type" => "application/json",
                "authorization_access" => sprintf("Basic %s", base64_encode(SecretsCollection::$id . ":" . SecretsCollection::$secret)),
            ],
            "form_params" => [
                "grant_type" => "authorization_code",
                "code" => $this->authCode,
            ],
        ];
        return $this->configArray;
    }

    /**
     * @return array
     */
    public function getAuthParams() {
        $this->authParams = [
            "query" => [
                "response_type" => $this->getConfigArray()["response_type"],
                "client_id" => $this->getConfigArray()["client_id"],
                "redirect_uri" => $this->getConfigArray()["redirect_uri"],
                "scope" => $this->getConfigArray()["scope"],
            ],
        ];

        return $this->authParams;
    }

    /**
     * @return array
     */
    public function getAccessParams() {
        $this->accessParams = [
            "headers" => [
                "Authorization" => $this->getConfigArray()["request_headers"]["authorization_access"],
            ],
            "form_params" => [
                "grant_type" => $this->getConfigArray()["form_params"]["grant_type"],
                "code" => $this->getConfigArray()["form_params"]["code"],
                "redirect_uri" => $this->getConfigArray()["redirect_uri"],
            ],
        ];

        return $this->accessParams;
    }

    /**
     * @return array
     */
    public function getParams() {
        $this->getParams = [
            "headers" => [
                "Accept" => "application/json",
                "Content-Type" => "application/json",
                "Authorization" => sprintf("Bearer %s",  $this->tokens["access_token"]),
            ],
            "query" => [
                "limit" => 25,
            ],
        ];

        return $this->getParams;
    }
}
