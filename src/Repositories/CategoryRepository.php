<?php

namespace App\Repositories;

use App\Models\Category;

class CategoryRepository extends BaseRepository
{
    // Fetch all categories using the BaseRepository fetchAll method
    public function getAllCategories(): array
    {
        $sql = "SELECT * FROM categories";

        // Use the fetchAll method from BaseRepository to get all records
        $categories = $this->fetchAll($sql);

        $categoryList = [];
        foreach ($categories as $category) {
            $categoryList[] = new Category($category['id'], $category['name']);
        }

        return $categoryList;
    }

    // Fetch a category by ID using the BaseRepository fetchOne method
    public function getCategoryById(string $id): ?Category
    {
        $sql = "SELECT * FROM categories WHERE id = :id";

        // Use the fetchOne method from BaseRepository to get a single record
        $category = $this->fetchOne($sql, [':id' => $id]);

        if ($category) {
            return new Category($category['id'], $category['name']);
        }

        return null;
    }
}
