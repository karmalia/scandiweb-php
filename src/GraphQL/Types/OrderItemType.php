<?php

namespace App\GraphQL\Types;

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;

class OrderItemType extends ObjectType
{
    public function __construct()
    {
        $config = [
            'name' => 'OrderItem',
            'fields' => [
                'product_id' => [
                    'type' => Type::string()
                ],
                'quantity' => [
                    'type' => Type::int()
                ],
                'price' => [
                    'type' => Type::float()
                ]
            ]
        ];
        parent::__construct($config);
    }
}
