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
            add_action('updated_option', [$this, 'updatedRoute'], 10, 3);
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
                'users-table-menu',
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
            register_setting('users_table_options', 'users_table_options', [ $this, 'validateOptions']);
            add_settings_section(
                'endpoint_settings',
                'Endpoint Settings',
                [$this, 'endpointSectionText'],
                'users_table'
            );
            add_settings_field(
                'endpoint_setting_route',
                'Route',
                [$this, 'endpointSettingRoute'],
                'users_table',
                'endpoint_settings'
            );
        }

        /**
         * Validates entered route in options page
         *
         * @param array $input
         * @return array
         */
        public function validateOptions(array $input): array
        {
            //removes whitespaces
            $newinput['route'] = str_replace(' ', '', $input['route']);
            if (preg_match('/[^a-zA-Z0-9-_\d]/', $newinput['route']) !== 0) {
                $newinput['route'] = '';
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
            echo '<p>Here you can set the route for the endpoint where the Users Table will be displayed.
            You can only use letters, numbers, dashes, and underscores</p>';
        }

        /**
         * Renders options route field
         */
        public function endpointSettingRoute()
        {
            $options = get_option('users_table_options');
            echo "<input id='endpoint_setting_route' name='users_table_options[route]' type='text' value='";
            echo ($options) ? esc_attr($options['route']) : "";
            echo "'/>";
        }

        /**
         * Updates route field
         *
         * @param string $optionName
         * @param mixed $oldValue
         * @param mixed $value
         */
        public function updatedRoute(string $optionName, $oldValue, $value)
        {
            if ($optionName === "users_table_options") {
                if ($oldValue['route'] !== $value['route']) {
                    $endpoint = new UTEndpoint();
                    $endpoint->addRoute($value['route']);
                }
            }
        }
    }
}