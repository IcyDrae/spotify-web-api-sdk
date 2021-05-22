<?php


namespace Gjoni\SpotifyWebApiSdk\Tests\Unit;

use function getFixture;
use Gjoni\SpotifyWebApiSdk\Personalization;
use Gjoni\SpotifyWebApiSdk\Interfaces\SdkInterface;
use Gjoni\SpotifyWebApiSdk\Sdk;
use PHPUnit\Framework\TestCase;

class PersonalizationTest extends TestCase
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
            ->getMockBuilder(Personalization::class)
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

    public function testCreatePersonalizationInstance()
    {
        $personalization = new Personalization($this->sdk);

        $this->assertIsObject($personalization);
    }

    public function testGetTopTracks()
    {
        $response = getFixture("personalization-get-top-tracks");
        $returnValue = $response;
        $returnValueDecoded = json_decode($returnValue, true);

        $personalization = $this->setUpMockObject(
            "getTop",
            $returnValue);

        $this->assertEquals(
            $returnValue,
            $personalization->getTop("tracks", [])
        );

        self::assertJson($returnValue);

        $this->assertIsArray($returnValueDecoded);
        $this->assertIsArray($returnValueDecoded["items"]);
    }

    public function testGetTopArtists()
    {
        $response = getFixture("personalization-get-top-artists");
        $returnValue = $response;
        $returnValueDecoded = json_decode($returnValue, true);

        $personalization = $this->setUpMockObject(
            "getTop",
            $returnValue);

        $this->assertEquals(
            $returnValue,
            $personalization->getTop("tracks", [])
        );

        self::assertJson($returnValue);

        $this->assertIsArray($returnValueDecoded);
        $this->assertIsArray($returnValueDecoded["items"]);
    }
}
