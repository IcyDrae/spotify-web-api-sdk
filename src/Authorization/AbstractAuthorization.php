<?php


namespace Gjoni\SpotifyWebApiSdk\Authorization;

use function array_merge_recursive;
use function http_build_query;
use Gjoni\SpotifyWebApiSdk\Http\Client;
use Gjoni\SpotifyWebApiSdk\Http\Output;
use Gjoni\SpotifyWebApiSdk\Interfaces\SdkInterface;
use Gjoni\SpotifyWebApiSdk\SdkConstants;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Message;

/**
 * Abstract authorization class, the core of all auth flows for this SDK, providing reusable methods.
 *
 * @package Gjoni\SpotifyWebApiSdk\Authorization
 * @link https://developer.spotify.com/documentation/general/guides/authorization-guide/
 * @author Reard Gjoni <gjoni-r@hotmail.com>
 */
abstract class AbstractAuthorization extends Client
{

    /**
     * @var array|array[] Default request options for each flow and each request(seek access, refresh).
     */
    protected array $options;

    /**
     * Sets the needed access request options needed for the flow.
     *
     * @return array
     */
    abstract protected function getOptionsAccess(): array;

    /**
     * Sets the needed refresh request options needed for the flow.
     *
     * @return array
     */
    protected function getOptionsRefresh(): array {
        return [];
    }

    /**
     * Sets the needed URL parameters needed for the flow.
     *
     * @return array
     */
    protected function getUrlParams(): array {
        return [];
    }

    /**
     * AbstractAuthorization constructor. Initializes the base request options.
     *
     * @param SdkInterface $sdk
     */
    public function __construct(SdkInterface $sdk)
    {
        parent::__construct($sdk, SdkConstants::ACCOUNTS_URL);

        $this->options = [
            "headers" => [
                "Authorization" => sprintf(
                    "Basic %s",
                    base64_encode($this->sdk->getClientId() . ":" . $this->sdk->getClientSecret())
                ),
                "Content-Type" => "application/x-www-form-urlencoded"
            ]
        ];
    }

    /**
     * Builds the URL the user will be redirected to for authorization.
     *
     * @return string
     */
    public function buildUrl(): string {
        $urlParams = [
            "response_type" => "code",
            "client_id" => $this->sdk->getClientId(),
            "redirect_uri" => $this->sdk->getRedirectUri(),
            "scope" => $this->sdk->getScopes()
        ];

        $urlParams = array_merge_recursive($urlParams, $this->getUrlParams());

        $url = SdkConstants::ACCOUNTS_URL . "/authorize?" . http_build_query($urlParams, null, "&");

        $this->data = [
            "url" => $url
        ];

        return $this->response->json(
            new Output($this->data, $this->error),
        );
    }

    /**
     * Using an authorization code, will request an access & refresh token, which will be used to make API requests.
     *
     * If successful, returns:
     *  1. access token
     *  2. refresh token
     *  3. expiry timestamp for the access token
     *
     * @param string $authCode The recently obtained authorization code.
     * @return string
     */
    public function requestAccessToken(string $authCode = ''): string {
        $options = [];

        /**
         * Empty value since for the client credentials flow there will be no authorization code.
         * That flow simply seeks access.
         */
        if (!empty($authCode)) {
            $options = [
                "form_params" => [
                    "code" => $authCode,
                    "redirect_uri" => $this->sdk->getRedirectUri()
                ]
            ];
        }

        $options = array_merge_recursive($options, $this->options, $this->getOptionsAccess());

        return $this->authorize($options);
    }

    /**
     * This method requests a new access token based on the refresh token.
     *
     * @param string $refreshToken The refresh token needed for the exchange.
     * @return string
     */
    public function refreshAccessToken(string $refreshToken): string {
        $options = [
            "form_params" => [
                "grant_type" => "refresh_token",
                "refresh_token" => $refreshToken
            ]
        ];

        $options = array_merge_recursive($options, $this->options, $this->getOptionsRefresh());

        return $this->authorize($options);
    }

    /**
     * Used as the main authorization method, it is used for each action of each flow.
     *
     * @param array $options
     * @return string
     */
    protected function authorize(array $options): string
    {
        try {
            $request = $this->post("/api/token", $options);

            $body = $request->getBody();

            $body = json_decode($body, true);
            $timestamp = time() + $body["expires_in"];

            $body["expires_in"] = $timestamp;

            $this->data = $body;
            $this->statusCode = $request->getStatusCode();
        } catch (RequestException $exception) {
            if ($exception->hasResponse()) {
                $request = Message::toString($exception->getRequest());
            }

            $this->error = [
                "message" => $exception->getMessage(),
                "request" => $request ?? ''
            ];
            $this->statusCode = $exception->getCode();
        }

        return $this->response->json(
            new Output($this->data, $this->error),
            $this->statusCode
        );
    }
}
