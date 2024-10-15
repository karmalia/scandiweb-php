<?php

namespace App\GraphQL\Types\Product;

use App\GraphQL\Schema\TypeRegistry;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;

class PriceType extends ObjectType
{
    public function __construct()
    {
        $config = [
            'name' => 'Price',
            'fields' => [
                'amount' => Type::nonNull(Type::float()),
                "currency" => TypeRegistry::getCurrencyType()
            ]
        ];
        parent::__construct($config);
    }
}
