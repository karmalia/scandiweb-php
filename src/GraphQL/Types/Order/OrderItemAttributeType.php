<?php

namespace App\GraphQL\Types\Order;

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;

class OrderItemAttributeType extends ObjectType
{
    public function __construct()
    {
        $config = [
            'name' => 'OrderItemAttribute',
            'fields' => [
                'attributeId' => Type::nonNull(Type::string()), // Define attributeId field
                'attributeName' => Type::nonNull(Type::string()), // Define attributeName field
                'attributeValue' => Type::nonNull(Type::string()), // Define attributeValue field
            ]
        ];

        parent::__construct($config);
    }
}
