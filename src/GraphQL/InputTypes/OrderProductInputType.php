<?php

namespace App\GraphQL\InputTypes;

use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\Type;

class OrderProductInputType extends InputObjectType
{
    public function __construct()
    {
        $config = [
            'name' => 'OrderProductInput',
            'fields' => [
                'productId' => Type::nonNull(Type::string()),
                'quantity' => Type::nonNull(Type::int())
            ]
        ];

        parent::__construct($config);
    }
}
