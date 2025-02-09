<?php
class homeController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function show() {
        // Récupérer les albums populaires
        $query = "SELECT nom AS title, artisteId AS artist, 'path/to/default-album-cover.jpg' AS cover FROM Album ORDER BY nom LIMIT 4";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $popularAlbums = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Récupérer les chansons tendances
        $query = "SELECT titre AS title, artisteId AS artist, 'path/to/default-song-cover.jpg' AS cover FROM Chanson ORDER BY titre LIMIT 4";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $trendingSongs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Inclure la vue avec les données
        include __DIR__ . '/../views/home.php';
    }
}

?>
