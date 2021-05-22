<?php


namespace Gjoni\SpotifyWebApiSdk\Tests\Unit;

use function getFixture;
use Gjoni\SpotifyWebApiSdk\Tracks;
use Gjoni\SpotifyWebApiSdk\Interfaces\SdkInterface;
use Gjoni\SpotifyWebApiSdk\Sdk;
use PHPUnit\Framework\TestCase;

class TracksTest extends TestCase
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
            ->getMockBuilder(Tracks::class)
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

    public function testCreateTracksInstance()
    {
        $tracks = new Tracks($this->sdk);

        $this->assertIsObject($tracks);
    }

    public function testGetMultiple()
    {
        $response = getFixture("tracks-get-multiple");
        $returnValue = $response;
        $returnValueDecoded = json_decode($returnValue, true);

        $tracks = $this->setUpMockObject(
            "getMultiple",
            $returnValue);

        $this->assertEquals(
            $returnValue,
            $tracks->getMultiple([
                "query_params" => [
                    "ids" => "3n3Ppam7vgaVa1iaRUc9Lp,3twNvmDtFQtAd5gMKedhLD"
                ]
            ])
        );

        self::assertJson($returnValue);

        $this->assertIsArray($returnValueDecoded);
        $this->assertIsArray($returnValueDecoded["tracks"]);
    }

    public function testGetSingle()
    {
        $response = getFixture("tracks-get-single");
        $returnValue = $response;
        $returnValueDecoded = json_decode($returnValue, true);

        $tracks = $this->setUpMockObject(
            "getSingle",
            $returnValue);

        $this->assertEquals(
            $returnValue,
            $tracks->getSingle("3n3Ppam7vgaVa1iaRUc9Lp", [])
        );

        self::assertJson($returnValue);

        $this->assertIsArray($returnValueDecoded);
        $this->assertIsArray($returnValueDecoded["album"]);
    }

    public function testGetAudioFeaturesForMultiple()
    {
        $response = getFixture("tracks-get-audio-features-for-multiple");
        $returnValue = $response;
        $returnValueDecoded = json_decode($returnValue, true);

        $tracks = $this->setUpMockObject(
            "getAudioFeaturesForMultiple",
            $returnValue);

        $this->assertEquals(
            $returnValue,
            $tracks->getAudioFeaturesForMultiple([
                "query_params" => [
                    "ids" => "4JpKVNYnVcJ8tuMKjAj50A,2NRANZE9UCmPAS5XVbXL40,24JygzOLM0EmRQeGtFcIcG"
                ]
            ])
        );

        self::assertJson($returnValue);

        $this->assertIsArray($returnValueDecoded);
        $this->assertIsArray($returnValueDecoded["audio_features"]);
    }

    public function testGetAudioFeaturesForSingle()
    {
        $response = getFixture("tracks-get-audio-features-for-single");
        $returnValue = $response;
        $returnValueDecoded = json_decode($returnValue, true);

        $tracks = $this->setUpMockObject(
            "getAudioFeaturesForSingle",
            $returnValue);

        $this->assertEquals(
            $returnValue,
            $tracks->getAudioFeaturesForSingle("06AKEBrKUckW0KREUWRnvT", [])
        );

        self::assertJson($returnValue);

        $this->assertIsArray($returnValueDecoded);
        $this->assertIsFloat($returnValueDecoded["danceability"]);
    }

    public function testGetAudioAnalysisForSingle()
    {
        $response = getFixture("tracks-get-audio-analysis-for-single");
        $returnValue = $response;
        $returnValueDecoded = json_decode($returnValue, true);

        $tracks = $this->setUpMockObject(
            "getAudioAnalysisForSingle",
            $returnValue);

        $this->assertEquals(
            $returnValue,
            $tracks->getAudioAnalysisForSingle("06AKEBrKUckW0KREUWRnvT", [])
        );

        self::assertJson($returnValue);

        $this->assertIsArray($returnValueDecoded);
        $this->assertIsArray($returnValueDecoded["track"]);
    }



    /*

    public function getAudioAnalysisForSingle(string $id, array $options = []): string
    {
        return $this->delegate("GET", SdkConstants::API_VERSION . "/audio-analysis/$id", $options);
    }

    */

}
