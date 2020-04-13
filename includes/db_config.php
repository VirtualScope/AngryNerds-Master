<?php

// DB config.
DEFINE('DATABASE_HOST', 'localhost');
DEFINE('DATABASE_DATABASE', 'angrynerdsmaster');
DEFINE('DATABASE_USER', 'root');
DEFINE('DATABASE_PASSWORD', '');

// Connect to DB
$dbcn = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_DATABASE);
$dbcn->set_charset("utf8");
if (mysqli_connect_errno()) {
    echo "<p>Error creating database connection.</p>";
    exit;
}
