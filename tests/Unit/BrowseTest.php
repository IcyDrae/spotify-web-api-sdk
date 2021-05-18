<?php


namespace Gjoni\SpotifyWebApiSdk\Tests\Unit;

use function getFixture;
use Gjoni\SpotifyWebApiSdk\Browse;
use Gjoni\SpotifyWebApiSdk\Interfaces\SdkInterface;
use Gjoni\SpotifyWebApiSdk\Sdk;
use PHPUnit\Framework\TestCase;

class   BrowseTest extends TestCase
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
            ->getMockBuilder(Browse::class)
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

    public function testCreateBrowseInstance()
    {
        $browse = new Browse($this->sdk);

        $this->assertIsObject($browse);
    }

    public function testGetNewReleases()
    {
        $response = getFixture("browse-get-new-releases");
        $returnValue = $response;
        $returnValueDecoded = json_decode($returnValue, true);

        $browse = $this->setUpMockObject(
            "getNewReleases",
            $returnValue);

        $this->assertEquals(
            $returnValue,
            $browse->getNewReleases([])
        );

        self::assertJson($returnValue);

        $this->assertIsArray($returnValueDecoded);
        $this->assertIsArray($returnValueDecoded["albums"]);
    }

    public function testGetFeaturedPlaylists()
    {
        $response = getFixture("browse-get-featured-playlists");
        $returnValue = $response;
        $returnValueDecoded = json_decode($returnValue, true);

        $browse = $this->setUpMockObject(
            "getFeaturedPlaylists",
            $returnValue);

        $this->assertEquals(
            $returnValue,
            $browse->getFeaturedPlaylists([])
        );

        self::assertJson($returnValue);

        $this->assertIsArray($returnValueDecoded);
        $this->assertIsString($returnValueDecoded["message"]);
        $this->assertIsArray($returnValueDecoded["playlists"]);
    }

    public function testGetCategories()
    {
        $response = getFixture("browse-get-categories");
        $returnValue = $response;
        $returnValueDecoded = json_decode($returnValue, true);

        $browse = $this->setUpMockObject(
            "getCategories",
            $returnValue);

        $this->assertEquals(
            $returnValue,
            $browse->getCategories([])
        );

        self::assertJson($returnValue);

        $this->assertIsArray($returnValueDecoded);
        $this->assertIsArray($returnValueDecoded["categories"]);
    }

    public function testGetCategory()
    {
        $response = getFixture("browse-get-category");
        $returnValue = $response;
        $returnValueDecoded = json_decode($returnValue, true);

        $browse = $this->setUpMockObject(
            "getCategory",
            $returnValue);

        $this->assertEquals(
            $returnValue,
            $browse->getCategory("dinner", [])
        );

        self::assertJson($returnValue);

        $this->assertIsArray($returnValueDecoded);
        $this->assertIsString($returnValueDecoded["id"]);
    }

    public function testGetCategoryPlaylists()
    {
        $response = getFixture("browse-get-category-playlists");
        $returnValue = $response;
        $returnValueDecoded = json_decode($returnValue, true);

        $browse = $this->setUpMockObject(
            "getCategoryPlaylists",
            $returnValue);

        $this->assertEquals(
            $returnValue,
            $browse->getCategoryPlaylists("dinner", [])
        );

        self::assertJson($returnValue);

        $this->assertIsArray($returnValueDecoded);
        $this->assertIsArray($returnValueDecoded["playlists"]);
    }

    public function testGetRecommendations()
    {
        $response = getFixture("browse-get-recommendations");
        $returnValue = $response;
        $returnValueDecoded = json_decode($returnValue, true);

        $browse = $this->setUpMockObject(
            "getRecommendations",
            $returnValue);

        $this->assertEquals(
            $returnValue,
            $browse->getRecommendations([
                "query_params" => [
                    "market" => "DE",
                    "seed_artists" => "6rqhFgbbKwnb9MLmUQDhG6",
                    "seed_genres" => "pop,hip-hop",
                    "seed_tracks" => "0c6xIDDpzE81m2q797ordA"
                ]
            ])
        );

        self::assertJson($returnValue);

        $this->assertIsArray($returnValueDecoded);
        $this->assertIsArray($returnValueDecoded["tracks"]);
        $this->assertIsArray($returnValueDecoded["seeds"]);
    }

    public function testGetRecommendationGenres()
    {
        $response = getFixture("browse-get-recommendation-genres");
        $returnValue = $response;
        $returnValueDecoded = json_decode($returnValue, true);

        $browse = $this->setUpMockObject(
            "getRecommendationGenres",
            $returnValue);

        $this->assertEquals(
            $returnValue,
            $browse->getRecommendationGenres([])
        );

        self::assertJson($returnValue);

        $this->assertIsArray($returnValueDecoded);
        $this->assertIsArray($returnValueDecoded["genres"]);
    }
}
