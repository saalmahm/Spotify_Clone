<?php

class Chanson {
    private $idChanson;
    private $titre;
    private $image;
    private $artisteId;
    private $categorieId;
    private $db;

    public function __construct($db, $titre, $image, $artisteId, $categorieId) {
        $this->db = $db;
        $this->titre = $titre;
        $this->image = $image;
        $this->artisteId = $artisteId;
        $this->categorieId = $categorieId;
    }

    public function ajouterChansonPlaylist($playlistId) {
        // Add song to playlist
        $query = "INSERT INTO PlayListeChanson (playlist_id, chanson_id) VALUES (?, ?)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$playlistId, $this->idChanson]);
    }

    public function supprimerChansonPlaylist($playlistId) {
        // Remove song from playlist
        $query = "DELETE FROM PlayListeChanson WHERE playlist_id = ? AND chanson_id = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$playlistId, $this->idChanson]);
    }

    public function superviserAlbum($albumId) {
        // Supervise the song in an album
        $query = "INSERT INTO AlbumChanson (album_id, chanson_id) VALUES (?, ?)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$albumId, $this->idChanson]);
    }

    public function gererChansonsAimees() {
        // Manage liked songs (e.g., count likes)
        $query = "SELECT COUNT(*) AS likes FROM ChansonAimee WHERE chanson_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$this->idChanson]);
        return $stmt->fetch();
    }

    public function getTrendingSongs($limit = 4) {
        $query = "SELECT c.titre AS title, u.username AS artist, c.image AS cover, c.songFile
                  FROM Chanson c
                  JOIN Users u ON c.artisteId = u.idUser
                  ORDER BY c.titre 
                  LIMIT ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
}
?>
