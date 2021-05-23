<?php


namespace Gjoni\SpotifyWebApiSdk\Tests\Unit;

use function getFixture;
use Gjoni\SpotifyWebApiSdk\UsersProfile;
use Gjoni\SpotifyWebApiSdk\Interfaces\SdkInterface;
use Gjoni\SpotifyWebApiSdk\Sdk;
use PHPUnit\Framework\TestCase;

class UsersProfileTest extends TestCase
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

    private function setUpMockObject(string $methodName, string $returnValue)
    {
        $mock = $this
            ->getMockBuilder(UsersProfile::class)
            ->setConstructorArgs([$this->sdk])
            ->onlyMethods([$methodName])
            ->getMock();

        $mock
            ->expects($this->once())
            ->method($methodName)
            ->withAnyParameters()
            ->willReturn($returnValue);

        return $mock;
    }

    public function testCreateUsersProfileInstance()
    {
        $usersProfile = new UsersProfile($this->sdk);

        $this->assertIsObject($usersProfile);
    }

    public function testMe()
    {
        $response = getFixture("usersProfile-me");
        $returnValue = $response;
        $returnValueDecoded = json_decode($returnValue, true);

        $userProfile = $this->setUpMockObject(
            "me",
            $returnValue);

        $this->assertEquals(
            $returnValue,
            $userProfile->me([])
        );

        self::assertJson($returnValue);

        $this->assertIsArray($returnValueDecoded);
        $this->assertIsString($returnValueDecoded["display_name"]);
    }

    public function testGetUserProfile()
    {
        $response = getFixture("usersProfile-get-user-profile");
        $returnValue = $response;
        $returnValueDecoded = json_decode($returnValue, true);

        $userProfile = $this->setUpMockObject(
            "getUserProfile",
            $returnValue);

        $this->assertEquals(
            $returnValue,
            $userProfile->getUserProfile("ray", [])
        );

        self::assertJson($returnValue);

        $this->assertIsArray($returnValueDecoded);
        $this->assertIsString($returnValueDecoded["display_name"]);
    }
}
