<?php


namespace Gjoni\SpotifyWebApiSdk\Tests\Unit;

use function getFixture;
use Exception;
use Gjoni\SpotifyWebApiSdk\Authorization;
use Gjoni\SpotifyWebApiSdk\Interfaces\SdkInterface;
use Gjoni\SpotifyWebApiSdk\Sdk;
use PHPUnit\Framework\TestCase;

class AuthorizationTest extends TestCase
{
    private SdkInterface $sdk;
    private string $clientId = "ZYDPLLBWSK3MVQJSIYHB1OR2JXCY0X2C5UJ2QAR2MAAIT5Q";
    private string $clientSecret = "54zYUAzCFg22ek7iXjATrb0L5gA7Pjry";
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
            ->getMockBuilder(Authorization::class)
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
        $auth = new Authorization($this->sdk);

        $this->assertIsObject($auth);
    }

    /**
     * @throws Exception
     */
    public function testGenerateCodeVerifier()
    {
        $auth = new Authorization($this->sdk);

        $codeVerifier = $auth->generateCodeVerifier();

        $this->assertIsString($codeVerifier);
    }

    /**
     * @throws Exception
     */
    public function testGenerateCodeChallenge()
    {
        $auth = new Authorization($this->sdk);

        $codeVerifier = $auth->generateCodeVerifier();

        # SHA256 method
        $codeChallengeSha256 = $auth->generateCodeChallenge($codeVerifier);
        $this->assertIsString($codeChallengeSha256, "There was a problem encrypting the verifier!");
        $this->assertNotEquals(
            $codeVerifier,
            $codeChallengeSha256);

        # Plain method
        $codeChallengePlain = $auth->generateCodeChallenge($codeVerifier, "plain");
        $this->assertIsString($codeChallengePlain);
        $this->assertEquals(
            $codeVerifier,
            $codeChallengePlain,
            "The challenge does not equal the verifier, this should not happen!");
    }

    public function testBuildUrlMethod()
    {
        $auth = new Authorization($this->sdk);

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
        $authorizationCode = "AQDvAtkS5S0JmYpxegURsqmQrMOdNBwuDXOb-hLVWa3jxZghLQEDyFU3iyAMA4aUqmzSuPdqycpXGIvaLa2ReKd4z7o0KF7xOlSDe3ZXbsBm_HDPF6xk93dCAwotZklfrbFFdccHAAPAURHlI_ZzBksPE1Ch2L2I4yYGL_weKxEKvBB_Qflh_xDqERr8jolVafmhdkzE_PXQ3Tf7wxWKfJD8GGqNmCiKBfKfnNX1K2spla7rVxqt2yjJbljPXSJdK2u7lETlB7Cdhx915tmE0_U-KW3w3Bh7XGBpMEe29712";

        $response = getFixture("access-token-returned");
        $returnValue = $response;
        $returnValueDecoded = json_decode($returnValue, true);

        $auth = $this->setUpMockObject(
            "requestAccessToken",
            $authorizationCode,
            $returnValue);

        $this->assertEquals(
            $returnValue,
            $auth->requestAccessToken($authorizationCode)
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
        $authorizationCode = "AQDvAtkS5S0JmYpxegURsqmQrMOdNBwuDXOb-hLVWa3jxZghLQEDyFU3iyAMA4aUqmzSuPdqycpXGIvaLa2ReKd4z7o0KF7xOlSDe3ZXbsBm_HDPF6xk93dCAwotZklfrbFFdccHAAPAURHlI_ZzBksPE1Ch2L2I4yYGL_weKxEKvBB_Qflh_xDqERr8jolVafmhdkzE_PXQ3Tf7wxWKfJD8GGqNmCiKBfKfnNX1K2spla7rVxqt2yjJbljPXSJdK2u7lETlB7Cdhx915tmE0_U-KW3w3Bh7XGBpMEe29712";

        $response = getFixture("access-token-refreshed");
        $returnValue = $response;
        $returnValueDecoded = json_decode($returnValue, true);

        $auth = $this->setUpMockObject(
            "refreshAccessToken",
            $authorizationCode,
            $returnValue);

        $this->assertEquals(
            $returnValue,
            $auth->refreshAccessToken($authorizationCode)
        );

        self::assertJson($returnValue);

        $this->assertIsArray($returnValueDecoded);
        $this->assertIsString($returnValueDecoded["access_token"]);
        $this->assertIsString($returnValueDecoded["token_type"]);
        $this->assertIsInt($returnValueDecoded["expires_in"]);
        $this->assertIsString($returnValueDecoded["scope"]);
    }

}
