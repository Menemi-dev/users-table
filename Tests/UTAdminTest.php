<?php

namespace UsersTable\Tests\UTAdminTest;

use UsersTable\Tests\TestCase as TestCase;
use UsersTable\Admin\UTAdmin;
use function Brain\Monkey\Functions\stubs;

class UTAdminTest extends TestCase
{
    /**
     * Object instance
     *
     * @var UTAdmin
     */
    private $instance;

    public function setUp(): void
    {
        parent::setUp();
        $this->instance = new UTAdmin('1.0.0');

        stubs(['add_settings_error' => 'Error message']);
        stubs(['get_option' => 'user']);
    }

    /**
     * Tests the return value of UTAmin::validateOptions
     */
    public function testValidateOptions()
    {
        $values = ['user', 'user 1', 'user@user', ' ', 'false'];
        $expected = ['user', 'user1', '', '', 'false'];
        foreach ($values as $key => $value) {
            $actual = $this->instance->validateOptions($value);
            $this->assertEquals($expected[$key], $actual);
        }
    }

    public function testEndpointSettingRoute()
    {
        $expected = "<input id='users_table_route' name='users_table_route' type='text' value='user'/>";
        $this->instance->endpointSettingRoute();
        $this->expectOutput($expected);
    }
}
