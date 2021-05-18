<?php


namespace Gjoni\SpotifyWebApiSdk\Tests\Unit;

use function getFixture;
use Gjoni\SpotifyWebApiSdk\Artists;
use Gjoni\SpotifyWebApiSdk\Interfaces\SdkInterface;
use Gjoni\SpotifyWebApiSdk\Sdk;
use PHPUnit\Framework\TestCase;

class ArtistsTest extends TestCase
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
            ->getMockBuilder(Artists::class)
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

    public function testCreateAlbumsInstance()
    {
        $artists = new Artists($this->sdk);

        $this->assertIsObject($artists);
    }

    public function testGetMultiple()
    {
        $response = getFixture("artists-get-multiple");
        $returnValue = $response;
        $returnValueDecoded = json_decode($returnValue, true);

        $artists = $this->setUpMockObject(
            "getMultiple",
            $returnValue);

        $this->assertEquals(
            $returnValue,
            $artists->getMultiple([
                "query_params" => [
                    "ids" => "0oSGxfWSnnOXhD2fKuz2Gy,3dBVyJ7JuOMt4GE9607Qin"
                ]
            ])
        );

        self::assertJson($returnValue);

        $this->assertIsArray($returnValueDecoded);
        $this->assertIsArray($returnValueDecoded["artists"]);
    }

    public function testGetSingle()
    {
        $response = getFixture("artists-get-single");
        $returnValue = $response;
        $returnValueDecoded = json_decode($returnValue, true);

        $artists = $this->setUpMockObject(
            "getSingle",
            $returnValue);

        $this->assertEquals(
            $returnValue,
            $artists->getSingle("0OdUWJ0sBjDrqHygGUXeCF", [])
        );

        self::assertJson($returnValue);

        $this->assertIsArray($returnValueDecoded);
        $this->assertIsArray($returnValueDecoded["external_urls"]);
        $this->assertIsArray($returnValueDecoded["followers"]);
    }

    public function testGetTopTracks()
    {
        $response = getFixture("artists-get-top-tracks");
        $returnValue = $response;
        $returnValueDecoded = json_decode($returnValue, true);

        $artists = $this->setUpMockObject(
            "getTopTracks",
            $returnValue);

        $this->assertEquals(
            $returnValue,
            $artists->getTopTracks("0oSGxfWSnnOXhD2fKuz2Gy", [
                "query_params" => [
                    "market" => "US"
                ]
            ])
        );

        self::assertJson($returnValue);

        $this->assertIsArray($returnValueDecoded);
        $this->assertIsArray($returnValueDecoded["tracks"]);
        $this->assertIsArray($returnValueDecoded["tracks"][0]["album"]);
    }

    public function testGetRelatedArtists()
    {
        $response = getFixture("artists-get-related");
        $returnValue = $response;
        $returnValueDecoded = json_decode($returnValue, true);

        $artists = $this->setUpMockObject(
            "getRelatedArtists",
            $returnValue);

        $this->assertEquals(
            $returnValue,
            $artists->getRelatedArtists("0oSGxfWSnnOXhD2fKuz2Gy", [])
        );

        self::assertJson($returnValue);

        $this->assertIsArray($returnValueDecoded);
        $this->assertIsArray($returnValueDecoded["artists"]);
        $this->assertIsArray($returnValueDecoded["artists"][0]["external_urls"]);
    }

    public function testGetAlbums()
    {
        $response = getFixture("artists-get-albums");
        $returnValue = $response;
        $returnValueDecoded = json_decode($returnValue, true);

        $artists = $this->setUpMockObject(
            "getAlbums",
            $returnValue);

        $this->assertEquals(
            $returnValue,
            $artists->getAlbums("0oSGxfWSnnOXhD2fKuz2Gy", [
                "query_params" => [
                    "include_groups" => "album,single,appears_on,compilation",
                    "limit" => 1
                ]
            ])
        );

        self::assertJson($returnValue);

        $this->assertIsArray($returnValueDecoded);
        $this->assertIsString($returnValueDecoded["href"]);
        $this->assertIsArray($returnValueDecoded["items"]);
    }
}
