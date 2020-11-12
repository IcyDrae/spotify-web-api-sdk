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
     * @var string $authCode
     */
    private string $authCode;

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
        $this->baseUri = $baseUri;
        $this->timeout = $timeout;
        $this->allowRedirects = $allowRedirects;

        $this->setConfigs();

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
            "code" => $this->authCode,
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
}
