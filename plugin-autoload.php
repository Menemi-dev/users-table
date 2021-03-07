<?php

/**
 * Plugin Name: Users Table
 * Version: 1.0.0
 * Description: Provides a custom endpoint to display a users table
 * Author: Emilia Mencia <emilia.mencia@gmail.com>
 */

declare(strict_types=1);

require_once plugin_dir_path(__FILE__) . 'vendor/autoload.php';
use UsersTable\UsersTable;

add_action('plugins_loaded', static function () {
    // Instantiate the plugin class
    $usersTable = new UsersTable();
});
