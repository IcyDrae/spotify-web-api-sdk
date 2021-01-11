<?php


namespace Gjoni\SpotifyWebApiSdk\Interfaces;

interface SdkInterface
{
    /**
     * Sets the client id
     *
     * @param string $id The id a third party app uses to identify oneself against the Spotify Web API
     * @return SdkInterface
     */
    public function setClientId(string $id): SdkInterface;

    /**
     * Returns the client id
     *
     * @return string
     */
    public function getClientId(): string;

    /**
     * Sets the client secret
     *
     * @param string $secret The secret a third party app uses to identify oneself against the Spotify Web API
     * @return SdkInterface
     */
    public function setClientSecret(string $secret): SdkInterface;

    /**
     * Returns the client secret
     *
     * @return string
     */
    public function getClientSecret(): string;

    /**
     * Sets the access token
     *
     * @param string $accessToken A short-lived token used to authorize the app to consume the Spotify Web API on behalf of a user
     * @return SdkInterface
     */
    public function setAccessToken(string $accessToken): SdkInterface;

    /**
     * Returns the access token
     *
     * @return string
     */
    public function getAccessToken(): string;

    /**
     * Sets the refresh token
     *
     * @param string $refreshToken A long-lived refresh token used to fetch a new access token
     * @return SdkInterface
     */
    public function setRefreshToken(string $refreshToken): SdkInterface;

    /**
     * Gets the refresh token
     *
     * @return string
     */
    public function getRefreshToken(): string;

}
