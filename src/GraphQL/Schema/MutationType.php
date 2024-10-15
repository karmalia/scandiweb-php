<?php

namespace App\GraphQL\Schema;

use GraphQL\Type\Definition\ObjectType;

use App\GraphQL\Mutations\OrderMutation;

class MutationType extends ObjectType
{
    public function __construct()
    {
        $orderMutation = new OrderMutation();

        $config = [
            'name' => 'Mutation',
            'fields' => [
                'createOrder' => $orderMutation->getCreateOrderField(),
                'updateOrder' => $orderMutation->updateOrderStatusField(),
            ],
        ];
        parent::__construct($config);
    }
}
