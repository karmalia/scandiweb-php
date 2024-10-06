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
                'total_amount' => [
                    'type' => Type::float()
                ],
                'currency_id' => [
                    'type' => Type::string()
                ],
                'status' => [
                    'type' => Type::string()
                ],
                'created_at' => [
                    'type' => Type::string()
                ],
                'updated_at' => [
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
