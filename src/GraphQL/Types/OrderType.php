<?php

namespace App\GraphQL\Types;

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;

class OrderType extends ObjectType
{
    public function __construct()
    {
        $config = [
            'name' => 'Order',
            'fields' => [
                'orderId' => [
                    'type' => Type::nonNull(Type::int())
                ],
                'totalAmount' => [
                    'type' => Type::float()
                ],
                'currencyId' => [
                    'type' => Type::string()
                ],
                'status' => [
                    'type' => Type::string()
                ],
                'createdAt' => [
                    'type' => Type::string()
                ],
                'updatedAt' => [
                    'type' => Type::string()
                ],
                'items' => [
                    'type' => Type::listOf(new OrderItemType()) // Define OrderItemType for the order items.
                ]
            ]
        ];
        parent::__construct($config);
    }
}
