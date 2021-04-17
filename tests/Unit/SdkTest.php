<?php


namespace Gjoni\SpotifyWebApiSdk\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Gjoni\SpotifyWebApiSdk\Sdk;

class SdkTest extends TestCase
{
    /**
     * Fake secrets
     */
    private string $clientId = "ZYDPLLBWSK3MVQJSIYHB1OR2JXCY0X2C5UJ2QAR2MAAIT5Q";
    private string $clientSecret = "YHB1OR2JXCY0X2C5UJ2QAR2MAAIT5Q";
    private array $scopes = [
        "user-read-private",
        "user-follow-modify",
        "some-other-scope"
    ];

    public function testCreateSdkInstance()
    {
        $sdk = new Sdk($this->clientId, $this->clientSecret, $this->scopes);

        $this->assertIsObject($sdk);
        $this->assertIsString($sdk->getClientId());
        $this->assertIsString($sdk->getClientSecret());
        $this->assertIsString($sdk->getScopes());

        # Test the format of the scopes as described in the SdkInterface
        $this->assertEquals(
            "user-read-private user-follow-modify some-other-scope",
            $sdk->getScopes(),
            "Scopes format is not a space separated string!"
        );
    }

    public function testSetAccessToken()
    {
        $sdk = new Sdk($this->clientId, $this->clientSecret, $this->scopes);

        $sdk->setAccessToken("some-access-token");

        $accessToken = $sdk->getAccessToken();

        $this->assertIsString($accessToken);
    }

    public function testSetRefreshToken()
    {
        $sdk = new Sdk($this->clientId, $this->clientSecret, $this->scopes);

        $sdk->setRefreshToken("some-refresh-token");

        $refreshToken = $sdk->getRefreshToken();

        $this->assertIsString($refreshToken);
    }
}
