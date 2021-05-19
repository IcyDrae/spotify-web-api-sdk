<?php


namespace Gjoni\SpotifyWebApiSdk\Tests\Unit\Http;

use function getFixture;
use Gjoni\SpotifyWebApiSdk\Http\Client;
use Gjoni\SpotifyWebApiSdk\Interfaces\SdkInterface;
use Gjoni\SpotifyWebApiSdk\Sdk;
use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase
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
            ->getMockBuilder(Client::class)
            ->setConstructorArgs([$this->sdk, "https://jsonplaceholder.typicode.com"])
            ->onlyMethods([$methodName])
            ->getMock();

        $mock
            ->expects($this->once())
            ->method($methodName)
            ->withAnyParameters()
            ->willReturn($returnValue);

        return $mock;
    }

    public function testCreateClientInstance()
    {
        $client = new Client($this->sdk);

        $this->assertIsObject($client);
    }

    public function testDelegate()
    {
        $response = getFixture("client-dummy-request");
        $returnValue = $response;
        $returnValueDecoded = json_decode($returnValue, true);

        $client = $this->setUpMockObject(
            "delegate",
            $returnValue);

        $this->assertEquals(
            $returnValue,
            $client->delegate("GET", "/posts/5", [])
        );

        self::assertJson($returnValue);

        $this->assertIsArray($returnValueDecoded);
        $this->assertIsInt($returnValueDecoded["userId"]);
        $this->assertIsInt($returnValueDecoded["id"]);
        $this->assertIsString($returnValueDecoded["title"]);
        $this->assertIsString($returnValueDecoded["body"]);
    }
}
