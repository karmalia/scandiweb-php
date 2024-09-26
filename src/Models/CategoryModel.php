<?php

namespace Models;

class CategoryModel extends BaseModel {

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function findAll() {
        $stmt = $this->db->query("SELECT * FROM categories");
        return $stmt->fetchAll();
    }

    public function insertCategory($categoryData) {
        return $this->insert($categoryData, 'categories');
    }
}
