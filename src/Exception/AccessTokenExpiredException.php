<?php


namespace Gjoni\SpotifyWebApiSdk\Exception;

use Throwable;

class AccessTokenExpiredException extends Exception
{

    public function __construct($message = "The access token has expired, please refresh the token.", $code = 401, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}
