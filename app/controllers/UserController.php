<?php

class UserController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // public function __construct($db, $username, $email, $password, $role = 'user', $image = null, $phone = null, $status = 'active') {
    //     $this->conn = $db;
    //     $this->username = htmlspecialchars(strip_tags($username));
    //     $this->email = filter_var($email, FILTER_VALIDATE_EMAIL) ? $email : throw new Exception("Invalid email format");
    //     $this->password = password_hash($password, PASSWORD_BCRYPT);
    //     $this->role = $role;
    //     $this->image = $image;
    //     $this->phone = $phone;
    //     $this->status = $status;
    // }
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $role = isset($_POST['role']) && in_array($_POST['role'], ['user', 'artiste']) ? $_POST['role'] : 'user';
            $status = isset($_POST['status']) && in_array($_POST['status'], ['active', 'banned']) ? $_POST['status'] : 'active';
            
            // Gestion du téléchargement de l'image
            $imagePath = null;
            if (!empty($_FILES['image']['name'])) {
                $targetDir = $_SERVER['DOCUMENT_ROOT'] . "/public/uploads/"; 
                $targetFile = $targetDir . basename($_FILES["image"]["name"]);
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
                    $imagePath = "/public/uploads/" . basename($_FILES["image"]["name"]);
                } else {
                    include "app/views/register.php";
                    echo "Error uploading the file.";
                    exit();
                }
            }
            if ($role === 'artiste') {
                $user = new Artiste(
                    $this->db, 
                    $_POST['username'],
                     $_POST['email'], 
                     $_POST['password'],
                     $role,
                     $status,
                    $imagePath,
                       $_POST['phone'] ?? null);
            } else {
                $user = new UserEnregister($this->db,
                 $_POST['username'], 
                 $_POST['email'], 
                 $_POST['password'],
                $role,
                $status,
                $imagePath, 
                $_POST['phone'] ?? null);
            }
    
            if ($user->register()) {
                header("Location: login");
                exit();
            } else {
                include "app/views/register.php";
                echo "Registration failed!";
                exit();
            }
        } else {
            include "app/views/register.php";
        }
    }
    
    
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];
    
            // On va chercher l'utilisateur par email
            $query = "SELECT * FROM Users WHERE email = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$email]);
            $user = $stmt->fetch();
    
            if ($user && password_verify($password, $user['password'])) {
                // Log the image path for debugging
                error_log("User image path: " . $user['image']);
                
                $_SESSION['user'] = [
                    'idUser' => $user['idUser'],
                    'username' => $user['username'],
                    'email' => $user['email'],
                    'role' => $user['role'],
                    'image' => $user['image'] ?? 'default-avatar.png',
                    'phone' => $user['phone'] ?? 'Téléphone non défini'
                ];
                if ($user['role'] == "artiste") { 
                    header("Location: uploadSong");
                } else {
                    header("Location: profile");
                }
                exit();
            } else {
                header("Location: login?error=invalid");
                exit();
            }
        } else {
            include "app/views/login.php";
        }
    }
    

    public function logout() {
        session_unset();
        session_destroy();
        header("Location: login");
        exit();
    }

    public function updateProfile() {
        if (isset($_SESSION['user'])) {
            $userId = $_SESSION['user']['idUser'];
            
            // Récupérer les albums de l'utilisateur
            $query = "SELECT * FROM Album WHERE artisteId = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$userId]);
            $albums = $stmt->fetchAll();
    
            // Récupérer les chansons de l'utilisateur
            $query = "SELECT * FROM Chanson WHERE artisteId = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$userId]);
            $songs = $stmt->fetchAll();
    
            include "app/views/profile.php";
        } else {
            header("Location: login");
            exit();
        }
    }
    
}
