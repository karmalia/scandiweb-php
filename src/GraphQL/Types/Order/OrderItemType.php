<?php

namespace App\GraphQL\Types\Order;

use App\Models\OrderItem;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;

class OrderItemType extends ObjectType
{
    public function __construct()
    {
        $config = [
            'name' => 'OrderItem',
            'fields' => [
                'productId' => [
                    'type' => Type::nonNull(Type::string()),
                    'resolve' => function (OrderItem $orderItem) {
                        return $orderItem->getId();
                    }
                ],
                'quantity' => [
                    'type' => Type::nonNull(Type::int()),
                    'resolve' => function (OrderItem $orderItem) {
                        return $orderItem->getQuantity();
                    }
                ],
                'price' => [
                    'type' => Type::nonNull(Type::float()),
                    'resolve' => function (OrderItem $orderItem) {
                        return $orderItem->getPrice();
                    }
                ],
                'productName' => [
                    'type' => Type::nonNull(Type::string()),
                    'resolve' => function (OrderItem $orderItem) {
                        return $orderItem->getProductName();
                    }
                ],
                'attributes' => [
                    'type' => Type::listOf(new OrderItemAttributeType()),
                    'resolve' => function (OrderItem $orderItem) {
                        return $orderItem->getAttributes();
                    }
                ]

            ]
        ];

        parent::__construct($config);
    }
}
