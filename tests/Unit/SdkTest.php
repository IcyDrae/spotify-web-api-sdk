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
    private string $redirectUri = "https://some.uri.com";

    public function testCreateSdkInstance()
    {
        $sdk = new Sdk($this->clientId, $this->clientSecret, $this->scopes, $this->redirectUri);

        $this->assertIsObject($sdk);
        $this->assertIsString($sdk->getClientId());
        $this->assertIsString($sdk->getClientSecret());
        $this->assertIsString($sdk->getScopes());
        $this->assertIsString($sdk->getRedirectUri());

        # Test the format of the scopes as described in the SdkInterface
        $this->assertEquals(
            "user-read-private user-follow-modify some-other-scope",
            $sdk->getScopes(),
            "Scopes format is not a space separated string!"
        );
    }

    public function testSetAccessToken()
    {
        $sdk = new Sdk($this->clientId, $this->clientSecret, $this->scopes, $this->redirectUri);

        $sdk->setAccessToken("some-access-token");

        $accessToken = $sdk->getAccessToken();

        $this->assertIsString($accessToken);
    }

    public function testSetRefreshToken()
    {
        $sdk = new Sdk($this->clientId, $this->clientSecret, $this->scopes, $this->redirectUri);

        $sdk->setRefreshToken("some-refresh-token");

        $refreshToken = $sdk->getRefreshToken();

        $this->assertIsString($refreshToken);
    }
}
