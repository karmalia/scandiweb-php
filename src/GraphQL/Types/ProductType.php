<?php

namespace App\GraphQL\Types;

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
                        return $product->id;
                    }
                ],
                'name' => [
                    'type' => Type::nonNull(Type::string()),
                    'resolve' => function ($product) {
                        return $product->name;
                    }
                ],
                'category' => [
                    'type' => Type::string(),
                    'resolve' => function ($product) {
                        return $product->category;
                    }
                ],
                'description' => [
                    'type' => Type::string(),
                    'resolve' => function ($product) {
                        return $product->description;
                    }
                ],
                'in_stock' => [
                    'type' => Type::boolean(),
                    'resolve' => function ($product) {
                        return $product->in_stock;
                    }
                ],
                'brand' => [
                    'type' => Type::string(),
                    'resolve' => function ($product) {
                        return $product->brand;
                    }
                ],
                'prices' => [
                    'type' => Type::listOf(new PriceType()),
                    'resolve' => function ($product) {
                        return $product->prices;
                    }
                ],
                'gallery' => [
                    'type' => Type::listOf(Type::string()),
                    'resolve' => function ($product) {
                        return $product->gallery;
                    }
                ],
                'attributes' => [
                    'type' => Type::listOf(new AttributeType()),
                    'resolve' => function ($product) {
                        return $product->attributes;
                    }
                ],

            ]
        ];
        parent::__construct($config);
    }
}
