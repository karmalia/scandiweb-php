<?php

namespace App\GraphQL\InputTypes;

use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\Type;

class OrderProductType extends InputObjectType
{
    public function __construct()
    {
        $config = [
            'name' => 'OrderProductInput',
            'fields' => [
                'productId' => Type::nonNull(Type::string()),
                'quantity' => Type::nonNull(Type::int()),
                'selectedAttributes' => Type::listOf(new SelectedAttributeType())
            ]
        ];

        parent::__construct($config);
    }
}
