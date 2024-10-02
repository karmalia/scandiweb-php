<?php

namespace App\GraphQL;

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;

use App\GraphQL\Types\ProductType;
use App\GraphQL\Types\CategoryType;
use App\GraphQL\Types\OrderType;

use App\Repositories\CategoryRepository;
use App\Repositories\ProductRepository;
use App\Repositories\OrderRepository;

class QueryType extends ObjectType
{
    private $productType;
    private $categoryType;
    private $orderType;
    public function __construct()
    {

        $this->productType = new ProductType();
        $this->categoryType = new CategoryType();
        $this->orderType = new OrderType();
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
                'getOrders' => [
                    'type' => Type::listOf($this->orderType),
                    'resolve' => function () {
                        $orderRepository = new OrderRepository();
                        $data = $orderRepository->getAllOrders();

                        if ($data) {
                            return $data;
                        } else {
                            throw new \GraphQL\Error\UserError('Orders not found');
                        }
                    }

                ]
            ],
        ];
        parent::__construct($config);
    }
}
