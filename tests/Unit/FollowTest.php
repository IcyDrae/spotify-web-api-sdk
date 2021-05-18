<?php


namespace Gjoni\SpotifyWebApiSdk\Tests\Unit;

use function getFixture;
use Gjoni\SpotifyWebApiSdk\Follow;
use Gjoni\SpotifyWebApiSdk\Interfaces\SdkInterface;
use Gjoni\SpotifyWebApiSdk\Sdk;
use PHPUnit\Framework\TestCase;

class FollowTest extends TestCase
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
            ->getMockBuilder(Follow::class)
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

    public function testCreateFollowInstance()
    {
        $follow = new Follow($this->sdk);

        $this->assertIsObject($follow);
    }

    public function testFollowPlaylist()
    {
        $response = getFixture("follow-follow-playlist");
        $returnValue = $response;
        $returnValueDecoded = json_decode($returnValue, true);

        $follow = $this->setUpMockObject(
            "followPlaylist",
            $returnValue);

        $this->assertEquals(
            $returnValue,
            $follow->followPlaylist("2CiGXPNpavGBrLd47ISrNq", [])
        );

        self::assertJson($returnValue);

        $this->assertIsArray($returnValueDecoded);
    }

    public function testUnFollowPlaylist()
    {
        $response = getFixture("follow-unfollow-playlist");
        $returnValue = $response;
        $returnValueDecoded = json_decode($returnValue, true);

        $follow = $this->setUpMockObject(
            "unfollowPlaylist",
            $returnValue);

        $this->assertEquals(
            $returnValue,
            $follow->unfollowPlaylist("2CiGXPNpavGBrLd47ISrNq", [])
        );

        self::assertJson($returnValue);

        $this->assertIsArray($returnValueDecoded);
    }

    public function testUsersFollowPlaylist()
    {
        $response = getFixture("follow-users-follow-playlist");
        $returnValue = $response;
        $returnValueDecoded = json_decode($returnValue, true);

        $follow = $this->setUpMockObject(
            "usersFollowPlaylist",
            $returnValue);

        $this->assertEquals(
            $returnValue,
            $follow->usersFollowPlaylist("2v3iNvBX8Ay1Gt2uXtUKUT", [
                "query_params" => [
                    "ids" => "testuser"
                ]
            ])
        );

        self::assertJson($returnValue);

        $this->assertIsArray($returnValueDecoded);
    }

    public function testGetFollowing()
    {
        $response = getFixture("follow-get-following");
        $returnValue = $response;
        $returnValueDecoded = json_decode($returnValue, true);

        $follow = $this->setUpMockObject(
            "getFollowing",
            $returnValue);

        $this->assertEquals(
            $returnValue,
            $follow->getFollowing([])
        );

        self::assertJson($returnValue);

        $this->assertIsArray($returnValueDecoded);
    }

    public function testFollow()
    {
        $response = getFixture("follow-follow");
        $returnValue = $response;
        $returnValueDecoded = json_decode($returnValue, true);

        $follow = $this->setUpMockObject(
            "follow",
            $returnValue);

        $this->assertEquals(
            $returnValue,
            $follow->follow([
                "query_params" => [
                    "type" => "artist",
                    "ids" => "0CbeG1224FS58EUx4tPevZ"
                ]
            ])
        );

        $this->assertEmpty($returnValueDecoded);
    }

    public function testUnfollow()
    {
        $response = getFixture("follow-unfollow");
        $returnValue = $response;
        $returnValueDecoded = json_decode($returnValue, true);

        $follow = $this->setUpMockObject(
            "unFollow",
            $returnValue);

        $this->assertEquals(
            $returnValue,
            $follow->unFollow([
                "query_params" => [
                    "type" => "artist",
                    "ids" => "0CbeG1224FS58EUx4tPevZ"
                ]
            ])
        );

        $this->assertEmpty($returnValueDecoded);
    }

    public function testFollowingState()
    {
        $response = getFixture("follow-following-state");
        $returnValue = $response;
        $returnValueDecoded = json_decode($returnValue, true);

        $follow = $this->setUpMockObject(
            "followingState",
            $returnValue);

        $this->assertEquals(
            $returnValue,
            $follow->followingState([
                "query_params" => [
                    "type" => "artist",
                    "ids" => "74ASZWbe4lXaubB36ztrGX,08td7MxkoHQkXnWAYD8d6Q"
                ]
            ])
        );

        self::assertJson($returnValue);
    }
}
