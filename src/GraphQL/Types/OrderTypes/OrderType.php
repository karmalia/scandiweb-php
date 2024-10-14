<?php

namespace App\GraphQL\Types\OrderTypes;

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;
use App\Models\Order;

class OrderType extends ObjectType
{
    public function __construct()
    {
        $config = [
            'name' => 'Order',
            'fields' => [
                'orderId' => [
                    'type' => Type::nonNull(Type::int()),
                    'resolve' => function (Order $order) {
                        return $order->getId();
                    }
                ],
                'totalAmount' => [
                    'type' => Type::nonNull(Type::float()),
                    'resolve' => function (Order $order) {
                        return $order->getTotalAmount();
                    }
                ],
                'currencyId' => [
                    'type' => Type::nonNull(Type::string()),
                    'resolve' => function (Order $order) {
                        return $order->getCurrencyId();
                    }
                ],
                'status' => [
                    'type' => Type::nonNull(Type::string()),
                    'resolve' => function (Order $order) {
                        return $order->getStatus();
                    }
                ],
                'createdAt' => [
                    'type' => Type::nonNull(Type::string()), // Assuming ISO string format for dates
                    'resolve' => function (Order $order) {
                        return $order->getCreatedAt();
                    }
                ],
                'updatedAt' => [
                    'type' => Type::nonNull(Type::string()), // Assuming ISO string format for dates
                    'resolve' => function (Order $order) {
                        return $order->getUpdatedAt();
                    }
                ],
                'items' => [
                    'type' => Type::listOf(Type::nonNull(new OrderItemType())), // You would create OrderItemType similarly
                    'resolve' => function (Order $order) {
                        return $order->getItems(); // Assuming getItems() returns the array of items
                    }
                ]
            ]
        ];

        parent::__construct($config);
    }
}
