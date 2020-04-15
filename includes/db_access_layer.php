<?php

Class DatabaseAccessLayer {
    // Properties
    private $__dbcn;
    private $__db_host;
    private $__db_user;
    private $__db_passwd;
    private $__db_connection_status;

    function __construct($db_host, $db_user, $db_pass, $db_name)
    {  
        // Connect to DB
        $this->__dbcn = new mysqli($db_host, $db_user, $db_pass, $db_name);
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