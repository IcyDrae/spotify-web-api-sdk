<?php


namespace Gjoni\SpotifyWebApiSdk\Tests\Unit\Authorization;

use function getFixture;
use Gjoni\SpotifyWebApiSdk\Authorization\AuthorizationCode;
use Gjoni\SpotifyWebApiSdk\Interfaces\SdkInterface;
use Gjoni\SpotifyWebApiSdk\Sdk;
use PHPUnit\Framework\TestCase;

class AuthorizationCodeTest extends TestCase
{
    private SdkInterface $sdk;
    private string $clientId = "ZYDPLLBWSK3MVQJSIYHB1OR2JXCY0X2C5UJ2QAR2MAAIT5Q";
    private string $clientSecret = "54zYUAzCFg22ek7iXjATrb0L5gA7Pjry";
    private string $authorizationCode = "AQDvAtkS5S0JmYpxegURsqmQrMOdNBwuDXOb-hLVWa3jxZghLQEDyFU3iyAMA4aUqmzSuPdqycpXGIvaLa2ReKd4z7o0KF7xOlSDe3ZXbsBm_HDPF6xk93dCAwotZklfrbFFdccHAAPAURHlI_ZzBksPE1Ch2L2I4yYGL_weKxEKvBB_Qflh_xDqERr8jolVafmhdkzE_PXQ3Tf7wxWKfJD8GGqNmCiKBfKfnNX1K2spla7rVxqt2yjJbljPXSJdK2u7lETlB7Cdhx915tmE0_U-KW3w3Bh7XGBpMEe29712";
    private array $scopes = [
        "user-read-private",
        "user-follow-modify",
    ];
    private string $redirectUri = "https://example.com";

    protected function setUp(): void
    {
        $this->sdk = new Sdk(
            $this->clientId,
            $this->clientSecret,
            $this->scopes,
            $this->redirectUri
        );
    }

    private function setUpMockObject(string $methodName, string $methodParam, string $returnValue)
    {
        $mock = $this
            ->getMockBuilder(AuthorizationCode::class)
            ->setConstructorArgs([$this->sdk])
            ->onlyMethods([$methodName])
            ->getMock();

        $mock
            ->expects($this->once())
            ->method($methodName)
            ->with(
                $this->equalTo($methodParam),
            )
            ->willReturn($returnValue);

        return $mock;
    }

    public function testCreateAuthorizationInstance()
    {
        $auth = new AuthorizationCode($this->sdk);

        $this->assertIsObject($auth);
    }

    public function testBuildUrlMethod()
    {
        $auth = new AuthorizationCode($this->sdk);

        $url = $auth->buildUrl();

        $this->assertTrue(
            !empty(
                json_decode($url, true)["data"]["url"]
            ),
            "The url array key was not set."
        );

        $this->assertIsString(
            json_decode($url, true)["data"]["url"],
            "Url building failed!"
        );
    }

    public function testRequestAccessToken()
    {
        $response = getFixture("access-token-returned");
        $returnValue = $response;
        $returnValueDecoded = json_decode($returnValue, true);

        $auth = $this->setUpMockObject(
            "requestAccessToken",
            $this->authorizationCode,
            $returnValue);

        $this->assertEquals(
            $returnValue,
            $auth->requestAccessToken($this->authorizationCode)
        );

        self::assertJson($returnValue);

        $this->assertIsArray($returnValueDecoded);
        $this->assertIsString($returnValueDecoded["access_token"]);
        $this->assertIsString($returnValueDecoded["refresh_token"]);
        $this->assertIsString($returnValueDecoded["token_type"]);
        $this->assertIsInt($returnValueDecoded["expires_in"]);
        $this->assertIsString($returnValueDecoded["scope"]);
    }

    public function testRefreshAccessToken()
    {
        $response = getFixture("access-token-refreshed");
        $returnValue = $response;
        $returnValueDecoded = json_decode($returnValue, true);

        $auth = $this->setUpMockObject(
            "refreshAccessToken",
            $this->authorizationCode,
            $returnValue);

        $this->assertEquals(
            $returnValue,
            $auth->refreshAccessToken($this->authorizationCode)
        );

        self::assertJson($returnValue);

        $this->assertIsArray($returnValueDecoded);
        $this->assertIsString($returnValueDecoded["access_token"]);
        $this->assertIsString($returnValueDecoded["token_type"]);
        $this->assertIsInt($returnValueDecoded["expires_in"]);
        $this->assertIsString($returnValueDecoded["scope"]);
    }

}
