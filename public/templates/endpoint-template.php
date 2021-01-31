<?php

/**
 * Template for displaying the users table
 */

declare(strict_types=1);

get_header();

?>

<div class="table-responsive container">

    <table id="userTable" class="table table-hover">
        <thead>
            <tr>
            <th scope="col">#</th>
            <th scope="col">Name</th>
            <th scope="col">Username</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>

</div>

<div class="container mt-5">
    <div id="userDetails" class="row mx-2">
    </div>
</div>

<?php get_footer(); ?>