<?php

namespace Controllers;

use Models\ProductModel;

class ProductController {
    public function createProduct($requestData) {
        $productModel = new ProductModel();
        $productId = $productModel->insertProduct([
            'id' => $requestData['id'],
            'name' => $requestData['name'],
            'description' => $requestData['description'],
            'in_stock' => $requestData['in_stock'],
            'category_id' => $requestData['category_id'],
            'brand' => $requestData['brand']
        ]);

        return $productId; // Return the newly created product ID
    }
}
