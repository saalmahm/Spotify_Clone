<?php

class homeController {
    private $db;
    private $albumModel;
    private $chansonModel;

    public function __construct($db) {
        $this->db = $db;
        $this->albumModel = new Playlist($db, 'Titre par défaut', 'type par défaut', 1); // Arguments requis ajoutés
        $this->chansonModel = new Chanson($db, 'Titre par défaut', 'image par défaut', 1, 1); // Arguments requis ajoutés
    }

    public function show() {
        $popularAlbums = $this->albumModel->getPopularAlbums();
        $trendingSongs = $this->chansonModel->getTrendingSongs();

        include __DIR__ . '/../views/home.php';
    }
}


?>