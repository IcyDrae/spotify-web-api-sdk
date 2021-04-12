<?php


namespace Gjoni\SpotifyWebApiSdk\Interfaces;

/**
 * Main contract for initializing the SDK. Responsible for secret/token handling and will be the main object being passed
 * into each entity.
 *
 * @package Gjoni\SpotifyWebApiSdk\Interfaces
 * @author Reard Gjoni <gjoni-r@hotmail.com>
 */
interface SdkInterface
{
    /**
     * Returns the scopes as a space-separated string.
     *
     * @return string
     */
    public function getScopes(): string;

    /**
     * Returns the client id.
     *
     * @return string
     */
    public function getClientId(): string;

    /**
     * Returns the client secret.
     *
     * @return string
     */
    public function getClientSecret(): string;

    /**
     * Sets the access token.
     *
     * @param string $accessToken A short-lived token used to authorize the app to consume the Spotify Web API on behalf of a user
     * @return SdkInterface
     */
    public function setAccessToken(string $accessToken): SdkInterface;

    /**
     * Returns the access token.
     *
     * @return string
     */
    public function getAccessToken(): string;

    /**
     * Sets the refresh token.
     *
     * @param string $refreshToken A long-lived refresh token used to fetch a new access token
     * @return SdkInterface
     */
    public function setRefreshToken(string $refreshToken): SdkInterface;

    /**
     * Gets the refresh token.
     *
     * @return string
     */
    public function getRefreshToken(): string;

}
