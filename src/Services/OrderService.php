<?php

namespace App\Services;


class OrderService
{
    // Can be converted to a static method in future?

    static function groupOrderItems(array $orders): array
    {
        $groupedOrders = [];

        foreach ($orders as $order) {
            $orderId = $order['orderId'];

            // If the order is not in the grouped array, add it.
            if (!isset($groupedOrders[$orderId])) {
                $groupedOrders[$orderId] = [
                    'orderId' => $orderId,
                    'totalAmount' => $order['totalAmount'],
                    'currencyId' => $order['currencyId'],
                    'status' => $order['status'],
                    'createdAt' => $order['createdAt'],
                    'updatedAt' => $order['updatedAt'],
                    'items' => [] // Initialize items as an empty array.
                ];
            }

            // Add order item details to the 'items' array.
            $groupedOrders[$orderId]['items'][] = [
                'productId' => $order['productId'],
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

        // echo json_encode($orderDetails, JSON_PRETTY_PRINT);

        foreach ($orderDetails as $row) {
            $orderId = $row['order_id'];

            if (!isset($orders[$orderId])) {
                $orders[$orderId] = [
                    'orderId' => $row['order_id'],
                    'totalAmount' => $row['total_amount'],
                    'currencySymbol' => $row['currency_symbol'],
                    'status' => $row['status'],
                    'createdAt' => $row['created_at'],
                    'updatedAt' => $row['updated_at'],
                    'items' => []
                ];
            }

            $productId = $row['product_id'];
            if (!isset($orders[$orderId]['items'][$productId])) {
                $orders[$orderId]['items'][$productId] = [
                    'productId' => $row['product_id'],
                    'quantity' => $row['quantity'],
                    'price' => $row['price'],
                    'productName' => $row['product_name'],
                    'attributes' => []
                ];
            }

            // Add the attribute if it exists in the row.
            if (!empty($row['attribute_name'])) {
                $orders[$orderId]['items'][$productId]['attributes'][] = [
                    'attributeName' => $row['attribute_name'],
                    'attributeValue' => $row['attribute_value'],
                ];
            }
        }

        return array_values($orders);
    }
}
