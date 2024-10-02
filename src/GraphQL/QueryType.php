<?php

namespace App\GraphQL;

use App\Repositories\CategoryRepository;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;
use App\Repositories\ProductRepository;

class QueryType extends ObjectType {
    private $productType;
    private $categoryType;
    public function __construct() {
        
        $this->productType = new ProductType();
        $this->categoryType = new CategoryType();
        $config = [
            'name' => 'Query',
            'fields' => [
                'products' => [
                    'type' => Type::listOf($this->productType),
                    'args' => [
                        'category' => Type::nonNull(Type::string())
                    ],
                    'resolve' =>  function ($root, $args) {
                        $productRepository = new ProductRepository();
                        return $productRepository->getAllProducts($args['category']);
                    }
                ],
                'categories' => [
                    'type' => Type::listOf($this->categoryType),
                    'resolve' =>  function () {
                       
                        $categoryRepository = new CategoryRepository();
                        return $categoryRepository->getAllCategories();
                    }
                ],
                'productById' => [
                    'type' => $this->productType,
                    'args' => [
                        'id' => Type::nonNull(Type::string())
                    ],
                    'resolve' => function ($root, $args) {
                        try {
                            $productRepository = new ProductRepository();
                            $data = $productRepository->getProductById($args['id']);
                            return $data;
                        } catch (\Throwable $th) {
                            echo $th->getMessage();
                            return null;
                        }
                        
                    }
                ],
            ],
        ];
        parent::__construct($config);
    }
}
