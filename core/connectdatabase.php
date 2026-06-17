<?php
require_once 'config/database.php';

class connectdatabase
{
    private $conn;
    public function connect()
    {
        $this->conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
        if ($this->conn->connect_error) {
            die("Connection failed:" . $this->conn->connect_error);
        } else {
            return $this->conn;
        }
    }
    public function close()
    {
        $this->conn->close();
    }
}
