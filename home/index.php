<?php

require '../essentials/db_access.php';
require '../essentials/access_check.php';

$title = 'Login';
$banner = false;
$form = false;
$editor = false;
$edit_options = '';
$right_sidebar_options = '';
$left_sidebar_options='
<aside class="sidebar-left main">
    something
</aside>
';

function main_article() {
    $main = '
        <article class="main">
            <h2>Home Page</h2>
            <hr>
            <div>
            </div>
        </article>';

    return $main;
}

require '../essentials/default.php';