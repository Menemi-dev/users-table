<?php

declare(strict_types=1);

namespace UsersTable\Includes;

if (!class_exists('UTEndpoint')) {

    class UTEndpoint
    {

        /**
         * Endpoint route
         *
         * @var string
         */
        protected $route;

        /**
         * Initialize the class and set its properties
         */
        public function __construct()
        {
            $this->route = '';
            $options = get_option('users_table_options');
            if ($options) {
                $this->route = $options['route'];
            }
        }

        /**
         * Sets new route and adds endpoint
         *
         * @param string $customRoute
         */
        public function addRoute(string $customRoute)
        {
            $this->route = $customRoute;
            update_option('new_route_added', true);
            $this->add();
        }

        /**
         * Adds endpoint and includes custom template
         */
        public function add()
        {
            add_action('init', [$this, 'addCustomEndpoint']);
            add_filter('template_include', [$this, 'includeCustomTemplate']);
        }

        /**
         * Adds a custom endpoint
         */
        public function addCustomEndpoint()
        {
            add_rewrite_endpoint($this->route, EP_PERMALINK | EP_PAGES);
            add_rewrite_rule(
                "{$this->route}/?",
                "index.php?{$this->route}={$this->route}",
                'top'
            );
            $this->settingsFlushRewrite();
        }

        /**
         * Displays instance's routev
         *
         * @return string
         */
        public function getRoute(): string
        {
            return $this->route;
        }
        /**
         * Includes plugin's template for endpoint
         *
         * @param string $template
         * @return string
         */
        public function includeCustomTemplate(string $template): string
        {
            global $wp_query;
            if (!isset($wp_query->query_vars[$this->route])) {
                return $template;
            }

            $templatePath = plugin_dir_path(__DIR__) . 'public/templates/endpoint-template.php';
            if (file_exists($templatePath)) {
                return $templatePath;
            }

            return $template;
        }

        /**
         * Flush rewrite rules
         */
        public function settingsFlushRewrite()
        {
            if (get_option('new_route_added') === true) {
                flush_rewrite_rules();
                update_option('new_route_added', false);
            }
        }
    }
}
