<?php

namespace App\GraphQL\Types\ProductTypes;

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;

class CurrencyType extends ObjectType
{
    public function __construct()
    {
        $config = [
            'name' => 'Currency',
            'fields' => [
                'label' => Type::nonNull(Type::string()),
                'symbol' => Type::nonNull(Type::string())
            ]
        ];
        parent::__construct($config);
    }
}
