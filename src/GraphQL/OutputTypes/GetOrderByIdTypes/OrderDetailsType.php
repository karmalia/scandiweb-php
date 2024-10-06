<?php

namespace App\GraphQL\OutputTypes\GetOrderByIdTypes;

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;

class OrderDetailsType extends ObjectType
{
    public function __construct()
    {
        $config = [
            'name' => 'OrderDetailsType',
            'fields' => [
                'orderId' => Type::nonNull(Type::int()),
                'totalAmount' => Type::nonNull(Type::float()),
                'currencySymbol' => Type::string(),
                'status' => Type::nonNull(Type::string()),
                'createdAt' => Type::nonNull(Type::string()),
                'updatedAt' => Type::nonNull(Type::string()),
                'products' => Type::listOf(new OrderProductType()) // Reference to another type for detailed products
            ]
        ];

        parent::__construct($config);
    }
}
