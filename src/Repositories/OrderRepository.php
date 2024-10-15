<?php

namespace App\Repositories;

use App\Models\Order;
use App\Services\OrderService;

class OrderRepository extends BaseRepository
{
    public function createOrder(float $totalAmount, string $currencyId, string $status = 'pending'): int
    {
        $sql = "INSERT INTO orders (total_amount, currency_id, status) 
                VALUES (:total_amount, :currency_id, :status)";


        $params = [
            ':total_amount' => $totalAmount,
            ':currency_id' => $currencyId,
            ':status' => $status,
        ];

        return $this->insert($sql, $params);
    }

    public function addOrderItems(int $orderId, array $orderedProducts)
    {
        $sql = "INSERT INTO order_items (order_id, product_id, quantity, price) 
                 VALUES (:order_id, :product_id, :quantity, :price)";

        $stmt = $this->db->prepare($sql);
        foreach ($orderedProducts as $product) {
            $stmt->bindValue(':order_id', $orderId);
            $stmt->bindValue(':product_id', $product['productId']);
            $stmt->bindValue(':quantity', $product['quantity']);
            $stmt->bindValue(':price', $product['price']);
            $stmt->execute();

            $orderItemId = $this->db->lastInsertId();

            // Add order item attributes if present
            if (!empty($product['selectedAttributes'])) {
                $this->addOrderItemAttributes($orderItemId, $product['selectedAttributes']);
            }
        }
    }

    private function addOrderItemAttributes(int $orderItemId, array $selectedAttributes)
    {
        $sql = "INSERT INTO order_item_attributes (order_item_id, attribute_id, attribute_item_id, attribute_name, attribute_value) 
                 VALUES (:order_item_id, :attribute_id, :attribute_item_id, :attribute_name, :attribute_value)";

        $stmt = $this->db->prepare($sql);
        foreach ($selectedAttributes as $attribute) {
            $stmt->bindParam(':order_item_id', $orderItemId);
            $stmt->bindParam(':attribute_id', $attribute['attributeId']);
            $stmt->bindParam(':attribute_item_id', $attribute['attributeItemId']);
            $stmt->bindParam(':attribute_name', $attribute['attributeName']);
            $stmt->bindParam(':attribute_value', $attribute['attributeValue']);
            $stmt->execute();
        }
    }

    public function updateOrderStatus(int $orderId, string $status): bool
    {
        $sql = "UPDATE orders SET status = :status WHERE id = :order_id";
        $params = [
            ':status' => $status,
            ':order_id' => $orderId,
        ];

        return $this->update($sql, $params);
    }

    public function fetchAllOrders(): array
    {
        $sql = "SELECT 
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
                    c.symbol AS currency_symbol,
                    c.id AS currency_id
                FROM orders o
                LEFT JOIN order_items oi ON o.id = oi.order_id
                LEFT JOIN products p ON oi.product_id = p.id
                LEFT JOIN currencies c ON o.currency_id = c.id";

        // Fetch all orders from the database
        $orders = $this->fetchAll($sql);

        // Group and sort the fetched data using OrderService
        return OrderService::groupOrders($orders);
    }

    public function fetchOrderById(int $orderId): ?Order
    {
        $sql = "SELECT 
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
                    c.symbol AS currency_symbol,
                    oia.attribute_id,
                    oia.attribute_name,
                    oia.attribute_value
                FROM orders o
                LEFT JOIN order_items oi ON o.id = oi.order_id
                LEFT JOIN products p ON oi.product_id = p.id
                LEFT JOIN currencies c ON o.currency_id = c.id
                LEFT JOIN order_item_attributes oia ON oi.id = oia.order_item_id
                WHERE o.id = :id";

        $params = [':id' => $orderId];

        // Fetch order by ID from the database
        $orderDetails = $this->fetchAll($sql, $params);

        if ($orderDetails) {
            // Group the fetched order details including the attributes using OrderService
            return OrderService::groupOrderItemsWithAttributes($orderDetails);
        }

        return null;
    }
}
