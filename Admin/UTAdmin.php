<?php

declare(strict_types=1);

namespace UsersTable\Admin;

use UsersTable\Includes\UTEndpoint;

if (! class_exists('UTAdmin')) {
    class UTAdmin
    {

        /**
         * Register plugin admin options
         */
        public function registerOptions()
        {
            add_action('admin_menu', [$this, 'addSettingsPage']);
            add_action('admin_init', [$this, 'registerSettings']);
            add_action('update_option_users_table_route', [$this, 'updatedRoute'], 10, 2);
        }

        public function unregisterOptions()
        {
            unregister_setting('users_table_options', 'users_table_route');
            delete_option('users_table_route');
        }
        /**
         * Creates options page
         */
        public function addSettingsPage()
        {
            add_options_page(
                'Users Table',
                'Users Table Menu',
                'manage_options',
                'users_table',
                [$this, 'renderPluginSettingsPage']
            );
        }

        /**
         * Adds options page fields
         */
        public function renderPluginSettingsPage()
        {
            ?>
            <h2>Users Table Settings</h2>
            <form action="options.php" method="post">
                <?php
                settings_fields('users_table_options');
                do_settings_sections('users_table'); ?>
                <input
                    name="submit"
                    class="button button-primary"
                    type="submit"
                    value="<?php esc_attr_e('Save'); ?>" />
            </form>
            <?php
        }

        /**
         * Registers options fields
         */
        public function registerSettings()
        {
            register_setting(
                'users_table_options',
                'users_table_route',
                [$this, 'validateOptions']
            );
            add_settings_section(
                'endpoint_settings',
                'Endpoint Settings',
                [$this, 'endpointSectionText'],
                'users_table'
            );
            add_settings_field(
                'users_table_route',
                'Route',
                [$this, 'endpointSettingRoute'],
                'users_table',
                'endpoint_settings'
            );
        }

        /**
         * Validates entered route in options page
         *
         * @param string $input
         * @return string
         */
        public function validateOptions(string $input): string
        {
            //removes whitespaces
            $newinput = str_replace(' ', '', $input);
            if (preg_match('/[^a-zA-Z0-9-_\d]/', $newinput) !== 0) {
                $newinput = '';
                add_settings_error(
                    'users_table_route',
                    'settings_updated',
                    'Invalid route, please fix',
                    'error'
                );
            }

            return $newinput;
        }

        /**
         * Adds section to options page
         */
        public function endpointSectionText()
        {
            echo '<p>
            Here you can set the route for the endpoint where the Users Table will be displayed.
            You can only use letters, numbers, dashes, and underscores
            </p>';
        }

        /**
         * Renders options route field
         */
        public function endpointSettingRoute()
        {
            $options = get_option('users_table_route');
            echo "<input id='users_table_route' name='users_table_route' type='text' value='";
            echo ($options) ? esc_attr($options) : "";
            echo "'/>";
        }

        /**
         * Updates route field
         *
         * @param string $oldValue
         * @param string $value
         */
        public function updatedRoute(string $oldValue, string $value)
        {
            if (is_string($value)) {
                if ($oldValue !== $value) {
                    $endpoint = new UTEndpoint();
                    $endpoint->addRoute($value);
                }
            }
        }
    }
}