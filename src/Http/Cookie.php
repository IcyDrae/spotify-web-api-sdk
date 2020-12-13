<?php

namespace SpotifyAPI\Http;

use Exception;

/**
 * Class Cookie
 * @package SpotifyAPI\Http
 * @author Reard Gjoni <gjoni-r@hotmail.com>
 *
 * Abstracts both the $_COOKIE and setcookie()
 */
class Cookie
{
    /**
     * @var array $data The array that stores the cookie
     */
    protected array $data;

    /**
     * @var array $config main cookie configuration array
     */
    protected array $config;

    /**
     * @var int $expires Expiration time from now
     */
    protected int $expires;

    /**
     * @var string
     */
    protected string $domain;

    /**
     * @var string $path
     */
    protected string $path;

    /**
     * @var bool $secure secure flag
     */
    protected bool $secure;

    /**
     * @var bool $httpOnly httpOnly flag
     */
    protected bool $httpOnly;

    /**
     * @var string $sameSite sameSite flag
     */
    protected string $sameSite;

    /**
     * Cookie constructor.
     *
     * Takes either a config array or single values while also considering default values.
     *
     * @param array $config
     * @param array $data
     * @param string $path
     * @param bool $secure
     * @param bool $httpOnly
     * @param string $sameSite
     * @param string $domain
     * @param int $expires
     *
     * Default expiration is 28 days (28 * 3600 * 24 = 2419200).
     */
    public function __construct(array $config,
                                $data = [],
                                $path = '',
                                $secure = false,
                                $httpOnly = false,
                                $sameSite = '',
                                $domain = '',
                                $expires = 2419200)
    {
        $this->data = $config["data"] ?? $data;
        $this->expires = $config["expires"] ?? $expires;
        $this->path = $config["path"] ?? $path;
        $this->secure = $config["secure"] ?? $secure;
        $this->httpOnly = $config["httpOnly"] ?? $httpOnly;
        $this->sameSite = $config["sameSite"] ?? $sameSite;

        if ($config["domain"]) {
            $this->domain = $config["domain"] ?? $domain;
        } else {
            $this->domain = (isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST']
                : isset($_SERVER['HTTP_HOST'])) ? $_SERVER['HTTP_HOST']
                : $_SERVER['SERVER_NAME'];
        }
    }

    public function __get($name): string
    {
        return (isset($this->data[$name])) ? $this->data[$name] : "";
    }

    public function __set($name, $value = null)
    {
        # Check whether the headers are already sent or not
        if (headers_sent()) {
            throw new Exception("Can't change cookie " . $name . " after sending headers.");
        }

        // Delete the cookie
        if (!$value) {
            /**
             * Warning: In order for the cookie to get deleted in Firefox, the browser needs all the previous data to be the same
             * as well as(weirdly) the time to be -1.
             *
             * Also, if no data is sent(which when deleting a cookie is pretty normal) Firefox will trigger the warning:
             * "Cookie 'access_token' has been rejected because it is already expired."
             *
             * All other browsers work fine this way.
             *
             */
            setcookie($name, null, [
                "expires" => -1,
                "domain" => $this->domain,
                "path" => $this->path,
                "secure" => $this->secure,
                "httponly" => $this->httpOnly,
                "samesite" => $this->sameSite
            ]);

            unset($this->data[$name]);
            unset($_COOKIE[$name]);
        } else {
            // Set the actual cookie
            setcookie($name, $value, [
                "expires" => time() + $this->expires,
                "domain" => $this->domain,
                "path" => $this->path,
                "secure" => $this->secure,
                "httponly" => $this->httpOnly,
                "samesite" => $this->sameSite
            ]);

            $this->data[$name] = $value;
            $_COOKIE[$name] = $value;
        }
    }
}
