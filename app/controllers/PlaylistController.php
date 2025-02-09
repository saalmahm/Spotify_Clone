<?php
require_once __DIR__ . '/../models/Playlist.php';

class PlaylistController {
    private $db;
    private $playlistModel;

    public function __construct($db) {
        $this->db = $db;
        $this->playlistModel = new Playlist($db, '', '', 0);
    }

    public function showAlbumDetails() {
        // Vérifier si l'ID de l'album est présent
        if (!isset($_GET['id'])) {
            // Rediriger vers la page d'accueil si pas d'ID
            header('Location: index.php?route=home');
            exit();
        }

        $albumId = intval($_GET['id']);
        $albumDetails = $this->playlistModel->getAlbumDetails($albumId);

        if (!$albumDetails) {
            // Rediriger si l'album n'existe pas
            header('Location: index.php?route=home');
            exit();
        }

        // Charger la vue des détails de l'album
        extract($albumDetails);
        require __DIR__ . '/../views/albumDetails.php';
    }
}
