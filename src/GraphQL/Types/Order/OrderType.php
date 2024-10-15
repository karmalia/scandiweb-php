<?php

namespace App\GraphQL\Types\Order;

use App\GraphQL\Schema\TypeRegistry;
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
                'currency' => [
                    'type' => Type::nonNull(TypeRegistry::getCurrencyType()),
                    'resolve' => function (Order $order) {
                        return $order->getCurrency();
                    }
                ],
                'status' => [
                    'type' => Type::nonNull(Type::string()),
                    'resolve' => function (Order $order) {
                        return $order->getStatus();
                    }
                ],
                'createdAt' => [
                    'type' => Type::nonNull(Type::string()),
                    'resolve' => function (Order $order) {
                        return $order->getCreatedAt();
                    }
                ],
                'updatedAt' => [
                    'type' => Type::nonNull(Type::string()),
                    'resolve' => function (Order $order) {
                        return $order->getUpdatedAt();
                    }
                ],
                'items' => [
                    'type' => Type::listOf(Type::nonNull(new OrderItemType())),
                    'resolve' => function (Order $order) {
                        return $order->getItems();
                    }
                ]
            ]
        ];

        parent::__construct($config);
    }
}
