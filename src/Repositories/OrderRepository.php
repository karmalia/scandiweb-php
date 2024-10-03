<?php

namespace App\Repositories;

use App\Core\Database;
use PDO;

class OrderRepository
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->connect();
    }

    public function createOrder(float $totalAmount, string $currencyId, string $status = 'pending'): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO orders (total_amount, currency_id, status) 
            VALUES (:total_amount, :currency_id, :status)
        ");

        $stmt->bindParam(':total_amount', $totalAmount);
        $stmt->bindParam(':currency_id', $currencyId);
        $stmt->bindParam(':status', $status);

        $stmt->execute();
        return $this->db->lastInsertId(); // Returns the ID of the newly created order.
    }

    public function addOrderItems(int $orderId, array $products)
    {
        $stmt = $this->db->prepare("
            INSERT INTO order_items (order_id, product_id, quantity, price) 
            VALUES (:order_id, :product_id, :quantity, :price)
        ");
        foreach ($products as $product) {
            $stmt->bindParam(':order_id', $orderId);
            $stmt->bindParam(':product_id', $product['productId']);
            $stmt->bindParam(':quantity', $product['quantity']);
            $stmt->bindParam(':price', $product['price']);

            $stmt->execute();
        }
    }

    public function getAllOrders()
    {
        $stmt = $this->db->query("
            SELECT * FROM orders
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
