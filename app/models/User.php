<?php
class User {
    protected $idUser;
    protected $username;
    protected $email;
    protected $password;
    protected $role;
    protected $status;
    protected $image;
    protected $phone;
    protected $db;

    public function __construct($db, $idUser, $username = null, $email = null, $password = null, $role = 'user', $status = 'active', $image = null, $phone = null) {
        $this->db = $db;
        $this->idUser = $idUser;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
        $this->status = $status;
        $this->image = $image;
        $this->phone = $phone;
    }

    public function register() {
        $query = "INSERT INTO Users (username, email, password, role, status, image, phone) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$this->username, $this->email, password_hash($this->password, PASSWORD_BCRYPT), $this->role, "active", $this->image, $this->phone]);
        return $this->db->lastInsertId();
    }

    public function getUserByEmail($email) {
        $query = "SELECT * FROM Users WHERE email = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function login() {
        $user = $this->getUserByEmail($this->email);
        if ($user && password_verify($this->password, $user['password'])) {
            $this->idUser = $user['iduser'];
            return true;
        }
        return false;
    }
    

    public function logout() {
        unset($_SESSION['user']);
        session_destroy();
    }

    public function updateProfile() {
        $query = "UPDATE Users SET username = ?, email = ?, phone = ?, image = ? WHERE iduser = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$this->username, $this->email, $this->phone, $this->image, $this->idUser]);
    }
}
?>
