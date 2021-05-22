<?php


namespace Gjoni\SpotifyWebApiSdk\Tests\Unit;

use function getFixture;
use Gjoni\SpotifyWebApiSdk\Library;
use Gjoni\SpotifyWebApiSdk\Interfaces\SdkInterface;
use Gjoni\SpotifyWebApiSdk\Sdk;
use PHPUnit\Framework\TestCase;

class LibraryTest extends TestCase
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
            ->getMockBuilder(Library::class)
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

    public function testCreateLibraryInstance()
    {
        $library = new Library($this->sdk);

        $this->assertIsObject($library);
    }

    public function testGetSavedAlbums()
    {
        $response = getFixture("library-get-saved-albums");
        $returnValue = $response;
        $returnValueDecoded = json_decode($returnValue, true);

        $library = $this->setUpMockObject(
            "getSavedAlbums",
            $returnValue);

        $this->assertEquals(
            $returnValue,
            $library->getSavedAlbums([])
        );

        self::assertJson($returnValue);

        $this->assertIsArray($returnValueDecoded);
        $this->assertIsArray($returnValueDecoded["items"]);
    }

    public function testSaveAlbums()
    {
        $library = $this->setUpMockObject(
            "saveAlbums",
            '');

        $this->assertEquals(
            '',
            $library->saveAlbums([
                "query_params" => [
                    "ids" => "07bYtmE3bPsLB6ZbmmFi8d,48JYNjh7GMie6NjqYHMmtT,27cZdqrQiKt3IT00338dws"
                ]
            ])
        );
    }

    public function testRemoveAlbums()
    {
        $library = $this->setUpMockObject(
            "removeAlbums",
            '');

        $this->assertEquals(
            '',
            $library->removeAlbums([
                "query_params" => [
                    "ids" => "07bYtmE3bPsLB6ZbmmFi8d,48JYNjh7GMie6NjqYHMmtT,27cZdqrQiKt3IT00338dws"
                ]
            ])
        );
    }

    public function testCheckSavedAlbums()
    {
        $response = getFixture("library-check-saved-albums");
        $returnValue = $response;

        $library = $this->setUpMockObject(
            "checkSavedAlbums",
            $returnValue);

        $this->assertEquals(
            $returnValue,
            $library->checkSavedAlbums([
                "query_params" => [
                    "ids" => "07bYtmE3bPsLB6ZbmmFi8d,48JYNjh7GMie6NjqYHMmtT,27cZdqrQiKt3IT00338dws"
                ]
            ])
        );

        self::assertJson($returnValue);
    }

    public function testGetSavedTracks()
    {
        $response = getFixture("library-get-saved-tracks");
        $returnValue = $response;
        $returnValueDecoded = json_decode($returnValue, true);

        $library = $this->setUpMockObject(
            "getSavedTracks",
            $returnValue);

        $this->assertEquals(
            $returnValue,
            $library->getSavedTracks([])
        );

        self::assertJson($returnValue);

        $this->assertIsArray($returnValueDecoded);
        $this->assertIsArray($returnValueDecoded["items"]);
    }

    public function testSaveTracks()
    {
        $library = $this->setUpMockObject(
            "saveTracks",
            '');

        $this->assertEquals(
            '',
            $library->saveTracks([])
        );
    }

    public function testRemoveTracks()
    {
        $library = $this->setUpMockObject(
            "removeTracks",
            '');

        $this->assertEquals(
            '',
            $library->removeTracks([])
        );
    }

    public function testGetSavedEpisodes()
    {
        $response = getFixture("library-get-saved-episodes");
        $returnValue = $response;
        $returnValueDecoded = json_decode($returnValue, true);

        $library = $this->setUpMockObject(
            "getSavedEpisodes",
            $returnValue);

        $this->assertEquals(
            $returnValue,
            $library->getSavedEpisodes([])
        );

        self::assertJson($returnValue);

        $this->assertIsArray($returnValueDecoded);
        $this->assertIsArray($returnValueDecoded["items"]);
    }

    public function testSaveEpisodes()
    {
        $library = $this->setUpMockObject(
            "saveEpisodes",
            '');

        $this->assertEquals(
            '',
            $library->saveEpisodes([])
        );
    }

    public function testRemoveEpisodes()
    {
        $library = $this->setUpMockObject(
            "removeEpisodes",
            '');

        $this->assertEquals(
            '',
            $library->removeEpisodes([])
        );
    }

    public function testCheckSavedEpisodes()
    {
        $response = getFixture("library-check-saved-episodes");
        $returnValue = $response;
        $returnValueDecoded = json_decode($returnValue, true);

        $library = $this->setUpMockObject(
            "checkSavedEpisodes",
            $returnValue);

        $this->assertEquals(
            $returnValue,
            $library->checkSavedEpisodes([
                "query_params" => [
                    "ids" => "77o6BIVlYM3msb4MMIL1jH,0Q86acNRm6V9GYx55SXKwf"
                ]
            ])
        );

        self::assertJson($returnValue);

        $this->assertIsArray($returnValueDecoded);
    }

    public function testGetSavedShows()
    {
        $response = getFixture("library-get-saved-shows");
        $returnValue = $response;
        $returnValueDecoded = json_decode($returnValue, true);

        $library = $this->setUpMockObject(
            "getSavedShows",
            $returnValue);

        $this->assertEquals(
            $returnValue,
            $library->getSavedShows([])
        );

        self::assertJson($returnValue);

        $this->assertIsArray($returnValueDecoded);
        $this->assertIsArray($returnValueDecoded["items"]);
    }

    public function testSaveShows()
    {
        $library = $this->setUpMockObject(
            "saveShows",
            '');

        $this->assertEquals(
            '',
            $library->saveShows([
                "query_params" => [
                    "ids" => "5AvwZVawapvyhJUIx71pdJ,6ups0LMt1G8n81XLlkbsPo,5AvwZVawapvyhJUIx71pdJ"
                ]
            ])
        );
    }

    public function testRemoveShows()
    {
        $library = $this->setUpMockObject(
            "removeShows",
            '');

        $this->assertEquals(
            '',
            $library->removeShows([
                "query_params" => [
                    "ids" => "5AvwZVawapvyhJUIx71pdJ,6ups0LMt1G8n81XLlkbsPo,5AvwZVawapvyhJUIx71pdJ"
                ]
            ])
        );
    }

    public function testCheckSavedShows()
    {
        $response = getFixture("library-check-saved-shows");
        $returnValue = $response;
        $returnValueDecoded = json_decode($returnValue, true);

        $library = $this->setUpMockObject(
            "checkSavedShows",
            $returnValue);

        $this->assertEquals(
            $returnValue,
            $library->checkSavedShows([
                "query_params" => [
                    "ids" => "5AvwZVawapvyhJUIx71pdJ,6ups0LMt1G8n81XLlkbsPo,5AvwZVawapvyhJUIx71pdJ"
                ]
            ])
        );

        self::assertJson($returnValue);

        $this->assertIsArray($returnValueDecoded);
    }
}
