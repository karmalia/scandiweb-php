<?php

namespace App\Repositories;

use App\Core\Database;
use App\Models\Order;

class OrderRepository
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->connect();
    }

    public function getAllOrders(): array
    {
        $stmt = $this->db->query("SELECT * FROM orders");
        $orders = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return array_map(function ($orderData) {
            return new Order(
                $orderData['id'],
                $orderData['total_amount'],
                $orderData['currency_id'],
                $orderData['status'],
                $orderData['created_at'],
                $orderData['updated_at']
            );
        }, $orders);
    }

    public function createOrder(float $totalAmount, string $currencyId, string $status): ?Order
    {
        $stmt = $this->db->prepare("
            INSERT INTO orders (total_amount, currency_id, status)
            VALUES (:total_amount, :currency_id, :status)
        ");
        $stmt->bindParam(':total_amount', $totalAmount);
        $stmt->bindParam(':currency_id', $currencyId);
        $stmt->bindParam(':status', $status);

        if ($stmt->execute()) {
            $orderId = $this->db->lastInsertId();
            return new Order($orderId, $totalAmount, $currencyId, $status, date('Y-m-d H:i:s'), date('Y-m-d H:i:s'));
        }

        return null;
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
}
