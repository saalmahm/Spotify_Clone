<?php
class ArtisteController {
    private $artiste_model;

    public function __construct($db) {
        $this->artiste_model = new Artiste($db, null);
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
            $albumData = [
                'albumTitle' => $_POST['albumTitle'],
                'artisteId' => $_POST['artisteId'],
                'songTitles' => $_POST['songTitles'],
                'songCategories' => $_POST['songCategories'],
                'songFiles' => $_FILES['songFiles']['name'],
                'songImages' => $_FILES['songImages']['name']
            ];
    
            // Valider et uploader les fichiers des chansons
            $targetDirSongs = "public/songAudio/";
            $targetDirImages = "public/songImage/";
            foreach ($_FILES['songFiles']['tmp_name'] as $index => $tmpName) {
                $fileNameSong = basename($_FILES['songFiles']['name'][$index]);
                $fileNameImage = basename($_FILES['songImages']['name'][$index]);
                $targetFilePathSong = $targetDirSongs . $fileNameSong;
                $targetFilePathImage = $targetDirImages . $fileNameImage;
    
                move_uploaded_file($tmpName, $targetFilePathSong);
                move_uploaded_file($_FILES['songImages']['tmp_name'][$index], $targetFilePathImage);
    
                $albumData['songFiles'][$index] = $targetFilePathSong;
                $albumData['songImages'][$index] = $targetFilePathImage;
            }
    
            // Créer l'album en passant l'objet Artiste
            $playlist = new Playlist($this->artiste_model->getDb(), $albumData['albumTitle'], 'album', $albumData['artisteId'], null, 'visible', $this->artiste_model);
            $albumId = $playlist->uploadAlbum($albumData);
    
            if ($albumId) {
                $message = "L'album a été uploadé avec succès.";
            } else {
                $message = "Erreur lors de l'upload de l'album.";
            }
    
            header('Location: home');
        } else {
            $categories = $this->artiste_model->getCategories();
            require __DIR__ .'/../views/uploadAlbum.php';
        }
    }
    
    
}
