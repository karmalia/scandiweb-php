<?php

namespace App\GraphQL\Queries;

use App\GraphQL\OutputTypes\GetOrderByIdTypes\OrderDetailsType;
use GraphQL\Type\Definition\Type;
use App\Repositories\OrderRepository;
use App\GraphQL\Types\OrderType;


class OrderQuery
{

    private $orderType;
    private $orderDetailsType;

    public function __construct()
    {
        $this->orderDetailsType = new OrderDetailsType();
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
            'type' => $this->orderDetailsType,
            'args' => [
                'id' => Type::nonNull(Type::int())
            ],
            'resolve' => function ($root, $args) {
                $orderRepository = new OrderRepository();
                $data = $orderRepository->getOrderDetailsById($args['id']);


                return $data;
            }

        ];
    }
}
