<?php

/**
 * Plugin Name: Users Table
 * Version: 1.0.0
 * Description: Provides a custom endpoint to display a users table
 * Author: Emilia Mencia <emilia.mencia@gmail.com>
 */

declare(strict_types=1);

namespace UsersTable;

require_once plugin_dir_path(__FILE__) . '/vendor/autoload.php';

use UsersTable\Admin\UTAdmin;
use UsersTable\Includes\UTEndpoint;

if (! class_exists('UsersTable')) {

    class UsersTable
    {

        /**
         * Plugin version
         *
         * @var string
         */
        private $version;

        /**
         * Endpoint instance
         *
         * @var UTEndpoint
         */
        private $endpoint;

        /**
         * UTAdmin instance
         *
         * @var UTAdmin
         */
        private $admin;

        /**
         * Initialize the class and set its properties
         */

        public function __construct()
        {
            $this->version = '1.0.0';

            if (file_exists(plugin_dir_path(__FILE__) . '/vendor/autoload.php')) {
                require_once plugin_dir_path(__FILE__) . '/vendor/autoload.php';
            }

            //Add endpoint
            $this->endpoint = new UTEndpoint();
            $this->endpoint->add();

            $this->setupActions();

            //Load assets
            $isAdmin = is_admin();
            if ($isAdmin) {
                $this->loadAdmin();
            }
            if (! $isAdmin) {
                $this->loadPublic();
            }
        }

        /**
         * Setting up main plugin hooks
         */
        public function setupActions()
        {
            register_activation_hook(__FILE__, [$this, 'activate']);
            register_deactivation_hook(__FILE__, [$this, 'deactivate']);
        }

        /**
         * Activate callback. When the option doesn't exist, set default value.
         */
        public static function activate()
        {
            flush_rewrite_rules();
        }

        /**
         * Deactivate callback
         */
        public static function deactivate()
        {
            $utAdmin = new UTAdmin();
            $utAdmin->unregisterOptions();
            flush_rewrite_rules();
        }

        /**
         * Register all of the hooks related to the admin area functionality
         */
        private function loadAdmin()
        {
            $this->admin = new UTAdmin($this->version);
            $this->admin->registerOptions();
        }

        /**
         * Register all of the hooks related to the public area functionality
         */
        private function loadPublic()
        {
            add_action('wp_enqueue_scripts', [$this, 'publicAssets']);
            add_filter('template_include', [$this, 'includeCustomTemplate']);
        }

        /**
         * Includes plugin's template for the endpoint
         *
         * @param string $template
         * @return string
         */
        public function includeCustomTemplate(string $template): string
        {
            global $wp_query;
            if (!isset($wp_query->query_vars[$this->endpoint->route()])) {
                return $template;
            }
            $templatePath = plugin_dir_path(__FILE__) . 'public/templates/endpoint-template.php';
            if (file_exists($templatePath)) {
                return $templatePath;
            }

            return $template;
        }

        /**
         * Registers public scripts
         */
        public function publicAssets()
        {
            global $wp_query;
            if (isset($wp_query->query_vars[$this->endpoint->route()])) {
                wp_enqueue_script(
                    'ut-public-script',
                    plugin_dir_url(__FILE__) . 'public/js/ut-public-script.js',
                    ['jquery'],
                    $this->version,
                    true
                );
                wp_enqueue_style(
                    'bootstrap-4',
                    plugin_dir_url(__FILE__) . 'public/css/bootstrap.min.css',
                    [],
                    '4.5.3'
                );
                wp_enqueue_style(
                    'ut-public-style',
                    plugin_dir_url(__FILE__) . 'public/css/ut-public-style.css',
                    [],
                    $this->version
                );
            }
        }
    }

}

// Instantiate the plugin class
$usersTable = new UsersTable();
