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
                'id' => [
                    'type' => Type::nonNull(Type::string()),
                    'resolve' => function ($product) {
                        return $product->id; // Access via magic __get
                    }
                ],
                'name' => [
                    'type' => Type::nonNull(Type::string()),
                    'resolve' => function ($product) {
                        return $product->name; // Access via magic __get
                    }
                ],
                'description' => [
                    'type' => Type::string(),
                    'resolve' => function ($product) {
                        return $product->description; // Access via magic __get
                    }
                ],
                'in_stock' => [
                    'type' => Type::boolean(),
                    'resolve' => function ($product) {
                        return $product->in_stock; // Access via magic __get
                    }
                ],
                'brand' => [
                    'type' => Type::string(),
                    'resolve' => function ($product) {
                        return $product->brand; // Access via magic __get
                    }
                ],
                'prices' => [
                    'type' => Type::listOf(new PriceType()),
                    'resolve' => function ($product) {
                        return $product->prices; // Access via magic __get
                    }
                ]
            ]
        ];
        parent::__construct($config);
    }
}
