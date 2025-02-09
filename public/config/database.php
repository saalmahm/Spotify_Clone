<?php

class Database {
    private $host = "localhost";
    private $db_name = "musify_clone";
    private $username = "postgres";
    private $password = "salmahm";
    private $conn;

    public function connect() {
        try {
            $this->conn = new PDO(
                "pgsql:host={$this->host};dbname={$this->db_name}", 
                $this->username, 
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::ATTR_AUTOCOMMIT => false
                ]
            );
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
        return $this->conn;
    }
}
