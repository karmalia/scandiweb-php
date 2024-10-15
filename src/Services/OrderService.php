<?php

namespace App\Services;

use App\Models\Currency;
use App\Models\Order;
use App\Models\OrderItem;

class OrderService
{
    public static function groupOrders(array $orders): array
    {
        $groupedOrders = [];

        foreach ($orders as $order) {
            $orderId = $order['order_id'];

            if (!isset($groupedOrders[$orderId])) {
                $currency = new Currency($order['currency_id'], $order['currency_label'], $order['currency_symbol']);
                $groupedOrders[$orderId] = new Order(
                    $order['order_id'],
                    $order['total_amount'],
                    $currency,
                    $order['status'],
                    $order['created_at'],
                    $order['updated_at']
                );
            }

            $orderItem = new OrderItem(
                $order['product_id'],
                $order['quantity'],
                $order['price'],
                $order['product_name']
            );
            $groupedOrders[$orderId]->addItem($orderItem);
        }

        return array_values($groupedOrders);
    }

    public static function groupOrderItemsWithAttributes(array $orderDetails): Order
    {
        $groupedOrders = [];

        foreach ($orderDetails as $orderDetail) {
            $orderId = $orderDetail['order_id'];

            $currency = new Currency($orderDetail['currency_id'], $orderDetail['currency_label'], $orderDetail['currency_symbol']);

            if (!isset($groupedOrders[$orderId])) {
                $groupedOrders[$orderId] = new Order(
                    $orderDetail['order_id'],
                    $orderDetail['total_amount'],
                    $currency,
                    $orderDetail['status'],
                    $orderDetail['created_at'],
                    $orderDetail['updated_at']
                );
            }

            $productId = $orderDetail['product_id'];

            $orderItem = $groupedOrders[$orderId]->findOrCreateItem(
                $productId,
                $orderDetail['quantity'],
                $orderDetail['price'],
                $orderDetail['product_name']
            );

            if (!empty($orderDetail['attribute_id'])) {
                $orderItem->addAttribute([
                    'attributeId' => $orderDetail['attribute_id'],
                    'attributeName' => $orderDetail['attribute_name'],
                    'attributeValue' => $orderDetail['attribute_value'],
                ]);
            }

            $groupedOrders[$orderId]->addItem($orderItem);
        }

        return array_values($groupedOrders)[0];
    }
}
