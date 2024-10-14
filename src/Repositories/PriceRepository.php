<?php

namespace App\Repositories;

use App\Models\Price;
use App\Models\Currency;

class PriceRepository extends BaseRepository
{
    // Fetch prices for a product using the BaseRepository fetchAll method
    public function getPricesByProductId(string $productId): array
    {
        $sql = "
            SELECT pr.amount, c.id as currency_id, c.label as currency_label, c.symbol as currency_symbol
            FROM prices pr
            LEFT JOIN currencies c ON pr.currency_id = c.id
            WHERE pr.product_id = :product_id
        ";

        // Use the fetchAll method from BaseRepository to get the prices
        $prices = $this->fetchAll($sql, [':product_id' => $productId]);

        // Convert the result into a list of Price objects
        $priceList = [];
        foreach ($prices as $price) {
            $currency = new Currency($price['currency_id'], $price['currency_label'], $price['currency_symbol']);
            $priceList[] = new Price($price['amount'], $currency);
        }

        return $priceList;
    }
}
