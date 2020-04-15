<?php

include("includes/db_access_layer.php");
$database;
// Create database connection object
try{                                    #  Host     Username Password     Database Name 
    $database = new DatabaseAccessLayer("localhost", "root", "", "angrynerdsmaster");
} catch(Exception $e) 
{
    echo ("<p> Unable to connect to database: " . $e->getMessage() . "</p>");
    exit;
}