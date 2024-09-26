<?php

namespace App\GraphQL;

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;

class ProductType extends ObjectType
{
    public function __construct()
    {
        $config = [
            'name' => 'Product',
            'fields' => [
                'id' => Type::nonNull(Type::string()),
                'name' => Type::nonNull(Type::string()),
                'description' => Type::string(),
                'in_stock' => Type::boolean(),
                'brand' => Type::string(),
                'price' => Type::float(),
            ]
        ];
        parent::__construct($config);
    }
}
