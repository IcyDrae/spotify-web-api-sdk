<?php


namespace Gjoni\SpotifyWebApiSdk\Authorization;


/**
 * Client Credentials Flow class.
 *
 * @package Gjoni\SpotifyWebApiSdk\Authorization
 * @link https://developer.spotify.com/documentation/general/guides/authorization-guide/#client-credentials-flow
 * @author Reard Gjoni <gjoni-r@hotmail.com>
 */
class ClientCredentials extends AbstractAuthorization
{

    /**
     * @inheritDoc
     */
    protected function getOptionsAccess(): array
    {
        return [
            "form_params" => [
                "grant_type" => "client_credentials"
            ]
        ];
    }

    /**
     * @inheritDoc
     */
    public function buildUrl(): string
    {
        return '';
    }

    /**
     * @inheritDoc
     */
    public function refreshAccessToken(string $refreshToken): string {
        return '';
    }

}
