<?php


namespace Gjoni\SpotifyWebApiSdk\Tests\Unit;

use function getFixture;
use Gjoni\SpotifyWebApiSdk\Shows;
use Gjoni\SpotifyWebApiSdk\Interfaces\SdkInterface;
use Gjoni\SpotifyWebApiSdk\Sdk;
use PHPUnit\Framework\TestCase;

class ShowsTest extends TestCase
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
            ->getMockBuilder(Shows::class)
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

    public function testCreateShowsInstance()
    {
        $shows = new Shows($this->sdk);

        $this->assertIsObject($shows);
    }

    public function testGetMultiple()
    {
        $response = getFixture("shows-get-multiple");
        $returnValue = $response;
        $returnValueDecoded = json_decode($returnValue, true);

        $shows = $this->setUpMockObject(
            "getMultiple",
            $returnValue);

        $this->assertEquals(
            $returnValue,
            $shows->getMultiple([])
        );

        self::assertJson($returnValue);

        $this->assertIsArray($returnValueDecoded);
        $this->assertIsArray($returnValueDecoded["shows"]);
    }

    public function testGetSingle()
    {
        $response = getFixture("shows-get-single");
        $returnValue = $response;
        $returnValueDecoded = json_decode($returnValue, true);

        $shows = $this->setUpMockObject(
            "getSingle",
            $returnValue);

        $this->assertEquals(
            $returnValue,
            $shows->getSingle("38bS44xjbVVZ3No3ByF1dJ", [])
        );

        self::assertJson($returnValue);

        $this->assertIsArray($returnValueDecoded);
        $this->assertIsArray($returnValueDecoded["episodes"]);
    }

    public function testGetEpisodes()
    {
        $response = getFixture("shows-get-episodes");
        $returnValue = $response;
        $returnValueDecoded = json_decode($returnValue, true);

        $shows = $this->setUpMockObject(
            "getEpisodes",
            $returnValue);

        $this->assertEquals(
            $returnValue,
            $shows->getEpisodes("38bS44xjbVVZ3No3ByF1dJ", [])
        );

        self::assertJson($returnValue);

        $this->assertIsArray($returnValueDecoded);
        $this->assertIsArray($returnValueDecoded["items"]);
    }
}
