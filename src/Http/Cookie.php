<?php


namespace SpotifyAPI\Http;

// Class that abstracts both the $_COOKIE and setcookie()
use Exception;

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
     * @param array $cookie
     * @param int $expires
     * @param string $domain
     * @param string $path
     * @param bool $secure
     * @param bool $httpOnly
     * @param string $sameSite
     *
     * Default expiration is 28 days (28 * 3600 * 24 = 2419200).
     */
    public function __construct(array $cookie,
                                string $path,
                                bool $secure,
                                bool $httpOnly,
                                string $sameSite,
                                string $domain = "",
                                int $expires = 2419200)
    {
        $this->data = $cookie;
        $this->expires = $expires;
        $this->path = $path;
        $this->secure = $secure;
        $this->httpOnly = $httpOnly;
        $this->sameSite = $sameSite;


        if ($domain) {
            $this->domain = $domain;
        } else {
            $this->domain = (isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST']
                : isset($_SERVER['HTTP_HOST'])) ? $_SERVER['HTTP_HOST']
                : $_SERVER['SERVER_NAME'];
        }
    }

    public function __get($name)
    {
        return (isset($this->data[$name])) ? $this->data[$name] : "";
    }

    public function __set($name, $value = null)
    {
        // Check whether the headers are already sent or not
        if (headers_sent()) {
            throw new Exception("Can't change cookie " . $name . " after sending headers.");
        }

        // Delete the cookie
        if (!$value) {
            setcookie($name, null, [
                "expires" => time() - 10,
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
