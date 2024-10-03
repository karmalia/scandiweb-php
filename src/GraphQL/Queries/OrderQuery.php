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
                $data = $orderRepository->getAllOrders();

                if ($data) {
                    return $data;
                } else {
                    throw new \GraphQL\Error\UserError('Orders not found');
                }
            }

        ];
    }
}
