<?php


namespace Gjoni\SpotifyWebApiSdk\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Gjoni\SpotifyWebApiSdk\Sdk;

class SdkCreationTest extends TestCase
{

    public function testCreateSdkInstance()
    {
        $sdk = new Sdk("ZYDPLLBWSK3MVQJSIYHB1OR2JXCY0X2C5UJ2QAR2MAAIT5Q", "YHB1OR2JXCY0X2C5UJ2QAR2MAAIT5Q", [
            "user-read-private",
        ]);

        $this->assertIsObject($sdk);
        $this->assertIsString($sdk->getClientId());
        $this->assertIsString($sdk->getClientSecret());
        $this->assertIsString($sdk->getScopes());
    }

    public function testSetAccessToken()
    {
        $sdk = new Sdk("ZYDPLLBWSK3MVQJSIYHB1OR2JXCY0X2C5UJ2QAR2MAAIT5Q", "YHB1OR2JXCY0X2C5UJ2QAR2MAAIT5Q", [
            "user-read-private",
        ]);

        $sdk->setAccessToken("some-access-token");

        $accessToken = $sdk->getAccessToken();

        $this->assertIsString($accessToken);
    }

    public function testSetRefreshToken()
    {
        $sdk = new Sdk("ZYDPLLBWSK3MVQJSIYHB1OR2JXCY0X2C5UJ2QAR2MAAIT5Q", "YHB1OR2JXCY0X2C5UJ2QAR2MAAIT5Q", [
            "user-read-private",
        ]);

        $sdk->setRefreshToken("some-refresh-token");

        $refreshToken = $sdk->getRefreshToken();

        $this->assertIsString($refreshToken);
    }
}
