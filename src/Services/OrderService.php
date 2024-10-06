<?php

namespace App\Services;


class OrderService
{
    // Can be converted to a static method in future?

    static function groupOrderItems(array $orders): array
    {
        $groupedOrders = [];

        foreach ($orders as $order) {
            $orderId = $order['order_id'];

            // If the order is not in the grouped array, add it.
            if (!isset($groupedOrders[$orderId])) {
                $groupedOrders[$orderId] = [
                    'order_id' => $orderId,
                    'total_amount' => $order['total_amount'],
                    'currency_id' => $order['currency_id'],
                    'status' => $order['status'],
                    'created_at' => $order['created_at'],
                    'updated_at' => $order['updated_at'],
                    'items' => [] // Initialize items as an empty array.
                ];
            }

            // Add order item details to the 'items' array.
            $groupedOrders[$orderId]['items'][] = [
                'product_id' => $order['product_id'],
                'quantity' => $order['quantity'],
                'price' => $order['price']
            ];
        }

        // Return as a flat array with grouped orders.
        return array_values($groupedOrders);
    }

    public static function groupOrderItemsWithAttributes(array $orderDetails)
    {
        $orders = [];

        foreach ($orderDetails as $row) {
            $orderId = $row['order_id'];

            if (!isset($orders[$orderId])) {
                $orders[$orderId] = [
                    'order_id' => $row['order_id'],
                    'total_amount' => $row['total_amount'],
                    'currency_symbol' => $row['currency_symbol'],
                    'status' => $row['status'],
                    'created_at' => $row['created_at'],
                    'updated_at' => $row['updated_at'],
                    'items' => []
                ];
            }

            $productId = $row['product_id'];
            if (!isset($orders[$orderId]['items'][$productId])) {
                $orders[$orderId]['items'][$productId] = [
                    'product_id' => $row['product_id'],
                    'quantity' => $row['quantity'],
                    'price' => $row['price'],
                    'product_name' => $row['product_name'],
                    'attributes' => []
                ];
            }

            // Add the attribute if it exists in the row.
            if (!empty($row['attribute_name'])) {
                $orders[$orderId]['items'][$productId]['attributes'][] = [
                    'attribute_name' => $row['attribute_name'],
                    'attribute_value' => $row['attribute_value'],
                    'selected' => (bool)$row['selected']
                ];
            }
        }

        return array_values($orders);
    }
}
