<?php


namespace Gjoni\SpotifyWebApiSdk\Authorization;

use function random_bytes;
use function bin2hex;
use function hash;
use function base64_encode;
use function strtr;
use function rtrim;
use Exception;

/**
 * Authorization Code Flow with Proof Key for Code Exchange (PKCE) class.
 *
 * @package Gjoni\SpotifyWebApiSdk\Authorization
 * @link https://developer.spotify.com/documentation/general/guides/authorization-guide/#authorization-code-flow-with-proof-key-for-code-exchange-pkce
 * @author Reard Gjoni <gjoni-r@hotmail.com>
 */
class AuthorizationCodePKCE extends AbstractAuthorization
{

    /**
     * @var string The code verifier.
     */
    private string $codeVerifier;

    /**
     * @var string The code challenge.
     */
    private string $codeChallenge;

    /**
     * Code verifier getter.
     *
     * @return string
     */
    public function getCodeVerifier(): string
    {
        return $this->codeVerifier;
    }

    /**
     * Code verifier setter.
     *
     * @param string $codeVerifier
     */
    public function setCodeVerifier(string $codeVerifier): void
    {
        $this->codeVerifier = $codeVerifier;
    }

    /**
     * Code challenge getter.
     *
     * @return string
     */
    public function getCodeChallenge(): string
    {
        return $this->codeChallenge;
    }

    /**
     * Code challenge setter.
     *
     * @param string $codeChallenge
     */
    public function setCodeChallenge(string $codeChallenge): void
    {
        $this->codeChallenge = $codeChallenge;
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    protected function getOptionsAccess(): array
    {
        return [
            "form_params" => [
                "client_id" => $this->sdk->getClientId(),
                "grant_type" => "authorization_code",
                "code_verifier" => $this->getCodeVerifier()
            ]
        ];
    }

    /**
     * @inheritDoc
     */
    protected function getOptionsRefresh(): array
    {
        return [
            "form_params" => [
                "client_id" => $this->sdk->getClientId()
            ]
        ];
    }

    /**
     * @inheritDoc
     */
    protected function getUrlParams(): array
    {
        return [
            "code_challenge_method" => "S256",
            "code_challenge" => $this->getCodeChallenge()
        ];
    }

    /**
     * Creates a high-entropy random string with a minimum length of 43 and maximum length of 128 characters.
     * Default value is 128.
     *
     * @param int $length The length of the to-be-generated verifier.
     * @return string
     * @throws Exception
     */
    public function generateCodeVerifier(int $length = 128): string
    {
        $codeVerifier = random_bytes($length / 2);
        $codeVerifier = bin2hex($codeVerifier);

        return $codeVerifier;
    }

    /**
     * Generates a code challenge based on a given code verifier.
     * The code challenge can either be SHA256 encrypted and base64 URL encoded or plain(the same as the given code verifier).
     *
     * @param string $verifier The needed code verifier.
     * @param string $method The encryption method, defaults at SHA256.
     * @return string
     */
    public function generateCodeChallenge(string $verifier, string $method = "sha256"): string
    {
        if ($method === "plain") {
            return $verifier;
        }

        $challenge = hash($method, $verifier, true);
        $challenge = base64_encode($challenge);
        $challenge = strtr($challenge, "+/", "-_");
        $challenge = rtrim($challenge, "=");

        return $challenge;
    }
}
