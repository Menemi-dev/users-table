<?php

namespace UsersTable\Tests;

use function Brain\Monkey\setUp;
use function Brain\Monkey\tearDown;
use function Brain\Monkey\Functions\stubs;
use Mockery;
use PHPUnit\Framework\TestCase as PhpUniTestCase;

class TestCase extends PhpUniTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        setUp();

        stubs(['esc_attr' => null]);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
        tearDown();
    }

    protected function expectOutput($expected, $description = '')
    {
        $output = \ob_get_contents();
        \ob_clean();

        $output = \preg_replace('|\R|', "\r\n", $output);
        $expected = \preg_replace('|\R|', "\r\n", $expected);

        $this->assertEquals($expected, $output, $description);
    }
}
