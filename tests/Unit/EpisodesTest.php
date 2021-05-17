<?php


namespace Gjoni\SpotifyWebApiSdk\Tests\Unit;

use function getFixture;
use Gjoni\SpotifyWebApiSdk\Episodes;
use Gjoni\SpotifyWebApiSdk\Interfaces\SdkInterface;
use Gjoni\SpotifyWebApiSdk\Sdk;
use PHPUnit\Framework\TestCase;

class EpisodesTest extends TestCase
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
            ->getMockBuilder(Episodes::class)
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

    public function testCreateEpisodesInstance()
    {
        $client = new Episodes($this->sdk);

        $this->assertIsObject($client);
    }

    public function testGetMultiple()
    {
        $response = getFixture("episodes-get-multiple");
        $returnValue = $response;
        $returnValueDecoded = json_decode($returnValue, true);

        $episodes = $this->setUpMockObject(
            "getMultiple",
            $returnValue);

        $this->assertEquals(
            $returnValue,
            $episodes->getMultiple([])
        );

        self::assertJson($returnValue);

        $this->assertIsArray($returnValueDecoded);
        $this->assertIsArray($returnValueDecoded["episodes"]);
    }

    public function testGetSingle()
    {
        $response = getFixture("episodes-get-single");
        $returnValue = $response;
        $returnValueDecoded = json_decode($returnValue, true);

        $episodes = $this->setUpMockObject(
            "getSingle",
            $returnValue);

        $this->assertEquals(
            $returnValue,
            $episodes->getSingle("512ojhOuo1ktJprKbVcKyQ", [])
        );

        self::assertJson($returnValue);

        $this->assertIsArray($returnValueDecoded);
        $this->assertIsString($returnValueDecoded["href"]);
    }
}
