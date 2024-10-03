<?php

namespace App\Repositories;

use App\Core\Database;
use App\Services\OrderService;
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

    public function getAllOrdersWithItems()
    {
        $stmt = $this->db->query("
        SELECT 
            o.id AS order_id, 
            o.total_amount, 
            o.currency_id, 
            o.status, 
            o.created_at, 
            o.updated_at,
            oi.product_id, 
            oi.quantity, 
            oi.price 
        FROM orders o
        LEFT JOIN order_items oi ON o.id = oi.order_id
        ORDER BY o.created_at DESC
    ");
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return OrderService::groupOrderItems($orders);
    }

    public function getOrderDetailsById(int $orderId)
    {
        $stmt = $this->db->prepare("
        SELECT 
            o.id AS order_id, 
            o.total_amount, 
            o.currency_id, 
            o.status, 
            o.created_at, 
            o.updated_at,
            oi.product_id, 
            oi.quantity, 
            oi.price,
            p.name AS product_name,
            c.label AS currency_label, 
            c.symbol AS currency_symbol
        FROM orders o
        LEFT JOIN order_items oi ON o.id = oi.order_id
        LEFT JOIN products p ON oi.product_id = p.id
        LEFT JOIN currencies c ON o.currency_id = c.id
        WHERE o.id = :order_id
    ");
        $stmt->bindParam(':order_id', $orderId);
        $stmt->execute();

        $orderDetails = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($orderDetails);
        return OrderService::groupOrderItems($orderDetails);
    }
}
