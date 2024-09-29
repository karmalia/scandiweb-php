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
                pr.amount, c.id as currency_id, c.label as currency_label, c.symbol as currency_symbol, 
                GROUP_CONCAT(pi.image_url) as image_urls, 
                cat.name as category_name
            FROM products p 
            LEFT JOIN prices pr ON p.id = pr.product_id
            LEFT JOIN categories cat ON cat.id = p.category_id
            LEFT JOIN currencies c ON pr.currency_id = c.id
            LEFT JOIN product_images pi ON p.id = pi.product_id
            GROUP BY p.id, pr.amount, c.id, c.label, c.symbol");
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
            $gallery = $product['image_urls'] ? explode('https', $product['image_urls']) : [];

            //Remove seperate(map) image by https and remove(filter) empty strings and get the values(values)
            $mappedGallery = array_values(
                array_filter(
                    array_map(function ($image) {
                        return strlen(trim($image)) > 0 ? 'https' . $image : null;
                    }, $gallery),
                    fn($image) => $image !== null
                )
            );

            $groupedProducts[$productId] = new Product(
                $product['id'],
                $product['name'],
                $product['description'],
                (bool) $product['in_stock'],
                $product['brand'],
                $product['category_name'],
                
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

        if($product['image_urls']){
            $groupedProducts[$productId]->setGallery($mappedGallery);
        }





    }
    return array_values($groupedProducts);
}

}
