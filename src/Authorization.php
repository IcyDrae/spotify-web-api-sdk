<?php


namespace Gjoni\SpotifyWebApiSdk;

use Exception;
use Gjoni\SpotifyWebApiSdk\Interfaces\SdkInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Message;
use Gjoni\SpotifyWebApiSdk\Http\Client;

/**
 * Responsible for
 *      1. generating a request URL where the user will be prompted to give access,
 *      2. requesting access/refresh tokens &
 *      3. refreshing the expired access token in order to stay logged in and not repeat the authorization process
 *
 * @package Gjoni\SpotifyWebApiSdk
 * @author Reard Gjoni <gjoni-r@hotmail.com>
 *
 */
class Authorization extends Client {
    /**
     * @var SdkInterface $sdk Sdk object
     */
    private SdkInterface $sdk;

    /**
     * Authorization constructor.
     *
     * Initializes the SDK object, client, response, headers and client config.
     *
     * @param SdkInterface $sdk An SDK object
     */
    public function __construct(SdkInterface $sdk)
    {
        $this->sdk = $sdk;

        parent::__construct($this->sdk, SdkConstants::ACCOUNTS_URL);
    }

    /**
     * Creates a high-entropy random string with a minimum length of 43 and maximum length of 128 characters.
     *
     * @throws Exception
     * @return string
     */
    public function generateCodeVerifier(): string
    {
        $length = random_int(43, 128);

        $codeVerifier = random_bytes($length);

        return bin2hex($codeVerifier);
    }

    /**
     * Generates a code challenge based on a given code verifier.
     * The code challenge can either be SHA256 encrypted and base64 URL encoded or plain(the same as the given code verifier).
     *
     * @param string $verifier
     * @param string $method
     * @return string
     */
    public function generateCodeChallenge(string $verifier, string $method = "sha256"): string
    {
        if ($method === "plain") {
            return $verifier;
        }

        $challenge = hash($method, $verifier);
        $challenge = base64_encode($challenge);
        $challenge = strtr($challenge, "+/", "-_");
        $challenge = rtrim($challenge, "=");

        return $challenge;
    }

    /**
     * Builds the URL the user will be redirected to for authorization.
     *
     * @return string
     */
    public function buildUrl(): string {
        try {
            $options = [
                "response_type" => $this->parameters["response_type"],
                "client_id" => $this->sdk->getClientId(),
                "redirect_uri" => $this->parameters["redirect_uri"],
                "scope" => $this->sdk->getScopes()
            ];

            $url = SdkConstants::ACCOUNTS_URL . "/authorize?" . http_build_query($options, null, "&");

            return $this->response->json([
                "data" => [
                    "url" => $url,
                ]
            ]);
        } catch (Exception $exception) {
            return $this->response->json([
                "error" => $exception->getMessage()
            ]);
        }
    }

    /**
     * Using an authorization code, will request an access & refresh token.
     *
     * If successful, returns:
     *  1. access token
     *  2. refresh token
     *  3. expiry timestamp for the access token
     *
     * @param string $authCode The recently obtained authorization code.
     * @return string
     */
    public function requestAccessToken(string $authCode): string {
        try {
            $request = $this->post("/api/token", [
                "headers" => [
                    "Authorization" => $this->parameters["headers"]["authorization_access"],
                    "Content-Type" => $this->parameters["headers"]["ctype_urlencoded"]
                ],
                "form_params" => [
                    "grant_type" => $this->parameters["grant_type"],
                    "code" => $authCode,
                    "redirect_uri" => $this->parameters["redirect_uri"],
                ],
            ]);

            $body = $request->getBody();

            $body = json_decode($body, true);
            $timestamp = time() + $body["expires_in"];

            $body["expires_in"] = $timestamp;

            return $this->response->json([
                "data" => [
                    "body" => $body,
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

    /**
     * This method requests a new access token based on the refresh token.
     *
     * @param string $refreshToken The refresh token needed for the exchange.
     * @return string
     */
    public function refreshAccessToken(string $refreshToken): string {
        try {
            $request = $this->post("/api/token", [
                "headers" => [
                    "Authorization" => $this->parameters["headers"]["authorization_access"],
                    "Content-Type" => $this->parameters["headers"]["ctype_urlencoded"]
                ],
                "form_params" => [
                    "grant_type" => "refresh_token",
                    "refresh_token" => $refreshToken
                ],
            ]);

            $body = $request->getBody();

            $body = json_decode($body, true);

            $timestamp = time() + $body["expires_in"];

            $body["expires_in"] = $timestamp;

            return $this->response->json([
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
