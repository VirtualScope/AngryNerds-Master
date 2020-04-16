<?php

include("includes/db_access_layer.php");
$Database;
// Create database connection object
try{                                    #  Host     Username Password     Database Name 
    $Database = new DatabaseAccessLayer("localhost", "root", "", "angrynerdsmaster");
} catch(Exception $e) 
{
    echo ("<p> Unable to connect to database: " . $e->getMessage() . "</p>");
    exit;
}