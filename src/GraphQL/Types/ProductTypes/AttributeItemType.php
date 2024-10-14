<?php

namespace App\GraphQL\Types\ProductTypes;

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;
use App\Models\AttributeItem;

class AttributeItemType extends ObjectType
{
    public function __construct()
    {
        $config = [
            'name' => 'AttributeItem',
            'fields' => [
                'id' => [
                    'type' => Type::nonNull(Type::string()),
                    'resolve' => function (AttributeItem $item) {
                        return $item->getId();
                    }
                ],
                'value' => [
                    'type' => Type::string(),
                    'resolve' => function (AttributeItem $item) {
                        return $item->getValue();
                    }
                ],
                'displayValue' => [
                    'type' => Type::string(),
                    'resolve' => function (AttributeItem $item) {
                        return $item->getDisplayValue();
                    }
                ],
            ]
        ];
        parent::__construct($config);
    }
}
