<?php


namespace Gjoni\SpotifyWebApiSdk\Tests\Unit;

use PHPUnit\Framework\TestCase;

class AuthorizationFlowsTest extends TestCase
{

    public function test_1()
    {
        $this->assertSame(1, 1);
        $this->assertSame(0, 0);
        $this->assertSame(1, 1);
    }

    public function test_2()
    {
        $this->assertSame(1, 1);
        $this->assertSame(0, 0);
        $this->assertSame(1, 1);
    }

    public function testExpectFooActualFoo(): void
    {
        $this->expectOutputString('foo');

        print 'foo';
    }

    public function testExpectBazActualBaz(): void
    {
        $this->expectOutputString('baz');

        print 'baz';
    }

}
