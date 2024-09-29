<?php

namespace App\GraphQL;

use App\Repositories\CategoryRepository;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;
use App\Repositories\ProductRepository;

class QueryType extends ObjectType {
    public function __construct() {
        $config = [
            'name' => 'Query',
            'fields' => [
                'products' => [
                    'type' => Type::listOf(new ProductType()),
                    'resolve' =>  function () {
                       

                        $productRepository = new ProductRepository();
                        return $productRepository->getAllProducts();
                    }
                ],
                'categories' => [
                    'type' => Type::listOf(new CategoryType()),
                    'resolve' =>  function () {
                       
                        $productRepository = new CategoryRepository();
                        return $productRepository->getAllCategories();
                    }
                ],
            ],
        ];
        parent::__construct($config);
    }
}
