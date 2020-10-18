<?php

namespace SpotifyAPI\Secrets;

/**
 * Class Secrets
 */
class Secrets {
    /**
     * @var string $id
     */
    private string $id = "";

    /**
     * @var string $secret
     */
    private string $secret = "";

    /**
     * @var string $host
     */
    private string $host = "";

    /**
     * @var string $baseUri
     */
    private string $baseUri = "";

    /**
     * @var string $apiUri
     */
    private string $apiUri = "";


    /**
     * Set the value of id
     *
     * @param string $id
     * @return $this
     */
    public function setId(string $id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Set the value of secret
     *
     * @param string $secret
     * @return $this
     */
    public function setSecret(string $secret)
    {
        $this->secret = $secret;

        return $this;
    }

    /**
     * Set the value of host
     *
     * @param string $host
     * @return $this
     */
    public function setHost(string $host)
    {
        $this->host = $host;

        return $this;
    }

    /**
     * Set the value of baseUri
     *
     * @param string $baseUri
     * @return $this
     */
    public function setBaseUri(string $baseUri)
    {
        $this->baseUri = $baseUri;

        return $this;
    }

    /**
     * Set the value of baseUri
     *
     * @param string $apiUri
     * @return $this
     */
    public function setApiUri(string $apiUri)
    {
        $this->apiUri = $apiUri;

        return $this;
    }

    /**
     * Get the value of id
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Get the value of secret
     * @return string
     */
    public function getSecret(): string
    {
        return $this->secret;
    }

    /**
     * Get the value of host
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * Get the value of baseUri
     * @return string
     */
    public function getBaseUri(): string
    {
        return $this->baseUri;
    }

    /**
     * Get the value of apiUri
     * @return string
     */
    public function getApiUri(): string
    {
        return $this->apiUri;
    }
}