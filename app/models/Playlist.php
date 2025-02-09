<?php

class Playlist {
    private $idPlayListe;
    private $titre;
    private $type;
    private $userId;
    private $anneeSortie;
    private $visibilite;
    private $chansons = [];
    private $db;

    public function __construct($db, $titre, $type, $userId, $anneeSortie, $visibilite) {
        $this->db = $db;
        $this->titre = $titre;
        $this->type = $type;
        $this->userId = $userId;
        $this->anneeSortie = $anneeSortie;
        $this->visibilite = $visibilite;
    }

    public function supprimerPlaylist() {
        // Delete playlist
        $query = "DELETE FROM PlayListe WHERE idPlayListe = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$this->idPlayListe]);
    }

    public function creerPlaylist() {
        // Create playlist
        $query = "INSERT INTO PlayListe (titre, type, user_id, anneeSortie, visibilite) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$this->titre, $this->type, $this->userId, $this->anneeSortie, $this->visibilite]);
    }

    public function modifierPlaylist() {
        // Modify playlist
        $query = "UPDATE PlayListe SET titre = ?, type = ?, anneeSortie = ?, visibilite = ? WHERE idPlayListe = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$this->titre, $this->type, $this->anneeSortie, $this->visibilite, $this->idPlayListe]);
    }

    public function ajouterChanson($chansonId) {
        // Add song to playlist
        $query = "INSERT INTO PlayListeChanson (playlist_id, chanson_id) VALUES (?, ?)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$this->idPlayListe, $chansonId]);
    }

    public function retirerChanson($chansonId) {
        // Remove song from playlist
        $query = "DELETE FROM PlayListeChanson WHERE playlist_id = ? AND chanson_id = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$this->idPlayListe, $chansonId]);
    }
}
?>
