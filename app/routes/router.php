<?php
require_once __DIR__ . '/../controllers/ArtisteController.php';
require_once __DIR__ . '/../controllers/PlaylistController.php';

class Router {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function run() {
        $requestUri = parse_url($_GET['route'], PHP_URL_PATH);
        $requestUri = trim($requestUri, '/');
    
        // Routes par défaut
        $routes = [
            'home' => ['homeController', 'show'],
            'login' => ['UserController', 'login'],
            'profile' => ['UserController', 'updateProfile'],
            'register' => ['UserController', 'register'],
            'uploadSong' => ['ArtisteController', 'uploadSong'],
            'uploadAlbum'=> ['ArtisteController', 'uploadAlbum'],
            'logout' => ['UserController', 'logout'],
            'album' => ['PlaylistController', 'showAlbumDetails'],
        ];
    
        // Vérification de la route et appel du contrôleur
        if (isset($routes[$requestUri])) {
            list($controller, $action) = $routes[$requestUri];
    
            // Vérification si l'utilisateur est authentifié pour certaines routes
            if ($requestUri !== 'login' && $requestUri !== 'register' && !isset($_SESSION['user'])) {
                header('Location: login');
                exit();
            }
            $controllerInstance = new $controller($this->db);
            call_user_func([$controllerInstance, $action]);
           
        }
    }
    
}