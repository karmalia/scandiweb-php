<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\Price;
use App\Models\Currency;
use App\Models\Attribute;
use App\Models\AttributeItem;
use App\Core\Database;

class ProductRepository
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->connect();
    }

    public function getAllProducts(): ?array
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
        
        $data = $this->groupProducts($products);
        
        if($data){
            return $data;
        }

        return null;
    }

    public function getProductById(string $id): ?Product
    {
        $stmt = $this->db->prepare("
        SELECT 
            p.id, p.name, p.description, p.in_stock, p.brand, 
            pr.amount, c.id AS currency_id, c.label AS currency_label, c.symbol AS currency_symbol, 
            GROUP_CONCAT(DISTINCT pi.image_url ORDER BY pi.id SEPARATOR ',') AS image_urls, 
            cat.name AS category_name,
            
            -- For attributes
            a.id AS attribute_id, a.name AS attribute_name, a.type AS attribute_type,

            -- For attribute items
            ai.id AS attribute_item_id,
            ai.value AS attribute_item_value,
            ai.display_value AS attribute_item_display_value

        FROM products p 
        LEFT JOIN prices pr ON p.id = pr.product_id
        LEFT JOIN categories cat ON cat.id = p.category_id
        LEFT JOIN currencies c ON pr.currency_id = c.id
        LEFT JOIN product_images pi ON p.id = pi.product_id
        LEFT JOIN product_attributes pa ON p.id = pa.product_id
        LEFT JOIN attributes a ON pa.attribute_id = a.id
        LEFT JOIN attribute_items ai ON pa.attribute_item_id = ai.id

        WHERE p.id = :id

        GROUP BY 
            p.id, pr.amount, c.id, c.label, c.symbol, cat.name,
            a.id, a.name, a.type, ai.id, ai.value, ai.display_value
    ");

        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $productData = $stmt->fetchAll();

       
            if ($productData) {
                
                $product = $this->mapRowToProduct($productData);
                
                return $product;
            }
        
        

        return null;
    }
    private function mapRowToProduct(array $rows): ?Product
{
    if (empty($rows)) {
        return null;
    }

    
    // Create the base product
    $firstRow = $rows[0];
    $gallery = $firstRow['image_urls'] ? explode('https', $firstRow['image_urls']) : [];
    $mappedGallery = array_values(array_filter(array_map(function ($image) {
        return strlen(trim($image)) > 0 ? 'https' . $image : null;
    }, $gallery), fn($image) => $image !== null));

    
    $product = new Product(
        $firstRow['id'],
        $firstRow['name'],
        $firstRow['description'],
        (bool) $firstRow['in_stock'],
        $firstRow['brand'],
        $firstRow['category_name']
    );
    
    $product->setGallery($mappedGallery);

    // Set unique prices
    $prices = []; // Initialize prices array once outside the loop
    $seenPrices = []; // Keep track of unique currency combinations to avoid duplicates
    foreach ($rows as $row) {
        if ($row['amount'] && $row['currency_id'] && $row['currency_label'] && $row['currency_symbol']) {
            $priceIdentifier = $row['amount'] . '-' . $row['currency_id']; // Create a unique identifier for each price
            if (!in_array($priceIdentifier, $seenPrices)) {
                $currency = new Currency(
                    $row['currency_id'],
                    $row['currency_label'],
                    $row['currency_symbol']
                );
                $price = new Price($row['amount'], $currency);

                $prices[] = $price; // Append new price to the prices array
                $seenPrices[] = $priceIdentifier; // Mark this price as seen
            }
        }
    }
    
    $product->setPrices($prices);

    // Set attributes
    $attributes = $this->groupAttributesByProduct($rows);
    
    $product->setAttributes($attributes);
   

    // Output the product as JSON for debugging
    // echo "PROUCT? ", json_encode($product, JSON_PRETTY_PRINT);
    return $product;
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

private function groupAttributesByProduct(array $productData): array
{
    $attributes = [];

    foreach ($productData as $product) {
        $attributeId = $product['attribute_id'];
        $attributeName = $product['attribute_name'];
        $attributeType = $product['attribute_type'];

        // If no attribute id, skip this product
        if (!$attributeId) {
            continue;
        }

        // Create a new attribute object if it doesn't exist
        if (!isset($attributes[$attributeId])) {
            $attributes[$attributeId] = new Attribute($attributeId, $attributeName, $attributeType, []);
        }

        // Add the attribute item to the attribute
        $attributeItemId = $product['attribute_item_id'];
        if ($attributeItemId) {
            $attributeItem = new AttributeItem(
                $attributeItemId,
                $product['attribute_item_value'],
                $product['attribute_item_display_value']
            );

            // Check if the item is already present in the attribute's items array
            $existingItems = $attributes[$attributeId]->getItems();
            $isItemExists = false;
            foreach ($existingItems as $item) {
                if ($item->getId() === $attributeItemId) {
                    $isItemExists = true;
                    break;
                }
            }

            // Only add the item if it does not already exist
            if (!$isItemExists) {
                $attributes[$attributeId]->addItem($attributeItem);
            }
        }
    }

    return array_values($attributes); // Return the attributes as an array of objects
}



}
