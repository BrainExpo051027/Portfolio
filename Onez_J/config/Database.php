<?php

class Database {
    private $host = 'localhost';
    private $username = 'root';
    private $password = '';
    private $database = 'onezdb';
    private $connection;
    private $last_activity;

    public function __construct() {
        $this->connect();
    }

    private function connect() {
        try {
            // Try to connect with additional options for XAMPP
            $this->connection = new mysqli($this->host, $this->username, $this->password, $this->database);
            
            if ($this->connection->connect_error) {
                throw new Exception("Connection failed: " . $this->connection->connect_error);
            }

            // Set connection timeout and other important settings
            $this->connection->set_charset("utf8mb4");
            
            // Set longer timeouts for XAMPP (these are safe to set per session)
            $this->connection->query("SET SESSION wait_timeout=28800"); // 8 hours
            $this->connection->query("SET SESSION interactive_timeout=28800"); // 8 hours
            $this->connection->query("SET SESSION sql_mode='STRICT_TRANS_TABLES,NO_ZERO_DATE,NO_ZERO_IN_DATE,ERROR_FOR_DIVISION_BY_ZERO'");
            
            // Note: max_allowed_packet and net_timeout variables require GLOBAL privileges
            // We'll skip those to avoid permission errors
            
            $this->last_activity = time();
            
        } catch (Exception $e) {
            // Try alternative connection method
            try {
                $this->connection = new mysqli($this->host, $this->username, $this->password);
                if ($this->connection->connect_error) {
                    throw new Exception("Alternative connection failed: " . $this->connection->connect_error);
                }
                
                // Create database if it doesn't exist
                $this->connection->query("CREATE DATABASE IF NOT EXISTS `{$this->database}`");
                $this->connection->select_db($this->database);
                
                // Set the same settings (safe ones only)
                $this->connection->set_charset("utf8mb4");
                $this->connection->query("SET SESSION wait_timeout=28800");
                $this->connection->query("SET SESSION interactive_timeout=28800");
                $this->connection->query("SET SESSION sql_mode='STRICT_TRANS_TABLES,NO_ZERO_DATE,NO_ZERO_IN_DATE,ERROR_FOR_DIVISION_BY_ZERO'");
                
                $this->last_activity = time();
                
            } catch (Exception $e2) {
                die("Database connection error: " . $e2->getMessage() . "\nOriginal error: " . $e->getMessage());
            }
        }
    }

    private function checkConnection() {
        // Check if connection is still alive
        if (!$this->connection || $this->connection->ping() === false) {
            // Connection lost, reconnect
            $this->connect();
        }
        $this->last_activity = time();
    }

    public function getConnection() {
        $this->checkConnection();
        return $this->connection;
    }

    public function query($sql) {
        $this->checkConnection();
        return $this->connection->query($sql);
    }

    public function prepare($sql) {
        $this->checkConnection();
        return $this->connection->prepare($sql);
    }

    public function escape($string) {
        $this->checkConnection();
        return $this->connection->real_escape_string($string);
    }

    public function getLastInsertId() {
        $this->checkConnection();
        return $this->connection->insert_id;
    }

    public function getAffectedRows() {
        $this->checkConnection();
        return $this->connection->affected_rows;
    }

    public function beginTransaction() {
        $this->checkConnection();
        return $this->connection->begin_transaction();
    }

    public function commit() {
        $this->checkConnection();
        return $this->connection->commit();
    }

    public function rollback() {
        $this->checkConnection();
        return $this->connection->rollback();
    }

    public function close() {
        if ($this->connection) {
            $this->connection->close();
        }
    }

    public function __destruct() {
        $this->close();
    }
}
