<?php

class UserEnregister extends User {
    public function obtenirChansonsAimees() {
        $query = "SELECT Chanson.* FROM Chanson
                  JOIN ChansonAimee ON ChansonAimee.chanson_id = Chanson.idChanson
                  WHERE ChansonAimee.user_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$this->idUser]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function aimerChanson($chansonId) {
        $query = "INSERT INTO ChansonAimee (user_id, chanson_id) VALUES (?, ?)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$this->idUser, $chansonId]);
    }
}

?>
