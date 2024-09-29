<?php

namespace App\Repositories;

use App\Models\Category;
use App\Core\Database;

class CategoryRepository
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->connect();
    }

    public function getAllCategories(): array
    {
        $stmt = $this->db->prepare("SELECT * FROM categories");
        $stmt->execute();
        $categories = $stmt->fetchAll();

        $categoryList = [];
        foreach ($categories as $category) {
            $categoryList[] = new Category($category['id'], $category['name']);
        }
        
        return $categoryList;
    }

    public function getCategoryById(string $id): ?Category
    {
        $stmt = $this->db->prepare("SELECT * FROM categories WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $category = $stmt->fetch();

        if ($category) {
            return new Category($category['id'], $category['name']);
        }

        return null;
    }
}
