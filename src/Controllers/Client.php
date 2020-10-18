<?php

namespace SpotifyAPI\Controllers;

#require_once(__DIR__ . "./../../vendor/autoload.php");

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\TransferStats;
use Psr\Http\Message\ResponseInterface;
use SpotifyAPI\Secrets\Secrets;

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
     * @var string $toUrl
     */
    private $toUrl;

    /**
     * @var array $parametersArray
     */
    private $parametersArray;

    /**
     * @var array $configArray
     */
    private $configArray;

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
     * Client constructor.
     * @param string $baseUri
     * @param integer $timeout
     */
    public function __construct ($baseUri, $timeout)
    {
        $this->baseUri = $baseUri;
        $this->timeout = $timeout;

        parent::__construct([
            "base_uri" => $this->baseUri,
            "timeout" => $this->timeout
        ]);
    }

    /**
     * @param string $httpMethod
     * @param string $toUrl
     * @param array $parametersArray
     * @return ResponseInterface
     */
    public function makeRequest($httpMethod, $toUrl, $parametersArray) {
        $this->httpMethod = $httpMethod;
        $this->toUrl = $toUrl;
        $this->parametersArray = $parametersArray;

        return parent::request($this->httpMethod, $this->toUrl, $this->parametersArray);
    }

    /**
     * @return array
     */
    public function getConfigArray()
    {
        if (isset($_GET["code"])) {
            $this->authCode = $_GET["code"];
        }
        $secretsObj = new Secrets;

        $this->configArray = [
            "client_id" => $secretsObj->getId(),
            "response_type" => "code",
            "redirect_uri" => $secretsObj->getHost() . "/callback",
            "scope" => "user-read-private user-read-email playlist-read-private", // add other scopes
            "on_stats_callback" => function (TransferStats $stats) {
                echo $stats->getHandlerStats()["redirect_url"];

                if ($stats->hasResponse()) {
                    return $stats->getResponse();
                }
            },
            "request_headers" => [
                "accept" => "application/json",
                "content-type" => "application/json",
                "authorization_access" => sprintf("Basic %s", base64_encode($secretsObj->getId() . ":" . $secretsObj->getSecret())),
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
            "on_stats" => $this->getConfigArray()["on_stats_callback"],
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
            "on_stats" => $this->getConfigArray()["on_stats_callback"],
        ];

        return $this->getParams;
    }
}