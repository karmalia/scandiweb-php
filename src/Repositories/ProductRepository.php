<?php

namespace App\Repositories;

use App\Core\Database;

class ProductRepository {
    private $conn;

    public function __construct() {
        $this->conn = new Database();
    }

    // Fetch a product with its prices, attributes, and images
    public function getProductById($id) {
        // Query the product details
        $productQuery = "SELECT * FROM products WHERE id = ?";
        $productStmt = $this->conn->prepare($productQuery);
        $productStmt->execute([$id]);
        $product = $productStmt->fetch();

        if (!$product) {
            return null; // No product found
        }

        // Fetch related prices
        $priceQuery = "SELECT * FROM prices WHERE product_id = ?";
        $priceStmt = $this->conn->prepare($priceQuery);
        $priceStmt->execute([$id]);
        $product['prices'] = $priceStmt->fetchAll();

        // Fetch related attributes
        $attributesQuery = "SELECT * FROM product_attributes 
                            JOIN attributes ON product_attributes.attribute_id = attributes.id 
                            WHERE product_attributes.product_id = ?";
        $attrStmt = $this->conn->prepare($attributesQuery);
        $attrStmt->execute([$id]);
        $product['attributes'] = $attrStmt->fetchAll();

        // Fetch related images
        $imagesQuery = "SELECT * FROM product_images WHERE product_id = ?";
        $imgStmt = $this->conn->prepare($imagesQuery);
        $imgStmt->execute([$id]);
        $product['images'] = $imgStmt->fetchAll();

        return $product;
    }

    // Other repository methods (e.g., createProduct, updateProduct)...
}
