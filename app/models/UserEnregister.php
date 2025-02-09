<?php

class UserEnregister extends User {

    public function obtenirChansonsAimees() {
        // Get all liked songs by the user
        $query = "SELECT Chanson.* FROM Chanson
                  JOIN ChansonAimee ON ChansonAimee.chanson_id = Chanson.idChanson
                  WHERE ChansonAimee.user_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$this->idUser]);
        return $stmt->fetchAll();
    }

    public function aimerChanson($chansonId) {
        // Like a song
        $query = "INSERT INTO ChansonAimee (user_id, chanson_id) VALUES (?, ?)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$this->idUser, $chansonId]);
    }
}
?>
