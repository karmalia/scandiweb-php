<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\Price;
use App\Models\Currency;
use App\Core\Database;
use App\Services\ProductService;

class ProductRepository
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->connect();
    }

    public function getAllProducts(string $category): ?array
    {
        
        $sql = "
        SELECT p.id, p.name, p.description, p.in_stock, p.brand, 
            pr.amount, c.id as currency_id, c.label as currency_label, c.symbol as currency_symbol, 
            GROUP_CONCAT(pi.image_url) as image_urls, 
            cat.name as category_name
        FROM products p
        LEFT JOIN prices pr ON p.id = pr.product_id
        LEFT JOIN categories cat ON cat.id = p.category_id
        LEFT JOIN currencies c ON pr.currency_id = c.id
        LEFT JOIN product_images pi ON p.id = pi.product_id
        ";

        if ($category !== 'all') {
            $sql .= " WHERE cat.name = :category ";
        }

        $sql .= " GROUP BY p.id, pr.amount, c.id, c.label, c.symbol, cat.name ";

        $stmt = $this->db->prepare($sql);

        if ($category !== 'all') {
            $stmt->bindParam(':category', $category);
        }

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
       
        $productService = new ProductService();
       
            if ($productData) {
                
                $product = $productService->mapRowToProduct($productData);
                
                return $product;
            }
        
        

        return null;
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
          
            $currency = new Currency(
                $product['currency_id'],
                $product['currency_label'],
                $product['currency_symbol']
            );

            $price = new Price($product['amount'], $currency);

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
