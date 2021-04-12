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

    public function testCreateSdkInstance()
    {
        $sdk = new Sdk(SecretsCollection::$appClientId, SecretsCollection::$appClientSecret, [
            "user-read-private",
        ]);

        $this->assertIsObject($sdk);
        $this->assertIsString($sdk->getClientId());
        $this->assertIsString($sdk->getClientSecret());
        $this->assertIsString($sdk->getScopes());
    }

    public function testSetAccessToken()
    {
        $sdk = new Sdk(SecretsCollection::$appClientId, SecretsCollection::$appClientSecret, [
            "user-read-private",
        ]);

        $sdk->setAccessToken("some-access-token");

        $accessToken = $sdk->getAccessToken();

        $this->assertIsString($accessToken);
    }

    public function testSetRefreshToken()
    {
        $sdk = new Sdk(SecretsCollection::$appClientId, SecretsCollection::$appClientSecret, [
            "user-read-private",
        ]);

        $sdk->setRefreshToken("some-refresh-token");

        $refreshToken = $sdk->getRefreshToken();

        $this->assertIsString($refreshToken);
    }
}
