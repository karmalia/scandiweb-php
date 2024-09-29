<?php

namespace App\GraphQL;

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;
use App\Repositories\ProductRepository;

class QueryType extends ObjectType {
    public function __construct() {
        $config = [
            'name' => 'Query',
            'fields' => [
                'product' => [
                    'type' => Type::listOf(new ProductType()),
                    'resolve' =>  function () {
                       

                        $productRepository = new ProductRepository();
                        return $productRepository->getAllProducts();
                    }
                ],
            ],
        ];
        parent::__construct($config);
    }
}
