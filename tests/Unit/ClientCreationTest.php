<?php


namespace Gjoni\SpotifyWebApiSdk\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Config\SecretsCollection;
use Gjoni\SpotifyWebApiSdk\Interfaces\SdkInterface;
use Gjoni\SpotifyWebApiSdk\Sdk;
use Gjoni\SpotifyWebApiSdk\Http\Client;

class ClientCreationTest extends TestCase
{
    /**
     * @var SdkInterface $sdk
     */
    protected SdkInterface $sdk;

    /**
     * @var Client $client
     */
    protected Client $client;

    public function testCreateClientInstance()
    {
        $this->sdk = new Sdk(SecretsCollection::$appClientId, SecretsCollection::$appClientSecret, [
            "user-read-private",
        ]);

        $this->sdk
            ->setAccessToken($_COOKIE["access_token"] ?? '')
            ->setRefreshToken($_COOKIE["refresh_token"] ?? '');

        $this->client = new Client($this->sdk, [
            "base_uri" => SecretsCollection::$apiUri,
            "timeout" => 1,
        ]);

        $this->assertIsObject($this->client);
    }
}
