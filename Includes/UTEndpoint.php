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
            $this->route = get_option('users_table_route');
            if ($this->route === false) {
                update_option('users_table_route', 'users');
                $this->addRoute('users');
            }
        }

        /**
         * Displays instance's route
         *
         * @return string
         */
        public function route(): string
        {
            return $this->route;
        }

        /**
         * Sets new route and adds endpoint
         *
         * @param string $newRoute
         */
        public function addRoute(string $newRoute)
        {
            $this->route = $newRoute;
            update_option('new_route_added', true);
            $this->add();
        }

        /**
         * Adds endpoint and includes custom template
         */
        public function add()
        {
            add_action('init', [$this, 'addCustomEndpoint']);
            add_action('setup_theme', [$this, 'settingsFlushRewrite']);
        }

        /**
         * Adds a custom endpoint
         */
        public function addCustomEndpoint()
        {
            add_rewrite_endpoint($this->route, EP_PERMALINK | EP_PAGES);
            add_rewrite_rule(
                "{$this->route}/?$",
                "index.php?{$this->route}={$this->route}",
                'top'
            );
        }

        /**
         * Flush rewrite rules
         */
        public function settingsFlushRewrite()
        {
            if (filter_var(get_option('new_route_added'), FILTER_VALIDATE_BOOLEAN) === true) {
                flush_rewrite_rules();
                update_option('new_route_added', false);
            }
        }
    }
}
