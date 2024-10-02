<?php

namespace App\Repositories;

use App\Models\Price;
use App\Models\Currency;
use App\Core\Database;

class PriceRepository
{
    // Not used in current implementation
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->connect();
    }

    public function getPricesByProductId(string $productId): array
    {
        $stmt = $this->db->prepare("
            SELECT pr.amount, c.id as currency_id, c.label as currency_label, c.symbol as currency_symbol
            FROM prices pr 
            LEFT JOIN currencies c ON pr.currency_id = c.id
            WHERE pr.product_id = :product_id
        ");
        $stmt->bindParam(':product_id', $productId);
        $stmt->execute();
        $prices = $stmt->fetchAll();

        $priceList = [];
        foreach ($prices as $price) {
            $currency = new Currency($price['currency_id'], $price['currency_label'], $price['currency_symbol']);
            $priceList[] = new Price($price['amount'], $currency);
        }

        return $priceList;
    }
}
