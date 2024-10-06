<?php

namespace App\GraphQL\OutputTypes\GetOrderByIdTypes;

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;

class OrderAttributeType extends ObjectType
{
    public function __construct()
    {
        $config = [
            'name' => 'OrderAttributeType',
            'fields' => [
                'attributeId' => Type::nonNull(Type::string()),
                'attributeName' => Type::nonNull(Type::string()),
                'attributeValue' => Type::nonNull(Type::string())
            ]
        ];

        parent::__construct($config);
    }
}
