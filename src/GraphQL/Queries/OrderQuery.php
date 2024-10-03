<?php

namespace App\GraphQL\Queries;

use GraphQL\Type\Definition\Type;
use App\Repositories\OrderRepository;

use App\GraphQL\Types\OrderType;


class OrderQuery
{

    private $orderType;

    public function __construct()
    {
        $this->orderType = new OrderType();;
    }

    public function getOrders(): array
    {
        return [
            'type' => Type::listOf($this->orderType),
            'resolve' => function () {
                $orderRepository = new OrderRepository();
                $data = $orderRepository->getAllOrdersWithItems();

                if ($data) {
                    return $data;
                } else {
                    throw new \GraphQL\Error\UserError('Orders not found');
                }
            }

        ];
    }

    public function getOrderById(): array
    {
        return [
            'type' => $this->orderType,
            'args' => [
                'id' => Type::nonNull(Type::int())
            ],
            'resolve' => function ($root, $args) {
                $orderRepository = new OrderRepository();
                $data = $orderRepository->getOrderDetailsById($args['id']);

                /*
                [
    {
        "order_id": 18,
        "total_amount": "800.22",
        "currency_id": "USD",
        "status": "pending",
        "created_at": "2024-10-03 15:23:59",
        "updated_at": "2024-10-03 15:23:59",
        "product_id": "apple-airpods-pro",
        "quantity": 1,
        "price": "300.23",
        "product_name": "AirPods Pro",
        "currency_label": "USD",
        "currency_symbol": "$"
    },
    {
        "order_id": 18,
        "total_amount": "800.22",
        "currency_id": "USD",
        "status": "pending",
        "created_at": "2024-10-03 15:23:59",
        "updated_at": "2024-10-03 15:23:59",
        "product_id": "xbox-series-s",
        "quantity": 1,
        "price": "333.99",
        "product_name": "Xbox Series S 512GB",
        "currency_label": "USD",
        "currency_symbol": "$"
    }
]

                    "product_id": "xbox-series-s",
        "quantity": 1,
        "price": "333.99",
        "product_name": "Xbox Series S 512GB",
        "currency_label": "USD",
        "currency_symbol": "$"


        bu kısmı items adı altıdna gruplamak lazım, 1 tane obje dönmesi lazım ayrı bir gruplama methodu yazılmalı
                
                
                */
                return $data;
            }

        ];
    }
}
