<?php
class Playlist {
    private $idPlayListe;
    private $titre;
    private $type; 
    private $userId;
    private $anneesortie;
    private $visibilite;
    private $chansons = [];
    private $db;

    public function __construct($db, $titre, $type, $userId, $anneesortie = null, $visibilite = 'visible') {
        $this->db = $db;
        $this->titre = $titre;
        $this->type = $type;
        $this->userId = $userId;
        $this->anneesortie = $anneesortie;
        $this->visibilite = $visibilite;
    }

    public function creerPlaylist() {
        $query = "INSERT INTO PlayListe (titre, type, userId, anneesortie, visibilite) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$this->titre, $this->type, $this->userId, $this->anneesortie, $this->visibilite]);
        $this->idPlayListe = $this->db->lastInsertId(); // Récupérer l'ID de la playlist créée
        return $this->idPlayListe;
    }
    public function supprimerPlaylist() {
        $query = "DELETE FROM PlayListe WHERE idPlayListe = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$this->idPlayListe]);
    }

    // Méthode pour modifier une playlist, un album ou une liste de favoris
    public function modifierPlaylist() {
        $query = "UPDATE PlayListe SET titre = ?, type = ?, anneesortie = ?, visibilite = ? WHERE idPlayListe = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$this->titre, $this->type, $this->anneesortie, $this->visibilite, $this->idPlayListe]);
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
        $query = "SELECT 
                    a.idAlbum,
                    a.nom,
                    a.artisteId,
                    u.username as artisteName,
                    (
                        SELECT c.image 
                        FROM Chanson c 
                        JOIN AlbumChanson ac ON c.idChanson = ac.chansonId 
                        WHERE ac.albumId = a.idAlbum 
                        LIMIT 1
                    ) as cover,
                    COUNT(ac.chansonId) as nombreChansons
                  FROM Album a
                  JOIN AlbumChanson ac ON a.idAlbum = ac.albumId
                  JOIN Users u ON a.artisteId = u.idUser
                  GROUP BY a.idAlbum, a.nom, a.artisteId, u.username
                  ORDER BY nombreChansons DESC
                  LIMIT :limit";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    


    public function uploadAlbum($albumData) {
        $this->titre = $albumData['albumTitle'];
        $this->type = 'album';
        $this->userId = $albumData['artisteId'];
        $this->anneesortie = date('Y'); // Année actuelle
        $this->visibilite = 'visible';

        // Créer l'album dans la base de données
        $albumId = $this->creerPlaylist();

        // Ajouter les chansons à l'album
        foreach ($albumData['songTitles'] as $index => $titre) {
            $chansonData = [
                'titre' => $titre,
                'image' => $albumData['songImages'][$index],
                'artisteId' => $albumData['artisteId'],
                'categorieId' => $albumData['songCategories'][$index],
                'type' => 'audio', // ou 'video' selon le type de fichier
                'songFile' => $albumData['songFiles'][$index]
            ];

            // Utiliser l'objet Artiste pour téléverser la chanson
            $chansonId = $this->artiste->televerserChanson($chansonData);

            // Ajouter la chanson à l'album
            $this->ajouterChanson($chansonId);
        }

        return $albumId;
    }

    public function getAlbumDetails($albumId) {
        $query = "SELECT 
                    a.idAlbum,
                    a.nom,
                    u.username as artistename,
                    (
                        SELECT c.image 
                        FROM Chanson c 
                        JOIN AlbumChanson ac ON c.idChanson = ac.chansonId 
                        WHERE ac.albumId = a.idAlbum 
                        LIMIT 1
                    ) as cover,
                    COUNT(ac.chansonId) as nombreChansons
                  FROM Album a
                  JOIN Users u ON a.artisteId = u.idUser
                  JOIN AlbumChanson ac ON a.idAlbum = ac.albumId
                  WHERE a.idAlbum = ?
                  GROUP BY a.idAlbum, a.nom, u.username";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute([$albumId]);
        $album = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$album) {
            return null;
        }

        // Fetch songs in the album
        $songQuery = "SELECT 
                        c.idChanson, 
                        c.titre, 
                        c.songFile,
                        '00:00' as duree
                      FROM Chanson c
                      JOIN AlbumChanson ac ON c.idChanson = ac.chansonId
                      WHERE ac.albumId = ?";
        
        $songStmt = $this->db->prepare($songQuery);
        $songStmt->execute([$albumId]);
        $chansons = $songStmt->fetchAll(PDO::FETCH_ASSOC);

        return [
            'album' => $album,
            'chansons' => $chansons
        ];
    }
}
?>