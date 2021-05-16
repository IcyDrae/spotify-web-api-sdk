<?php


namespace Gjoni\SpotifyWebApiSdk\Tests\Unit\Http;

use PHPUnit\Framework\TestCase;
use Gjoni\SpotifyWebApiSdk\Http\Output;

class OutputTest extends TestCase
{

    public function testCreateOutputInstance()
    {

        $output = new Output([
            "userId" => 1,
            "id" => 1,
            "title" => "delectus aut autem",
            "completed" => false
        ], [
            "error" => "some-error"
        ]);

        $this->assertIsObject($output);
    }

}
