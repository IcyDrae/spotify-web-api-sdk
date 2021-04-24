<?php


namespace Gjoni\SpotifyWebApiSdk;

use Gjoni\SpotifyWebApiSdk\Interfaces\SdkInterface;

/**
 * An implementation of the main Sdk interface.
 *
 * @package Gjoni\SpotifyWebApiSdk
 * @author Reard Gjoni <gjoni-r@hotmail.com>
 */
class Sdk implements SdkInterface
{
    /**
     * @var array $scopes
     */
    private array $scopes;

    /**
     * @var string $clientId
     */
    private string $clientId;

    /**
     * @var string $clientSecret
     */
    private string $clientSecret;

    /**
     * @var string $redirectUri
     */
    private string $redirectUri;

    /**
     * @var string $accessToken
     */
    private string $accessToken;

    /**
     * @var string $refreshToken
     */
    private string $refreshToken;

    /**
     * Sdk constructor.
     *
     * @param string $clientId The client id of the third party app that will use the API
     * @param string $clientSecret Its corresponding secret
     * @param array $scopes The scopes the app needs
     */
    public function __construct(string $clientId, string $clientSecret, array $scopes, $redirectUri)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->scopes = $scopes;
        $this->redirectUri = $redirectUri;
    }

    /**
     * @inheritDoc
     */
    public function getScopes(): string
    {
        return implode(" ", $this->scopes);
    }

    /**
     * @inheritDoc
     */
    public function getClientId(): string
    {
        return $this->clientId;
    }

    /**
     * @inheritDoc
     */
    public function getClientSecret(): string
    {
        return $this->clientSecret;
    }

    /**
     * @inheritDoc
     */
    public function getRedirectUri(): string
    {
        return $this->redirectUri;
    }

    /**
     * @inheritDoc
     */
    public function setAccessToken(string $accessToken): SdkInterface
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    /**
     * @inheritDoc
     */
    public function setRefreshToken(string $refreshToken): SdkInterface
    {
        $this->refreshToken = $refreshToken;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }
}
