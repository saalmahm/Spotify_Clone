<?php
class ArtisteController {
    private $artiste_model;
    private $db;

    public function __construct($db) {
        $this->db = $db;
        $this->artiste_model = new Artiste($db, isset($_SESSION['user']) ? $_SESSION['user']['iduser'] : null);
    }

    public function uploadSong() {
        error_log('Méthode uploadSong appelée');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') { 
            error_log('Requête POST reçue');
            $titre = $_POST['songTitle'];
            $categorie = $_POST['songCategory'];
            $artisteId = $_POST['artisteId'];
            $fichier = $_FILES['songFile'];
            $type = $_POST['type'];
            $image = $_FILES['songImage'];

            // Validation et upload des fichiers
            $targetDirSongs = "public/songAudio/";
            $targetDirImages = "public/songImage/";
            $fileNameSong = basename($fichier["name"]);
            $fileNameImage = basename($image["name"]);
            $targetFilePathSong = $targetDirSongs . $fileNameSong;
            $targetFilePathImage = $targetDirImages . $fileNameImage;
            $fileTypeSong = pathinfo($targetFilePathSong, PATHINFO_EXTENSION);
            $fileTypeImage = pathinfo($targetFilePathImage, PATHINFO_EXTENSION);

            // Vérifiez le type de fichier
            $allowTypesSong = array('mp3','wav');
            $allowTypesImage = array('jpg','jpeg','png');
            if(in_array($fileTypeSong, $allowTypesSong) && in_array($fileTypeImage, $allowTypesImage)){
                error_log('Types de fichiers valides');
                // Upload des fichiers vers le serveur
                if(move_uploaded_file($fichier["tmp_name"], $targetFilePathSong) && move_uploaded_file($image["tmp_name"], $targetFilePathImage)){
                    error_log('Fichiers déplacés avec succès');
                    $chansonData = [
                        'titre' => $titre,
                        'image' => $targetFilePathImage,
                        'artisteId' => $artisteId,
                        'categorieId' => $categorie,
                        'type' => $type,
                        'songFile' => $targetFilePathSong // Ajoute le chemin du fichier de la chanson
                    ];
                    if($this->artiste_model->televerserChanson($chansonData)){
                        $message = "La chanson a été uploadée avec succès.";
                        error_log($message);
                    } else {
                        $message = "Erreur lors de l'upload de la chanson.";
                        error_log($message);
                    }
                } else {
                    $message = "Erreur lors de l'upload des fichiers.";
                    error_log($message);
                }
            } else {
                $message = "Seuls les fichiers MP3 et WAV sont autorisés pour les chansons, et JPG, JPEG, PNG pour les images.";
                error_log($message);
            }

            header('Location: home');
        } else {
            $categories = $this->artiste_model->getCategories();
            require __DIR__ .'/../views/uploadSong.php';
        }
    }

    public function uploadAlbum() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                // Vérifier si l'utilisateur est connecté
                if (!isset($_SESSION['user']) || !isset($_SESSION['user']['iduser'])) {
                    throw new Exception("Vous devez être connecté pour uploader un album");
                }

                // Vérifier si la connexion à la base de données est active
                if (!$this->db || !($this->db instanceof PDO)) {
                    throw new Exception("Erreur de connexion à la base de données");
                }

                $this->db->beginTransaction();

                // Debug: Afficher les données reçues
                error_log("Données POST reçues : " . print_r($_POST, true));
                error_log("Session user : " . print_r($_SESSION['user'], true));

                // Validation des données reçues
                if (empty($_POST['albumTitle']) || empty($_POST['songTitles']) || !is_array($_POST['songTitles'])) {
                    throw new Exception("Données d'album incomplètes");
                }

                // Créer d'abord l'entrée dans la table PlayListe
                $query = "INSERT INTO PlayListe (titre, type, userId, anneeSortie, visibilite) 
                         VALUES (:titre, 'album', :userId, EXTRACT(YEAR FROM NOW()), 'visible') 
                         RETURNING idPlayListe";
                
                error_log("Query PlayListe: " . $query);
                
                $stmt = $this->db->prepare($query);
                $stmt->bindValue(':titre', $_POST['albumTitle']);
                $stmt->bindValue(':userId', $_SESSION['user']['iduser']);
                
                error_log("Paramètres de la requête : titre=" . $_POST['albumTitle'] . ", userId=" . $_SESSION['user']['iduser']);
                
                if (!$stmt->execute()) {
                    $error = $stmt->errorInfo();
                    error_log("Erreur SQL lors de l'insertion dans PlayListe: " . print_r($error, true));
                    throw new Exception("Erreur lors de la création de la playlist: " . implode(", ", $error));
                }
                
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                error_log("Résultat de l'insertion : " . print_r($result, true));
                
                if (!$result) {
                    throw new Exception("Erreur lors de la récupération de l'ID de la playlist");
                }
                $playListeId = $result['idplayliste'];
                error_log("ID de la playlist créée : " . $playListeId);

                // Préparer les données de l'album
                $albumData = [
                    'nom' => $_POST['albumTitle'],
                    'chansons' => []
                ];

                // Traiter chaque chanson
                foreach ($_POST['songTitles'] as $index => $titre) {
                    error_log("Traitement de la chanson " . ($index + 1) . " : " . $titre);
                    
                    // Vérifier si tous les fichiers nécessaires sont présents
                    if (empty($_FILES['songFiles']['name'][$index]) || empty($_POST['songCategories'][$index])) {
                        throw new Exception("Données manquantes pour la chanson " . ($index + 1));
                    }

                    // Gérer l'upload du fichier audio
                    $songFile = $_FILES['songFiles']['name'][$index];
                    $songTmpName = $_FILES['songFiles']['tmp_name'][$index];
                    $targetDirSongs = "public/songAudio/";
                    if (!file_exists($targetDirSongs)) {
                        mkdir($targetDirSongs, 0777, true);
                    }
                    
                    $fileNameSong = uniqid() . '_' . basename($songFile);
                    $targetFilePathSong = $targetDirSongs . $fileNameSong;

                    // Vérifier le type de fichier audio
                    $fileTypeSong = strtolower(pathinfo($songFile, PATHINFO_EXTENSION));
                    if (!in_array($fileTypeSong, ['mp3', 'wav'])) {
                        throw new Exception("Le fichier audio " . ($index + 1) . " doit être au format MP3 ou WAV");
                    }

                    // Gérer l'upload de l'image
                    $imageFile = $_FILES['songImages']['name'][$index];
                    $imageTmpName = $_FILES['songImages']['tmp_name'][$index];
                    $targetDirImages = "public/songImage/";
                    if (!file_exists($targetDirImages)) {
                        mkdir($targetDirImages, 0777, true);
                    }
                    
                    $fileNameImage = uniqid() . '_' . basename($imageFile);
                    $targetFilePathImage = $targetDirImages . $fileNameImage;

                    // Vérifier le type de fichier image
                    $fileTypeImage = strtolower(pathinfo($imageFile, PATHINFO_EXTENSION));
                    if (!in_array($fileTypeImage, ['jpg', 'jpeg', 'png'])) {
                        throw new Exception("L'image " . ($index + 1) . " doit être au format JPG, JPEG ou PNG");
                    }

                    // Upload des fichiers
                    if (!move_uploaded_file($songTmpName, $targetFilePathSong)) {
                        throw new Exception("Erreur lors de l'upload du fichier audio " . ($index + 1));
                    }
                    if (!move_uploaded_file($imageTmpName, $targetFilePathImage)) {
                        throw new Exception("Erreur lors de l'upload de l'image " . ($index + 1));
                    }

                    // Ajouter les données de la chanson
                    $albumData['chansons'][] = [
                        'titre' => $titre,
                        'categorieId' => $_POST['songCategories'][$index],
                        'songFile' => $targetFilePathSong,
                        'image' => $targetFilePathImage
                    ];
                }

                // Créer l'album avec les chansons
                error_log("Appel de gererAlbums avec les données : " . print_r($albumData, true));
                $result = $this->artiste_model->gererAlbums($albumData);
                error_log("Résultat de gererAlbums : " . print_r($result, true));

                if (!$result['success']) {
                    throw new Exception($result['message']);
                }

                // Lier les chansons à la playlist
                foreach ($result['chansons'] as $chansonId) {
                    $query = "INSERT INTO PlayListeChanson (playListeId, chansonId) VALUES (:playListeId, :chansonId)";
                    error_log("Query PlayListeChanson: " . $query . " avec playListeId=" . $playListeId . " et chansonId=" . $chansonId);
                    
                    $stmt = $this->db->prepare($query);
                    $stmt->bindValue(':playListeId', $playListeId);
                    $stmt->bindValue(':chansonId', $chansonId);
                    
                    if (!$stmt->execute()) {
                        $error = $stmt->errorInfo();
                        error_log("Erreur SQL lors de l'insertion dans PlayListeChanson: " . print_r($error, true));
                        throw new Exception("Erreur lors de la liaison chanson-playlist: " . implode(", ", $error));
                    }
                }

                $this->db->commit();
                error_log("Transaction commit avec succès");
                
                $this->db->setAttribute(PDO::ATTR_AUTOCOMMIT, true);
                $_SESSION['message'] = "L'album a été uploadé avec succès !";
                header('Location: home');
                exit;

            } catch (Exception $e) {
                error_log("Exception attrapée : " . $e->getMessage());
                try {
                    if ($this->db && $this->db->inTransaction()) {
                        $this->db->rollBack();
                        error_log("Transaction rollback effectué");
                    }
                } catch (Exception $rollbackError) {
                    error_log("Rollback error: " . $rollbackError->getMessage());
                }
                
                if ($this->db) {
                    $this->db->setAttribute(PDO::ATTR_AUTOCOMMIT, true);
                }
                $_SESSION['error'] = $e->getMessage();
                $categories = $this->artiste_model->getCategories();
                require __DIR__ . '/../views/uploadAlbum.php';
            }
        } else {
            $categories = $this->artiste_model->getCategories();
            require __DIR__ . '/../views/uploadAlbum.php';
        }
    }
}
