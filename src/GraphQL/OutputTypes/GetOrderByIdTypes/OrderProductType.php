<?php

namespace App\GraphQL\OutputTypes\GetOrderByIdTypes;

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;

class OrderProductType extends ObjectType
{
    public function __construct()
    {
        $config = [
            'name' => 'OrderProductType',
            'fields' => [
                'productId' => Type::nonNull(Type::string()),
                'productName' => Type::nonNull(Type::string()),
                'quantity' => Type::nonNull(Type::int()),
                'price' => Type::nonNull(Type::float()),
                'attributes' => Type::listOf(new OrderAttributeType()) // List of attributes for the product
            ]
        ];

        parent::__construct($config);
    }
}
