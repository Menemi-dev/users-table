# Users Table
Users Table is a plugin that makes available a custom endpoint on the WordPress site, when visited, the plugin sends an HTTP request to a REST API endpoint at https://jsonplaceholder.typicode.com, parses the JSON response and use it to build and display an HTML table.

## Installation
### Requirements
<ul>
<li>PHP 7.2 or greater</li>
<li>WordPress 4.4 or greater</li>
</ul>

## Steps
<ol>
<li>Clone or download plugin folder from github https://github.com/Menemi-dev/users-table.git</li>
<li>Upload plugin folder to the '/wp-content/plugins/' directory</li>
<li>Run composer install</li>
<li>Activate the plugin through the 'Plugins' menu in WordPress</li>
</ol>

## Usage
The plugin uses a default endpoint on activate in /users. To set a route go to the admin area, Settings->Users Table Menu. Enter the new name of the endpoint route to use.

To display the users' table, go to the endpoint set before. The table lists all users, to see a user's detail click on the user and the data will be displayed in an area under the table.

## Cache for HTTP requests
The first request to the API brings all the users with their respective details. This data is cached using a local object because we only need it to exist on the page they are created, we don't need to allocate the data across the site.

## Styling
The plugin uses minimum custom styling to better the blend with the current theme's general styling
