<?php

namespace App\Repositories;

use App\Models\Category;

class CategoryRepository extends BaseRepository
{
    public function getAllCategories(): array
    {
        $sql = "SELECT * FROM categories";

        $categories = $this->fetchAll($sql);

        $categoryList = [];
        foreach ($categories as $category) {
            $categoryList[] = new Category($category['id'], $category['name']);
        }

        return $categoryList;
    }

    public function getCategoryById(string $id): ?Category
    {
        $sql = "SELECT * FROM categories WHERE id = :id";

        $category = $this->fetchOne($sql, [':id' => $id]);

        if ($category) {
            return new Category($category['id'], $category['name']);
        }

        return null;
    }
}
