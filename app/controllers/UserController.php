<?php

class UserController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function register() {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $role = isset($_POST['role']) && in_array($_POST['role'], ['user', 'artiste']) ? $_POST['role'] : 'user';
                $status = isset($_POST['status']) && in_array($_POST['status'], ['active', 'banned']) ? $_POST['status'] : 'active';
                
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
    
                $username = $_POST['username'];
                $email = $_POST['email'];
                $password = $_POST['password'];
                $phone = $_POST['phone'] ?? null;
    
                if ($role === 'artiste') {
                    $user = new Artiste(
                        $this->db, 
                        null, 
                        $username,
                        $email, 
                        $password,
                        $role,
                        $status,
                        $imagePath,
                        $phone
                    );
                } else {
                    $user = new UserEnregister(
                        $this->db,
                        null, 
                        $username, 
                        $email, 
                        $password,
                        $role,
                        $status,
                        $imagePath, 
                        $phone
                    );
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
        } catch (Exception $e) {
            error_log('Error: ' . $e->getMessage());
            include "app/views/register.php";
            echo "Registration failed due to an unexpected error!";
            exit();
        }
    }
    
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];
    
            $userModel = new User($this->db, null, null, $email, $password);
    
            if ($userModel->login()) {
                $user = $userModel->getUserByEmail($email);
                $_SESSION['user'] = [
                    'iduser' => $user['iduser'],
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
            $userId = $_SESSION['user']['iduser'];
            
            $query = "SELECT * FROM Album WHERE artisteId = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$userId]);
            $albums = $stmt->fetchAll();
    
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
