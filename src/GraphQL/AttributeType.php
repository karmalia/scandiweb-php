<?php

namespace App\GraphQL;

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;

class AttributeType extends ObjectType
{
    public function __construct()
    {
        $config = [
            'name' => 'Attribute',
            'fields' => [
                'id' => [
                    'type' => Type::nonNull(Type::string()),
                    'resolve' => function ($attribute) {
                        return $attribute->id; 
                    }
                ],
                'name' => [
                    'type' => Type::nonNull(Type::string()),
                    'resolve' => function ($attribute) {
                        return $attribute->name; 
                    }
                ],
                'type' => [
                    'type' => Type::nonNull(Type::string()),
                    'resolve' => function ($attribute) {
                        return $attribute->type; 
                    }
                ],
               
                'items' => [
                    'type' => Type::listOf(new PriceType()),
                    'resolve' => function ($attribute) {
                        return $attribute->items; 
                    }
                ],
                
            ]
        ];
        parent::__construct($config);
    }
}
