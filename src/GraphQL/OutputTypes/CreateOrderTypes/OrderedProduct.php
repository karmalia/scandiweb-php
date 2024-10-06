<?php

namespace App\GraphQL\OutputTypes\CreateOrderTypes;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class OrderedProduct extends ObjectType
{
    public function __construct()
    {
        $config = [
            'name' => 'OrderedProduct',
            'fields' => [
                'productName' => Type::nonNull(Type::string()),
                'quantity' => Type::nonNull(Type::string()),
            ]
        ];

        parent::__construct($config);
    }
}
