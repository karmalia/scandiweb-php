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
        GROUP_CONCAT(pi.image_url SEPARATOR '||') as image_urls, 
        cat.name as category_name
        FROM products p
        LEFT JOIN prices pr ON p.id = pr.product_id
        LEFT JOIN categories cat ON cat.id = p.category_id
        LEFT JOIN currencies c ON pr.currency_id = c.id
        LEFT JOIN product_images pi ON p.id = pi.product_id";


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
        $productService = new ProductService();
        $data = $productService->groupProducts($products);

        if ($data) {
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
    GROUP_CONCAT(DISTINCT pi.image_url ORDER BY pi.id SEPARATOR '||') AS image_urls, 
    cat.name AS category_name,
    a.id AS attribute_id, a.name AS attribute_name, a.type AS attribute_type,
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
ORDER BY 
    CASE WHEN a.name = 'Capacity' THEN 0 ELSE 1 END,
    CASE WHEN a.name = 'Capacity' THEN ai.sort_order ELSE NULL END,
    a.name,
    ai.value;

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
}
