<?php

class Database
{
    protected $dbServerName = "db-3x03.cqfhtzlpzdjp.ap-southeast-1.rds.amazonaws.com";
    protected $dbUsername = "admindeek";
    protected $dbPassword = "passworddeek";
    protected $dbName = "db_aas";
    public $con;

    public function connection()
    {
        // Create connection
        $this->con = new mysqli($this->dbServerName, $this->dbUsername, $this->dbPassword, $this->dbName);
        // Check connection
        if ($this->con->connect_error) {
            die("Connection failed: " . $this->con->connect_error);
        }
        return $this->con;
    }

}