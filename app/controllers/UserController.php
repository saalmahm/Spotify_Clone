<?php
require_once '../models/User.php';
require_once '/config/database.php'; 

class UserController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function registerUser($username, $email, $password, $role = 'user', $image = null, $phone = null) {
        $user = new User($this->db, $username, $email, $password, $role, $image, $phone);
        return $user->register();
    }

    public function loginUser($email, $password) {
        return User::login($email, $password, $this->db);
    }

    public function updateUserProfile($idUser, $username, $email, $image, $phone) {
        $userData = User::getUserById($this->db, $idUser);
        if ($userData) {
            $user = new User($this->db, $userData['username'], $userData['email'], $userData['password'], $userData['role'], $userData['image'], $userData['phone'], $userData['status']);
            return $user->updateProfile($username, $email, $image, $phone);
        }
        return false;
    }
}

// Gestion des requêtes POST
$controller = new UserController($db);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['register'])) {
        $controller->registerUser($_POST['username'], $_POST['email'], $_POST['password']);
        header('Location: login.php');
    }
    if (isset($_POST['login'])) {
        $user = $controller->loginUser($_POST['email'], $_POST['password']);
        if ($user) {
            session_start();
            $_SESSION['user'] = $user;
            header('Location: dashboard.php');
        } else {
            echo "Identifiants incorrects";
        }
    }
}
