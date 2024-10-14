<?php

namespace App\GraphQL\Types\ProductTypes;

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
                        return $product->getId();
                    }
                ],
                'name' => [
                    'type' => Type::nonNull(Type::string()),
                    'resolve' => function ($product) {
                        return $product->getName();
                    }
                ],
                'category' => [
                    'type' => Type::string(),
                    'resolve' => function ($product) {
                        return $product->getCategory();
                    }
                ],
                'description' => [
                    'type' => Type::string(),
                    'resolve' => function ($product) {
                        return $product->getDescription();
                    }
                ],
                'in_stock' => [
                    'type' => Type::boolean(),
                    'resolve' => function ($product) {
                        return $product->isInStock();
                    }
                ],
                'brand' => [
                    'type' => Type::string(),
                    'resolve' => function ($product) {
                        return $product->getBrand();
                    }
                ],
                'prices' => [
                    'type' => Type::listOf(new PriceType()),
                    'resolve' => function ($product) {
                        return $product->getPrices();
                    }
                ],
                'gallery' => [
                    'type' => Type::listOf(Type::string()),
                    'resolve' => function ($product) {
                        return $product->getGallery();
                    }
                ],
                'attributes' => [
                    'type' => Type::listOf(new AttributeType()),
                    'resolve' => function ($product) {
                        return $product->getAttributes();
                    }
                ],

            ]
        ];
        parent::__construct($config);
    }
}
