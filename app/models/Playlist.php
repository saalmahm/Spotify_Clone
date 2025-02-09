<?php
class Playlist {
    private $idPlayListe;
    private $titre;
    private $type; // Peut être 'album', 'playlist' ou 'favoris'
    private $userId;
    private $anneeSortie;
    private $visibilite;
    private $chansons = [];
    private $db;

    public function __construct($db, $titre, $type, $userId, $anneeSortie = null, $visibilite = 'visible') {
        $this->db = $db;
        $this->titre = $titre;
        $this->type = $type;
        $this->userId = $userId;
        $this->anneeSortie = $anneeSortie;
        $this->visibilite = $visibilite;
    }

    // Méthode pour créer une playlist, un album ou une liste de favoris
    public function creerPlaylist() {
        $query = "INSERT INTO PlayListe (titre, type, userId, anneeSortie, visibilite) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$this->titre, $this->type, $this->userId, $this->anneeSortie, $this->visibilite]);
        $this->idPlayListe = $this->db->lastInsertId(); // Récupérer l'ID de la playlist créée
        return $this->idPlayListe;
    }

    // Méthode pour supprimer une playlist, un album ou une liste de favoris
    public function supprimerPlaylist() {
        $query = "DELETE FROM PlayListe WHERE idPlayListe = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$this->idPlayListe]);
    }

    // Méthode pour modifier une playlist, un album ou une liste de favoris
    public function modifierPlaylist() {
        $query = "UPDATE PlayListe SET titre = ?, type = ?, anneeSortie = ?, visibilite = ? WHERE idPlayListe = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$this->titre, $this->type, $this->anneeSortie, $this->visibilite, $this->idPlayListe]);
    }

    // Méthode pour ajouter une chanson à une playlist, un album ou une liste de favoris
    public function ajouterChanson($chansonId) {
        $query = "INSERT INTO PlayListeChanson (playListeId, chansonId) VALUES (?, ?)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$this->idPlayListe, $chansonId]);
    }

    public function retirerChanson($chansonId) {
        $query = "DELETE FROM PlayListeChanson WHERE playListeId = ? AND chansonId = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$this->idPlayListe, $chansonId]);
    }

    public function getChansons() {
        $query = "SELECT c.* FROM Chanson c
                  JOIN PlayListeChanson pc ON c.idChanson = pc.chansonId
                  WHERE pc.playListeId = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$this->idPlayListe]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function gererAlbum($nomAlbum, $artisteId) {
        if ($this->type !== 'album') {
            throw new Exception("Cette méthode est réservée aux albums.");
        }

        $query = "INSERT INTO Album (nom, artisteId) VALUES (?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$nomAlbum, $artisteId]);
        $albumId = $this->db->lastInsertId();

        foreach ($this->chansons as $chansonId) {
            $query = "INSERT INTO AlbumChanson (albumId, chansonId) VALUES (?, ?)";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$albumId, $chansonId]);
        }

        return $albumId;
    }
    public function getPopularAlbums($limit = 5) {
        $query = "SELECT a.nom, a.artisteId, COUNT(ac.chansonId) as nombreChansons
                  FROM Album a
                  JOIN AlbumChanson ac ON a.idAlbum = ac.albumId
                  GROUP BY a.nom, a.artisteId
                  ORDER BY nombreChansons DESC
                  LIMIT ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
}
?>