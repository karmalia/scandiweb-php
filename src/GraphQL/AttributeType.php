<?php

namespace App\GraphQL;

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;
use App\Models\Attribute;

class AttributeType extends ObjectType
{
  
    public function __construct()
    {
        $config = [
            'name' => 'Attribute',
            'fields' => [
                'id' => [
                    'type' => Type::nonNull(Type::string()),
                    'resolve' => function (Attribute $attribute) {
                        return $attribute->getId(); 
                    }
                ],
                'name' => [
                    'type' => Type::nonNull(Type::string()),
                    'resolve' => function (Attribute $attribute) {
                        return $attribute->getName(); 
                    }
                ],
                'type' => [
                    'type' => Type::nonNull(Type::string()),
                    'resolve' => function (Attribute $attribute) {
                        return $attribute->getType();
                    }
                ],
               
                'items' => [
                    'type' => Type::listOf(new AttributeItemType()),
                    'resolve' => function (Attribute $attribute):array {
                        return $attribute->getItems();
                    }
                ],
                
            ]
        ];
        parent::__construct($config);
    }
}
