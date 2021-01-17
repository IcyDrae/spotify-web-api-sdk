<?php


namespace Gjoni\SpotifyWebApiSdk;

use Gjoni\SpotifyWebApiSdk\Interfaces\SdkInterface;

/**
 * An implementation of the main Sdk interface
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
     * @var string $accessToken
     */
    private string $accessToken;

    /**
     * @var string $refreshToken
     */
    private string $refreshToken;

    /**
     * Sdk constructor.
     * @param string $clientId The client id of the third party app that will use the API
     * @param string $clientSecret It's corresponding secret
     */
    public function __construct(string $clientId, string $clientSecret)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
    }

    /**
     * @inheritDoc
     */
    public function setScopes(array $scopes): SdkInterface
    {
        $this->scopes = $scopes;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getScopes(): array
    {
        return $this->scopes;
    }

    /**
     * @inheritDoc
     */
    public function setClientId(string $id): Sdk
    {
        $this->clientId = $id;

        return $this;
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
    public function setClientSecret(string $secret): Sdk
    {
        $this->clientSecret = $secret;

        return $this;
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
