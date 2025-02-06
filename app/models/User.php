<?php
class User {
    private $conn;
    private $nom;
    private $email;
    private $role;
    private $mot_de_passe;

    public function __construct($db,$nom, $email, $role, $mot_de_passe) {
        $this->conn = $db;
        $this->nom = $nom;
        $this->email = $email;
        $this->role = $role;
        $this->mot_de_passe = $mot_de_passe;
    }

    public function register() {
        $hashed_password = password_hash($this->mot_de_passe, PASSWORD_BCRYPT);
        $query = "INSERT INTO Utilisateur (nom, email, mot_de_passe, role) VALUES (:nom, :email, :password, :role)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([ 'nom' => $this->nom, 'email' => $this->email, 'password' => $hashed_password, 'role' => $this->role ]);
    }

    static function login($email, $password, $db) {
        $query = "SELECT * FROM Utilisateur WHERE email = :email";
        $stmt = $db->prepare($query);
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['mot_de_passe'])) {
            return $user;
        }
        return false;
    }
}
