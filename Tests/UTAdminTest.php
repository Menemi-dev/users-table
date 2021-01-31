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
        stubs(['get_option' => ['route' => 'user']]);
    }

    /**
     * Tests the return value of UTAmin::validateOptions
     */
    public function testValidateOptions()
    {
        $values = ['user', 'user 1', 'user@user', ' ', 'false'];
        $expected = ['user', 'user1', '', '', 'false'];
        foreach ($values as $key => $value) {
            $actual = $this->instance->validateOptions(['route' => $value]);
            $this->assertEquals(['route' => $expected[$key]], $actual);
        }
    }

    public function testEndpointSettingRoute()
    {
        $expected = "<input id='endpoint_setting_route' name='users_table_options[route]' type='text' value='user'/>";
        $this->instance->endpointSettingRoute();
        $this->expectOutput($expected);
    }
}
