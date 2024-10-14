<?php

namespace App\GraphQL\Queries;

use GraphQL\Type\Definition\Type;
use App\Repositories\OrderRepository;
use App\GraphQL\Types\OrderTypes\OrderType;
use App\Models\Order;

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
                $orders = $orderRepository->fetchAllOrders();

                // Assuming $orders contains an array of Order model instances
                if ($orders) {
                    return $orders;
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
            'resolve' => function ($root, $args): Order|null {
                $orderRepository = new OrderRepository();
                $data = $orderRepository->fetchOrderById($args['id']);


                return $data;
            }

        ];
    }
}
