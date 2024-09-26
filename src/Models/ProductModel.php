<?php

namespace Models;

class ProductModel extends BaseModel {
    
    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function findAll() {
        $stmt = $this->db->query("SELECT * FROM products");
        return $stmt->fetchAll();
    }

    public function insertProduct($productData, $prices) {
        // Insert product into the database
        $productId = $this->insert($productData, 'products');

        // Insert associated prices
        $priceModel = new PriceModel();
        $priceModel->insertMultiplePrices($productId, $prices);
        return $productId;
    }
}
