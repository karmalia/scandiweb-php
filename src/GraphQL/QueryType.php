<?php

namespace App\GraphQL;

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;
use App\Core\Database;

class QueryType extends ObjectType
{
    public function __construct()
    {
        $config = [
            'name' => 'Query',
            'fields' => [
                'product' => [
                    'type' => Type::listOf(new ProductType()),
                    'resolve' => function () {
                        $db = new Database();
                        $conn = $db->connect();

                        $stmt = $conn->prepare("SELECT p.id, p.name, p.description, p.in_stock, p.brand, pr.amount as price
                                                FROM products p
                                                JOIN prices pr ON p.id = pr.product_id");
                        $stmt->execute();
                        return $stmt->fetchAll();
                    }
                ],
            ],
        ];
        parent::__construct($config);
    }
}
