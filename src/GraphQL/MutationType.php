<?php

namespace App\GraphQL;

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;

use App\GraphQL\Types\OrderType;

use App\Repositories\OrderRepository;


class MutationType extends ObjectType
{
    public function __construct()
    {
        $orderType = new OrderType();

        $config = [
            'name' => 'Mutation',
            'fields' => [
                'createOrder' => [
                    'type' => $orderType,
                    'args' => [
                        'total_amount' => Type::nonNull(Type::float()),
                        'currency_id' => Type::nonNull(Type::string()),
                        'status' => Type::string(),
                    ],
                    'resolve' => function ($root, $args) {
                        $orderRepository = new OrderRepository();
                        $newOrder = $orderRepository->createOrder(
                            $args['total_amount'],
                            $args['currency_id'],
                            $args['status'] ?? 'pending'
                        );

                        if ($newOrder) {
                            return $newOrder;
                        } else {
                            throw new \GraphQL\Error\UserError('Failed to create order.');
                        }
                    }
                ],
            ],
        ];

        parent::__construct($config);
    }
}
