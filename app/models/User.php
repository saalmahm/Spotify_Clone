<?php
class User {
    private $conn;
    private $idUser;
    private $username;
    private $email;
    private $password;
    private $role;
    private $status;
    private $image;
    private $phone;

    public function __construct($db, $username, $email, $password, $role = 'user', $image = null, $phone = null, $status = 'active') {
        $this->conn = $db;
        $this->username = $username;
        $this->email = $email;
        $this->password = password_hash($password, PASSWORD_BCRYPT);
        $this->role = $role;
        $this->image = $image;
        $this->phone = $phone;
        $this->status = $status;
    }

    public function getId() {
        return $this->idUser;
    }

    public function getUsername() {
        return $this->username;
    }

    public function setUsername($username) {
        if (!empty($username)) {
            $this->username = htmlspecialchars(strip_tags($username));
        }
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->email = $email;
        }
    }

    public function getRole() {
        return $this->role;
    }

    public function setRole($role) {
        $this->role = $role;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    public function getImage() {
        return $this->image;
    }

    public function setImage($image) {
        $this->image = $image;
    }

    public function getPhone() {
        return $this->phone;
    }

    public function setPhone($phone) {
        if (preg_match('/^[0-9]{10}$/', $phone)) {
            $this->phone = $phone;
        }
    }

    public function register() {
        $query = "INSERT INTO \"User\" (username, email, password, role, status, image, phone) VALUES (:username, :email, :password, :role, :status, :image, :phone)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            'username' => $this->username,
            'email' => $this->email,
            'password' => $this->password,
            'role' => $this->role,
            'status' => $this->status,
            'image' => $this->image,
            'phone' => $this->phone
        ]);
    }

    public static function login($email, $password, $db) {
        $query = "SELECT * FROM \"User\" WHERE email = :email";
        $stmt = $db->prepare($query);
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return new User($db, $user['username'], $user['email'], $user['password'], $user['role'], $user['image'], $user['phone'], $user['status']);
        }
        return false;
    }

    public function logout() {
        session_start();
        session_unset();
        session_destroy();
        return true;
    }

    public function updateProfile($username, $email, $image, $phone) {
        $this->setUsername($username);
        $this->setEmail($email);
        $this->setImage($image);
        $this->setPhone($phone);
        
        $query = "UPDATE \"User\" SET username = :username, email = :email, image = :image, phone = :phone WHERE idUser = :idUser";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            'username' => $this->username,
            'email' => $this->email,
            'image' => $this->image,
            'phone' => $this->phone,
            'idUser' => $this->idUser
        ]);
    }

    public static function getUserById($db, $idUser) {
        $query = "SELECT * FROM \"User\" WHERE idUser = :idUser";
        $stmt = $db->prepare($query);
        $stmt->execute(['idUser' => $idUser]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

