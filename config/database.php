<?php
class Database {
    private $host = "localhost";
    private $db_name = "spotify_clone";
    private $username = "postgres";
    private $password = "salmahm";
    private $conn;

    public function connect() {
        try {
            $this->conn = new PDO("pgsql:host={$this->host};dbname={$this->db_name}", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
        return $this->conn;
    }
}

$db = (new Database)->connect() ;