<?php

namespace App\GraphQL\Queries;

use GraphQL\Type\Definition\Type;

use App\Repositories\ProductRepository;
use App\Repositories\CategoryRepository;

use App\GraphQL\Types\Product\CategoryType;

use App\GraphQL\Types\Product\ProductType;


class ProductQuery
{
    private $productType;
    private $categoryType;

    public function __construct()
    {
        $this->productType = new ProductType();
        $this->categoryType = new CategoryType();
    }

    public function getProducts(): array
    {
        return [
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
        ];
    }

    public function getProductById(): array
    {
        return [
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
        ];
    }

    public function getCategories(): array
    {
        return [
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
        ];
    }
}
