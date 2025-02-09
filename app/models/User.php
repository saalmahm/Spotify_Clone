<?php

abstract class User {
    protected $idUser;
    protected $username;
    protected $email;
    protected $password;
    protected $role;
    protected $status;
    protected $image;
    protected $phone;
    protected $db;

    public function __construct($db, $username = null, $email = null, $password = null, $role = 'user', $status = 'active', $image = null, $phone = null) {
        $this->db = $db;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
        $this->status = $status;
        $this->image = $image;
        $this->phone = $phone;
    }

    // Méthodes communes
    public function register() {
        // Register a new user
        $query = "INSERT INTO Users (username, email, password, role, status, image, phone) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$this->username, $this->email, password_hash($this->password, PASSWORD_BCRYPT), $this->role, "active", $this->image, $this->phone]);
        return $this->db->lastInsertId();
    }

    public function login() {
        // Login logic
        $query = "SELECT * FROM Users WHERE email = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$this->email]);
        $user = $stmt->fetch();
        if ($user && password_verify($this->password, $user['password'])) {
            $_SESSION['user'] = $user;
            return true;
        }
        return false;
    }

    public function logout() {
        // Logout logic
        unset($_SESSION['user']);
        session_destroy();
    }

    public function updateProfile() {
        // Update user profile
        $query = "UPDATE Users SET username = ?, email = ?, phone = ?, image = ? WHERE idUser = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$this->username, $this->email, $this->phone, $this->image, $this->idUser]);
    }
}
?>
