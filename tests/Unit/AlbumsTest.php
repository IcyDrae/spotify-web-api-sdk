<?php


namespace Gjoni\SpotifyWebApiSdk\Tests\Unit;

use function getFixture;
use Gjoni\SpotifyWebApiSdk\Albums;
use Gjoni\SpotifyWebApiSdk\Interfaces\SdkInterface;
use Gjoni\SpotifyWebApiSdk\Sdk;
use PHPUnit\Framework\TestCase;

class AlbumsTest extends TestCase
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
            ->getMockBuilder(Albums::class)
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
        $albums = new Albums($this->sdk);

        $this->assertIsObject($albums);
    }

    public function testGetMultiple()
    {
        $response = getFixture("albums-get-multiple");
        $returnValue = $response;
        $returnValueDecoded = json_decode($returnValue, true);

        $albums = $this->setUpMockObject(
            "getMultiple",
            $returnValue);

        $this->assertEquals(
            $returnValue,
            $albums->getMultiple([])
        );

        self::assertJson($returnValue);

        $this->assertIsArray($returnValueDecoded);
        $this->assertIsArray($returnValueDecoded["albums"]);
    }

    public function testGetSingle()
    {
        $response = getFixture("albums-get-single");
        $returnValue = $response;
        $returnValueDecoded = json_decode($returnValue, true);

        $albums = $this->setUpMockObject(
            "getSingle",
            $returnValue);

        $this->assertEquals(
            $returnValue,
            $albums->getSingle("7eMXWxMOHtAoliVQfVpdpl", [])
        );

        self::assertJson($returnValue);

        $this->assertIsArray($returnValueDecoded);
        $this->assertIsString($returnValueDecoded["album_type"]);
    }

    public function testGetTracks()
    {
        $response = getFixture("albums-get-tracks");
        $returnValue = $response;
        $returnValueDecoded = json_decode($returnValue, true);

        $albums = $this->setUpMockObject(
            "getTracks",
            $returnValue);

        $this->assertEquals(
            $returnValue,
            $albums->getTracks("7eMXWxMOHtAoliVQfVpdpl", [])
        );

        self::assertJson($returnValue);

        $this->assertIsArray($returnValueDecoded);
        $this->assertIsString($returnValueDecoded["href"]);
    }
}
