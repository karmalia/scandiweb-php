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
        return $this->db->lastInsertId();
    }

    // Add products to the order and return the order items' details
    public function addOrderItems(int $orderId, array $orderedProducts)
    {
        $stmt = $this->db->prepare("
             INSERT INTO order_items (order_id, product_id, quantity, price) 
             VALUES (:order_id, :product_id, :quantity, :price)
         ");
        foreach ($orderedProducts as $product) {
            $stmt->bindParam(':order_id', $orderId);
            $stmt->bindParam(':product_id', $product['productId']);
            $stmt->bindParam(':quantity', $product['quantity']);
            $stmt->bindParam(':price', $product['price']);

            $stmt->execute();
            $orderItemId = $this->db->lastInsertId();

            // If the product has attributes, insert them
            if (!empty($product['selectedAttributes'])) {
                $this->addOrderItemAttributes($orderItemId, $product['selectedAttributes']);
            }
        }
    }

    // Insert attributes into `order_item_attributes`
    private function addOrderItemAttributes(int $orderItemId, array $selectedAttributes)
    {
        $stmt = $this->db->prepare("
            INSERT INTO order_item_attributes (order_item_id, attribute_id, attribute_item_id, attribute_name, attribute_value) 
            VALUES (:order_item_id, :attribute_id, :attribute_item_id, :attribute_name, :attribute_value)
        ");
        foreach ($selectedAttributes as $attribute) {
            $stmt->bindParam(':order_item_id', $orderItemId);
            $stmt->bindParam(':attribute_id', $attribute['attributeId']);
            $stmt->bindParam(':attribute_item_id', $attribute['attributeItemId']);
            $stmt->bindParam(':attribute_name', $attribute['attributeName']); // Store the name for reference
            $stmt->bindParam(':attribute_value', $attribute['attributeValue']); // Store the value for the selected item

            $stmt->execute();
        }
    }


    // Retrieve all orders with their items and attributes
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
             oi.id AS order_item_id,
             oi.product_id, 
             oi.quantity, 
             oi.price,
             -- Join for product details
             p.name AS product_name,
             -- Join for currency details
             c.label AS currency_label, 
             c.symbol AS currency_symbol,
             -- Join for attributes
             oia.attribute_id,
             oia.attribute_name,
             oia.attribute_value
         FROM orders o
         LEFT JOIN order_items oi ON o.id = oi.order_id
         LEFT JOIN products p ON oi.product_id = p.id
         LEFT JOIN currencies c ON o.currency_id = c.id
         LEFT JOIN order_item_attributes oia ON oi.id = oia.order_item_id
         ORDER BY o.created_at DESC
     ");
        $orders = $stmt->fetchAll();
        return OrderService::groupOrderItemsWithAttributes($orders);
    }

    // Retrieve specific order details including selected attributes
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
        oi.id AS order_item_id,
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
        WHERE o.id = :order_id
    ");
        $stmt->bindParam(':order_id', $orderId);
        $stmt->execute();

        $orderDetails = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Group and structure the response
        return $this->formatOrderDetails($orderDetails);
    }

    private function formatOrderDetails(array $orderDetails): array
    {
        if (empty($orderDetails)) {
            return [];
        }

        // Extract the main order details (using the first row as the base)
        $order = [
            'orderId' => $orderDetails[0]['order_id'],
            'totalAmount' => $orderDetails[0]['total_amount'],
            'currencyId' => $orderDetails[0]['currency_id'],
            'currencyLabel' => $orderDetails[0]['currency_label'],
            'currencySymbol' => $orderDetails[0]['currency_symbol'],
            'status' => $orderDetails[0]['status'],
            'createdAt' => $orderDetails[0]['created_at'],
            'updatedAt' => $orderDetails[0]['updated_at'],
            'products' => []
        ];

        // Group products and their attributes
        $products = [];
        foreach ($orderDetails as $detail) {
            $orderItemId = $detail['order_item_id'];

            // If the product is not in the array, add it
            if (!isset($products[$orderItemId])) {
                $products[$orderItemId] = [
                    'order_item_id' => $orderItemId,
                    'productId' => $detail['product_id'],
                    'productName' => $detail['product_name'],
                    'quantity' => $detail['quantity'],
                    'price' => $detail['price'],
                    'attributes' => [] // Placeholder for attributes
                ];
            }

            // Add the attribute if it exists
            if ($detail['attribute_id']) {
                $products[$orderItemId]['attributes'][] = [
                    'attributeId' => $detail['attribute_id'],
                    'attributeName' => $detail['attribute_name'],
                    'attributeValue' => $detail['attribute_value']
                ];
            }
        }

        // Add the products array to the order
        $order['products'] = array_values($products);

        // For debugging purposes, print the final structured order details (optional).
        // echo json_encode($order);

        return $order;
    }

    public function updateOrderStatus(int $orderId, string $status): bool
    {
        $stmt = $this->db->prepare("
            UPDATE orders 
            SET status = :status 
            WHERE id = :order_id
        ");
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':order_id', $orderId);

        return $stmt->execute();
    }
}
