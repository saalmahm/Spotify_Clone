<?php
class Artiste extends User {
    protected $db;

    public function __construct($db, $idUser) {
        $this->db = $db;
        if (!$this->db || !($this->db instanceof PDO)) {
            throw new Exception("Erreur de connexion à la base de données dans le modèle Artiste");
        }
        $this->idUser = $idUser;
    }

    public function getDb() {
        return $this->db;
    }

    public function televerserChanson($chansonData) {
        try {
            // Validation des données requises
            $required = ['titre', 'artisteId', 'categorieId', 'type', 'songFile'];
            foreach ($required as $field) {
                if (empty($chansonData[$field])) {
                    throw new Exception("Le champ {$field} est requis");
                }
            }

            // Vérifier si la catégorie existe
            $stmt = $this->db->prepare("SELECT idCategory FROM Category WHERE idCategory = ?");
            $stmt->execute([$chansonData['categorieId']]);
            if (!$stmt->fetch()) {
                throw new Exception("La catégorie spécifiée n'existe pas");
            }

            // Vérifier si l'artiste existe
            $stmt = $this->db->prepare("SELECT idUser FROM Users WHERE idUser = ? AND role = 'artiste'");
            $stmt->execute([$chansonData['artisteId']]);
            if (!$stmt->fetch()) {
                throw new Exception("L'artiste spécifié n'existe pas ou n'a pas le rôle d'artiste");
            }

            $query = "INSERT INTO Chanson (titre, image, type, artisteId, categorieId, songFile) 
                     VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($query);
            
            $result = $stmt->execute([
                $chansonData['titre'],
                $chansonData['image'] ?? null,
                $chansonData['type'],
                $chansonData['artisteId'],
                $chansonData['categorieId'],
                $chansonData['songFile']
            ]);

            if (!$result) {
                throw new Exception('Erreur lors de l\'ajout de la chanson: ' . implode(", ", $stmt->errorInfo()));
            }

            return $this->db->lastInsertId();

        } catch (Exception $e) {
            error_log('Erreur dans televerserChanson: ' . $e->getMessage());
            throw $e;
        }
    }

    public function gererAlbums($albumData) {
        try {
            // Vérifier si une transaction est déjà en cours
            if (!$this->db->inTransaction()) {
                $this->db->beginTransaction();
            }

            // Validation des données de l'album
            if (empty($albumData['nom'])) {
                throw new Exception("Le nom de l'album est requis");
            }

            if (empty($albumData['chansons']) || !is_array($albumData['chansons'])) {
                throw new Exception("L'album doit contenir au moins une chanson");
            }

            // Créer l'album
            $query = "INSERT INTO Album (nom, artisteId) VALUES (:nom, :artisteId) RETURNING idAlbum";
            $stmt = $this->db->prepare($query);
            $success = $stmt->execute([
                ':nom' => $albumData['nom'],
                ':artisteId' => $this->idUser
            ]);

            if (!$success) {
                throw new Exception('Erreur lors de la création de l\'album: ' . implode(", ", $stmt->errorInfo()));
            }

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$result) {
                throw new Exception("Erreur lors de la récupération de l'ID de l'album");
            }
            $albumId = $result['idalbum'];
            $chansonIds = [];

            // Ajouter les chansons à l'album
            foreach ($albumData['chansons'] as $index => $chanson) {
                try {
                    // Préparer les données de la chanson
                    $chansonData = [
                        'titre' => $chanson['titre'],
                        'image' => $chanson['image'] ?? null,
                        'artisteId' => $this->idUser,
                        'categorieId' => $chanson['categorieId'],
                        'type' => 'audio',
                        'songFile' => $chanson['songFile']
                    ];

                    // Ajouter la chanson
                    $chansonId = $this->televerserChanson($chansonData);

                    if (!$chansonId) {
                        throw new Exception('Erreur lors de l\'ajout d\'une chanson');
                    }

                    $chansonIds[] = $chansonId;

                    // Lier la chanson à l'album
                    $query = "INSERT INTO AlbumChanson (albumId, chansonId) VALUES (:albumId, :chansonId)";
                    $stmt = $this->db->prepare($query);
                    $success = $stmt->execute([
                        ':albumId' => $albumId,
                        ':chansonId' => $chansonId
                    ]);

                    if (!$success) {
                        throw new Exception('Erreur lors de la liaison chanson-album');
                    }

                } catch (Exception $e) {
                    throw new Exception("Erreur avec la chanson " . ($index + 1) . ": " . $e->getMessage());
                }
            }

            if (!$this->db->inTransaction()) {
                $this->db->commit();
            }

            return [
                'success' => true,
                'albumId' => $albumId,
                'chansons' => $chansonIds,
                'message' => 'Album créé avec succès'
            ];

        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            error_log('Erreur lors de la création de l\'album: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function viewGlobalStatistics() {
        $query = "SELECT COUNT(idChanson) as total_songs FROM Chanson WHERE artisteId = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$this->idUser]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getCategories() {
        $query = "SELECT * FROM Category";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function organiserChansons($chansons) {
        foreach ($chansons as $chanson) {
            $query = "INSERT INTO AlbumChanson (album_id, chanson_id) VALUES (?, ?)";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$chanson['album_id'], $chanson['chanson_id']]);
        }
    }

    public function getAlbumDetails($albumId) {
        // Récupérer les détails de l'album
        $query = "SELECT a.*, COUNT(ac.chansonId) as nombre_chansons 
                 FROM Album a 
                 LEFT JOIN AlbumChanson ac ON a.idAlbum = ac.albumId 
                 WHERE a.idAlbum = ? AND a.artisteId = ?
                 GROUP BY a.idAlbum, a.nom, a.artisteId";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$albumId, $this->idUser]);
        $album = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($album) {
            // Récupérer les chansons de l'album
            $query = "SELECT c.* 
                     FROM Chanson c 
                     JOIN AlbumChanson ac ON c.idChanson = ac.chansonId 
                     WHERE ac.albumId = ? 
                     ORDER BY c.titre";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$albumId]);
            $album['chansons'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        return $album;
    }
}

?>
