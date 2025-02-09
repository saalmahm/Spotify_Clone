<?php

class Artiste extends User {

    public function televerserChanson($chansonData) {
        // if (empty($chansonData['titre']) || empty($chansonData['image']) || empty($chansonData['artisteId']) || empty($chansonData['categorieId']) || empty($chansonData['type'])) {
        //     error_log('Validation failed: Some fields are empty');
        //     return false;
        // }
    
        $query = "INSERT INTO Chanson (titre, image, type, artisteId, categorieId,songFile) VALUES (?, ?, ?, ?, ?,?)";
        $stmt = $this->db->prepare($query);
        if ($stmt === false) {
            error_log('Failed to prepare SQL statement: ' . print_r($this->db->errorInfo(), true));
            return false;
        }
    
        $result = $stmt->execute([
            $chansonData['titre'], 
            $chansonData['image'], 
            $chansonData['type'], 
            $chansonData['artisteId'], 
            $chansonData['categorieId'],
            $chansonData['songFile']

        ]);
    
        if ($result) {
            error_log('Chanson ajoutée avec succès dans la base de données');
        } else {
            error_log('Erreur lors de l\'ajout de la chanson : ' . print_r($stmt->errorInfo(), true));
        }
        return $result;
    }
    
    
    public function getCategories() {
        $query = "SELECT * FROM Category";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function organiserChansons($chansons) {
        // Organize songs into playlists or albums
        // Example: Adding songs to an album
        foreach ($chansons as $chanson) {
            $query = "INSERT INTO AlbumChanson (album_id, chanson_id) VALUES (?, ?)";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$chanson['album_id'], $chanson['chanson_id']]);
        }
    }

    public function gererAlbums($albumData) {
        // Manage albums (create/update/delete)
        if (isset($albumData['action']) && $albumData['action'] == 'create') {
            $query = "INSERT INTO Album (titre, artiste_id, anneeSortie) VALUES (?, ?, ?)";
            $stmt = $this->db->prepare($query);
            return $stmt->execute([$albumData['titre'], $this->idUser, $albumData['anneeSortie']]);
        }
        // Add more actions (e.g., delete, update)
    }

    public function viewGlobalStatistics() {
        // Get statistics for the artist (e.g., total songs, albums, listeners)
        $query = "SELECT COUNT(idChanson) as total_songs FROM Chanson WHERE artiste = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$this->idUser]);
        return $stmt->fetch();
    }
}
?>
