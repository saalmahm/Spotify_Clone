<?php

class Admin extends User {

    public function gererUtilisateurs($userId, $action) {
        // Manage users (e.g., ban/unban)
        if ($action == 'ban') {
            $query = "UPDATE Users SET status = 'banned' WHERE idUser = ?";
        } else if ($action == 'unban') {
            $query = "UPDATE Users SET status = 'active' WHERE idUser = ?";
        }
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$userId]);
    }

    public function superviserChansons($chansonId, $action) {
        // Supervise songs (approve/reject)
        if ($action == 'approve') {
            $query = "UPDATE Chanson SET status = 'approved' WHERE idChanson = ?";
        } else if ($action == 'reject') {
            $query = "UPDATE Chanson SET status = 'rejected' WHERE idChanson = ?";
        }
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$chansonId]);
    }

    public function superviserAlbums($albumId, $action) {
        // Supervise albums (approve/reject)
        if ($action == 'approve') {
            $query = "UPDATE Album SET status = 'approved' WHERE idAlbum = ?";
        } else if ($action == 'reject') {
            $query = "UPDATE Album SET status = 'rejected' WHERE idAlbum = ?";
        }
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$albumId]);
    }
}
?>
