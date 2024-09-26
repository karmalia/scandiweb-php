<?php

namespace Models;

class PriceModel extends BaseModel  {

    // Fetch all prices for a specific product
    public function findByProductId($productId) {
        $stmt = $this->db->prepare("SELECT * FROM prices WHERE product_id = ?");
        $stmt->execute([$productId]);
        return $stmt->fetchAll(); // Returns all price records for the given product
    }

    // Insert a price record for a product
    public function insertPrice($priceData) {
        return $this->insert($priceData, 'prices');
    }

    // Insert multiple prices for a single product
    public function insertMultiplePrices($productId, $prices) {
        foreach ($prices as $price) {
            $this->insertPrice([
                'product_id' => $productId,
                'amount' => $price['amount'],
                'currency_label' => $price['currency_label'],
                'currency_symbol' => $price['currency_symbol']
            ]);
        }
    }
}
