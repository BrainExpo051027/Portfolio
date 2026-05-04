<?php

$server = 'localhost';
$username = 'root';
$password = '';
$dbname = 'blk7';

// Check if class already exists to prevent redeclaration error
if (!class_exists('DBConn')) {
    class DBConn {
        private $server;
        private $username;
        private $password;
        private $dbname;
        
        public function __construct($server, $username, $password, $dbname) {
            $this->server = $server;
            $this->username = $username;
            $this->password = $password;
            $this->dbname = $dbname;
        }
        
        public function conn() {
            $conn = new mysqli($this->server, $this->username, $this->password, $this->dbname);
            
            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            
            // Set charset to utf8mb4 for better Unicode support
            $conn->set_charset("utf8mb4");
            
            return $conn;
        }
    }
}

// Only create connection if it doesn't already exist
if (!isset($conn)) {
    $connect = new DBConn($server, $username, $password, $dbname);
    $conn = $connect->conn();
}

?>