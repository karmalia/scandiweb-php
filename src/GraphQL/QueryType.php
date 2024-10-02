<?php

namespace App\GraphQL;

use App\Repositories\CategoryRepository;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;
use App\Repositories\ProductRepository;

class QueryType extends ObjectType
{
    private $productType;
    private $categoryType;
    public function __construct()
    {

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
                        $data = $productRepository->getAllProducts($args['category']);

                        if ($data) {
                            return $data;
                        } else {
                            throw new \GraphQL\Error\UserError('Products not found');
                        }
                    }
                ],
                'categories' => [
                    'type' => Type::listOf($this->categoryType),
                    'resolve' =>  function () {

                        $categoryRepository = new CategoryRepository();
                        $data = $categoryRepository->getAllCategories();

                        if ($data) {
                            return $data;
                        } else {
                            throw new \GraphQL\Error\UserError('Categories not found');
                        }
                    }
                ],
                'productById' => [
                    'type' => $this->productType,
                    'args' => [
                        'id' => Type::nonNull(Type::string())
                    ],
                    'resolve' => function ($root, $args) {

                        $productRepository = new ProductRepository();
                        $data = $productRepository->getProductById($args['id']);

                        if ($data) {
                            return $data;
                        } else {
                            throw new \GraphQL\Error\UserError('Product not found');
                        }
                    }
                ],
            ],
        ];
        parent::__construct($config);
    }
}
