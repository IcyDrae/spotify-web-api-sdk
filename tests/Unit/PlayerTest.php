<?php


namespace Gjoni\SpotifyWebApiSdk\Tests\Unit;

use function getFixture;
use Gjoni\SpotifyWebApiSdk\Player;
use Gjoni\SpotifyWebApiSdk\Interfaces\SdkInterface;
use Gjoni\SpotifyWebApiSdk\Sdk;
use PHPUnit\Framework\TestCase;

class PlayerTest extends TestCase
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
            ->getMockBuilder(Player::class)
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

    public function testCreatePlayerInstance()
    {
        $player = new Player($this->sdk);

        $this->assertIsObject($player);
    }

    public function testGetCurrentPlayback()
    {
        $response = getFixture("player-get-current-playback");
        $returnValue = $response;
        $returnValueDecoded = json_decode($returnValue, true);

        $player = $this->setUpMockObject(
            "getCurrentPlayback",
            $returnValue);

        $this->assertEquals(
            $returnValue,
            $player->getCurrentPlayback([])
        );

        self::assertJson($returnValue);

        $this->assertIsArray($returnValueDecoded);
        $this->assertIsArray($returnValueDecoded["device"]);
    }

    public function testTransferPlayback()
    {
        $player = $this->setUpMockObject(
            "transferPlayback",
        '');

        $this->assertEquals(
            '',
            $player->transferPlayback([
                "body" => file_get_contents('php://input')
            ])
        );
    }

    public function testGetAvailableDevices()
    {
        $response = getFixture("player-get-available-devices");
        $returnValue = $response;
        $returnValueDecoded = json_decode($returnValue, true);

        $player = $this->setUpMockObject(
            "getAvailableDevices",
            $returnValue);

        $this->assertEquals(
            $returnValue,
            $player->getAvailableDevices([])
        );

        self::assertJson($returnValue);

        $this->assertIsArray($returnValueDecoded);
        $this->assertIsArray($returnValueDecoded["devices"]);
    }

    public function testGetCurrentlyPlaying()
    {
        $response = getFixture("player-get-currently-playing");
        $returnValue = $response;
        $returnValueDecoded = json_decode($returnValue, true);

        $player = $this->setUpMockObject(
            "getCurrentlyPlaying",
            $returnValue);

        $this->assertEquals(
            $returnValue,
            $player->getCurrentlyPlaying([])
        );

        self::assertJson($returnValue);

        $this->assertIsArray($returnValueDecoded);
        $this->assertIsArray($returnValueDecoded["context"]);
    }

    public function testStartPlayback()
    {
        $player = $this->setUpMockObject(
            "startPlayback",
            '');

        $this->assertEquals(
            '',
            $player->startPlayback([])
        );
    }

    public function testPausePlayback()
    {
        $player = $this->setUpMockObject(
            "pausePlayback",
            '');

        $this->assertEquals(
            '',
            $player->pausePlayback([])
        );
    }

    public function testSkipPlayback()
    {
        $player = $this->setUpMockObject(
            "skipPlayback",
            '');

        $this->assertEquals(
            '',
            $player->skipPlayback([])
        );
    }

    public function testSeekTrackPosition()
    {
        $player = $this->setUpMockObject(
            "seekTrackPosition",
            '');

        $this->assertEquals(
            '',
            $player->seekTrackPosition([
                "query_params" => [
                    "position_ms" => 5000
                ]
            ])
        );
    }

    public function testSetRepeatMode()
    {
        $player = $this->setUpMockObject(
            "setRepeatMode",
            '');

        $this->assertEquals(
            '',
            $player->setRepeatMode([
                "query_params" => [
                    "state" => "track"
                ]
            ])
        );
    }

    public function testSetVolume()
    {
        $player = $this->setUpMockObject(
            "setVolume",
            '');

        $this->assertEquals(
            '',
            $player->setVolume([
                "query_params" => [
                    "volume_percent" => 100
                ]
            ])
        );
    }

    public function testToggleShuffle()
    {
        $player = $this->setUpMockObject(
            "toggleShuffle",
            '');

        $this->assertEquals(
            '',
            $player->toggleShuffle([
                "query_params" => [
                    "state" => "true"
                ]
            ])
        );
    }

    public function testGetRecentlyPlayed()
    {
        $response = getFixture("player-get-recently-played");
        $returnValue = $response;
        $returnValueDecoded = json_decode($returnValue, true);

        $player = $this->setUpMockObject(
            "getRecentlyPlayed",
            $returnValue);

        $this->assertEquals(
            $returnValue,
            $player->getRecentlyPlayed([])
        );

        self::assertJson($returnValue);

        $this->assertIsArray($returnValueDecoded);
        $this->assertIsArray($returnValueDecoded["items"]);
    }

    public function testAddToQueue()
    {
        $player = $this->setUpMockObject(
            "addToQueue",
            '');

        $this->assertEquals(
            '',
            $player->addToQueue([
                "query_params" => [
                    "uri" => "spotify:track:1RlnzIazwomBpkg7vHVs2s"
                ]
            ])
        );
    }

    /*
    public function addToQueue(array $options = []): string
    {
        return $this->delegate("POST", SdkConstants::PLAYER . "/queue", $options);
    }*/

}
