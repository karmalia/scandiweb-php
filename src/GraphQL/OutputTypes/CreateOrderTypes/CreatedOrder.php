<?php

namespace App\GraphQL\OutputTypes\CreateOrderTypes;

use App\GraphQL\OutputTypes\CreateOrderTypes\OrderedProduct;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class CreatedOrder extends ObjectType
{
    public function __construct()
    {
        $config = [
            'name' => 'CreatedOrderOutput',
            'fields' => [
                'orderId' => Type::string(),
                'status' => Type::nonNull(Type::string()),
                'orderedProducts' => Type::listOf(new OrderedProduct()),
                'error' => Type::string()
            ]
        ];

        parent::__construct($config);
    }
}
