<?php


namespace Gjoni\SpotifyWebApiSdk\Tests\Unit;

use function getFixture;
use Gjoni\SpotifyWebApiSdk\Playlists;
use Gjoni\SpotifyWebApiSdk\Interfaces\SdkInterface;
use Gjoni\SpotifyWebApiSdk\Sdk;
use PHPUnit\Framework\TestCase;

class PlaylistsTest extends TestCase
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
            ->getMockBuilder(Playlists::class)
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

    public function testCreatePlaylistsInstance()
    {
        $playlists = new Playlists($this->sdk);

        $this->assertIsObject($playlists);
    }

    public function testGetPlaylists()
    {
        $response = getFixture("playlists-get-playlists");
        $returnValue = $response;
        $returnValueDecoded = json_decode($returnValue, true);

        $playlists = $this->setUpMockObject(
            "getPlaylists",
            $returnValue);

        $this->assertEquals(
            $returnValue,
            $playlists->getPlaylists([])
        );

        self::assertJson($returnValue);

        $this->assertIsArray($returnValueDecoded);
        $this->assertIsArray($returnValueDecoded["items"]);
    }

    public function testGetUserPlaylists()
    {
        $response = getFixture("playlists-get-user-playlists");
        $returnValue = $response;
        $returnValueDecoded = json_decode($returnValue, true);

        $playlists = $this->setUpMockObject(
            "getUserPlaylists",
            $returnValue);

        $this->assertEquals(
            $returnValue,
            $playlists->getUserPlaylists("user", [])
        );

        self::assertJson($returnValue);

        $this->assertIsArray($returnValueDecoded);
        $this->assertIsArray($returnValueDecoded["items"]);
    }

    public function testCreate()
    {
        $response = getFixture("playlists-get-user-playlists");
        $returnValue = $response;
        $returnValueDecoded = json_decode($returnValue, true);
        $bodyParam = json_encode([
            "name" => "client_test"
        ]);

        $playlists = $this->setUpMockObject(
            "create",
            $returnValue);

        $this->assertEquals(
            $returnValue,
            $playlists->create("reard.gjoni97", [
                "body" => $bodyParam
            ])
        );

        self::assertJson($returnValue);

        $this->assertIsArray($returnValueDecoded);
        $this->assertIsString($returnValueDecoded["href"]);
    }

    public function testGetPlaylist()
    {
        $response = getFixture("playlists-get-playlist");
        $returnValue = $response;
        $returnValueDecoded = json_decode($returnValue, true);

        $playlists = $this->setUpMockObject(
            "getPlaylist",
            $returnValue);

        $this->assertEquals(
            $returnValue,
            $playlists->getPlaylist("5mneMwXUYxBkPHYYfXLUvo", [])
        );

        self::assertJson($returnValue);

        $this->assertIsArray($returnValueDecoded);
        $this->assertIsString($returnValueDecoded["href"]);
    }

    public function testChangeDetails()
    {
        $bodyParam = json_encode([
            "name" => "Test playlist"
        ]);

        $playlists = $this->setUpMockObject(
            "changeDetails",
            '');

        $this->assertEquals(
            '',
            $playlists->changeDetails("5mneMwXUYxBkPHYYfXLUvo", [
                "body" => $bodyParam
            ])
        );
    }

    public function testGetItems()
    {
        $response = getFixture("playlists-get-items");
        $returnValue = $response;
        $returnValueDecoded = json_decode($returnValue, true);

        $playlists = $this->setUpMockObject(
            "getItems",
            $returnValue);

        $this->assertEquals(
            $returnValue,
            $playlists->getItems("5mneMwXUYxBkPHYYfXLUvo", [])
        );

        self::assertJson($returnValue);

        $this->assertIsArray($returnValueDecoded);
        $this->assertIsArray($returnValueDecoded["items"]);
    }

    public function testAddItems()
    {
        $response = getFixture("playlists-add-items");
        $returnValue = $response;
        $returnValueDecoded = json_decode($returnValue, true);

        $playlists = $this->setUpMockObject(
            "addItems",
            $returnValue);

        $this->assertEquals(
            $returnValue,
            $playlists->addItems("5mneMwXUYxBkPHYYfXLUvo", [])
        );

        self::assertJson($returnValue);

        $this->assertIsArray($returnValueDecoded);
        $this->assertIsString($returnValueDecoded["snapshot_id"]);
    }

    public function testChangeItems()
    {
        $response = getFixture("playlists-change-items");
        $returnValue = $response;
        $returnValueDecoded = json_decode($returnValue, true);
        $bodyParams = json_encode([
            "range_start" => 0,
            "insert_before" => 5
        ]);

        $playlists = $this->setUpMockObject(
            "changeItems",
            $returnValue);

        $this->assertEquals(
            $returnValue,
            $playlists->changeItems("5mneMwXUYxBkPHYYfXLUvo", [
                "body" => $bodyParams
            ])
        );

        self::assertJson($returnValue);

        $this->assertIsArray($returnValueDecoded);
        $this->assertIsString($returnValueDecoded["snapshot_id"]);
    }

    public function testRemoveItems()
    {
        $response = getFixture("playlists-remove-items");
        $returnValue = $response;
        $returnValueDecoded = json_decode($returnValue, true);
        $bodyParams = json_encode([
            "tracks" => [
                "uri" => "spotify:track:5tBkRo9ledyIr6JJ1U6MPW"
            ]
        ]);

        $playlists = $this->setUpMockObject(
            "removeItems",
            $returnValue);

        $this->assertEquals(
            $returnValue,
            $playlists->removeItems("2CiGXPNpavGBrLd47ISrNq", [
                "body" => $bodyParams
            ])
        );

        self::assertJson($returnValue);

        $this->assertIsArray($returnValueDecoded);
        $this->assertIsString($returnValueDecoded["snapshot_id"]);
    }

    public function testGetCoverImage()
    {
        $response = getFixture("playlists-get-cover-image");
        $returnValue = $response;
        $returnValueDecoded = json_decode($returnValue, true);

        $playlists = $this->setUpMockObject(
            "getCoverImage",
            $returnValue);

        $this->assertEquals(
            $returnValue,
            $playlists->getCoverImage("5mneMwXUYxBkPHYYfXLUvo", [])
        );

        self::assertJson($returnValue);

        $this->assertIsArray($returnValueDecoded);
        $this->assertIsInt($returnValueDecoded[0]["height"]);
    }

    public function testChangeCoverImage()
    {
        $img = "some-image";

        $playlists = $this->setUpMockObject(
            "changeCoverImage",
            '');

        $this->assertEquals(
            '',
            $playlists->changeCoverImage("5mneMwXUYxBkPHYYfXLUvo", [
                "body" => base64_encode($img)
            ])
        );
    }
}
