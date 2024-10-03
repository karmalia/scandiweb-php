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
}
