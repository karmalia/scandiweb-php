<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\Price;
use App\Models\Currency;
use App\Core\Database;

class ProductRepository
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->connect();
    }

    public function getAllProducts(): array
    {
        $stmt = $this->db->prepare("
            SELECT p.id, p.name, p.description, p.in_stock, p.brand, 
                   pr.amount, c.id as currency_id, c.label as currency_label, c.symbol as currency_symbol
            FROM products p 
            LEFT JOIN prices pr ON p.id = pr.product_id
            LEFT JOIN currencies c ON pr.currency_id = c.id
        ");
        $stmt->execute();
        $products = $stmt->fetchAll();
        return $this->groupProducts($products);
    }

    private function groupProducts(array $products): array
{
    $groupedProducts = [];

    foreach ($products as $product) {
        $productId = $product['id'];

        // If the product is not in the grouped array, add it
        if (!isset($groupedProducts[$productId])) {
            $groupedProducts[$productId] = new Product(
                $product['id'],
                $product['name'],
                $product['description'],
                (bool) $product['in_stock'],
                $product['brand']
            );
        }

       

        // Only add the price if all required price fields are present
        if ($product['amount'] && $product['currency_id'] && $product['currency_label'] && $product['currency_symbol']) {
            // Create a Currency object for the current price
            $currency = new Currency(
                $product['currency_id'],
                $product['currency_label'],
                $product['currency_symbol']
            );

            // Create a Price object
            $price = new Price($product['amount'], $currency);

            // Retrieve the current prices array, add the new price, and set it back
            $currentPrices = $groupedProducts[$productId]->getPrices();
            $currentPrices[] = $price;
            $groupedProducts[$productId]->setPrices($currentPrices);
        }
    }

    return array_values($groupedProducts);
}

}
