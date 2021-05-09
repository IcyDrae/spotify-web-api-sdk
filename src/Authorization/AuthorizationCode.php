<?php


namespace Gjoni\SpotifyWebApiSdk\Authorization;


/**
 * Authorization Code Flow class.
 *
 * @package Gjoni\SpotifyWebApiSdk\Authorization
 * @link https://developer.spotify.com/documentation/general/guides/authorization-guide/#authorization-code-flow
 * @author Reard Gjoni <gjoni-r@hotmail.com>
 */
class AuthorizationCode extends AbstractAuthorization
{

    /**
     * @inheritDoc
     */
    protected function getOptionsAccess(): array
    {
        return [
            "form_params" => [
                "grant_type" => "authorization_code"
            ]
        ];
    }

}
