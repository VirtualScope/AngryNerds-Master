<?php

include("includes/db_access_layer.php");
$database;
// Create database connection object
try{                                    #  Host        Database Name   Username Password
    $database = new DatabaseAccessLayer("localhost", "angrynerdsmaster", "root", "");
} catch(Exception $e) 
{
    echo ("<p> Unable to connect to database: " . $e->getMessage() . "</p>");
    exit;
}