<?php

class Categorie {
    private $idCategory;
    private $name;
    private $associatedMusic = [];
    private $db;

    public function __construct($db, $name) {
        $this->db = $db;
        $this->name = $name;
    }

    public function getAllCategorys() {
        // Get all categories
        $query = "SELECT * FROM Category";
        $stmt = $this->db->query($query);
        return $stmt->fetchAll();
    }

    public function saveCategory() {
        // Save category to database
        $query = "INSERT INTO Category (name) VALUES (?)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$this->name]);
    }
}
?>
