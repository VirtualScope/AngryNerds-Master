<?php

Class DatabaseAccessLayer {
    // Properties
    private $__dbcn;
    private $__db_host;
    private $__db_user;
    private $__db_passwd;
    private $__db_connection_status;

    function __construct($db_host, $db_name, $db_user, $db_pass)
    {  
        // Load DB config.
        #DEFINE('DATABASE_HOST',     $db_host);
        #DEFINE('DATABASE_DATABASE', $db_name);
        #DEFINE('DATABASE_USER',     $db_user);
        #DEFINE('DATABASE_PASSWORD', $db_pass);

        // Connect to DB
        $this->__dbcn = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_DATABASE);
        $this->__dbcn->set_charset("utf8");
        if (mysqli_connect_errno()) {
            throw new Exception("Database Connection Error");
            $this->__db_connection_status = false;
            return;
        } 
        else 
            $this->__db_connection_status = true;
    }
    function query($sql)                      { return $this->__dbcn->query($sql); }
    function get_database_connection_status() { return $this->__db_connection_status; }
}