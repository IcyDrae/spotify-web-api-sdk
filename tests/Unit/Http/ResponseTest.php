<?php


namespace Gjoni\SpotifyWebApiSdk\Tests\Unit\Http;

use Gjoni\SpotifyWebApiSdk\Http\Output;
use Gjoni\SpotifyWebApiSdk\Http\Response;
use PHPUnit\Framework\TestCase;

class ResponseTest extends TestCase
{

    public function testCreateResponseInstance()
    {
        $response = new Response();

        $this->assertIsObject($response);
    }

    public function testSetHeader()
    {
        $response = new Response();

        $response->setHeader(200, "Content-Type: text/html; charset=UTF-8");

        # We need to use xdebug_get_headers() instead of getallheaders()
        $this->assertTrue(
            in_array(
                "Content-Type: text/html; charset=UTF-8",
                xdebug_get_headers()
            )
        );
    }

    public function testJsonResponseOk()
    {
        $response = new Response();

        $data = [
            "first" => true,
            "second" => 1,
            "third" => "Some data"
        ];

        $output = $response->json(
            new Output($data, [])
        );
        $outputDecoded = json_decode($output, true);

        self::assertJson(
            $output,
            "The response is not JSON format."
        );

        $this->assertTrue(
            !empty(
                $outputDecoded["data"]
            )
        );

        $this->assertTrue(
            empty(
                $outputDecoded["error"]
            )
        );
    }
}
