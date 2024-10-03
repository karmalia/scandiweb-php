<?php

namespace App\GraphQL;

use GraphQL\Type\Definition\ObjectType;

use App\GraphQL\Mutations\OrderMutation; // Import your specific mutation classes

class MutationType extends ObjectType
{
    public function __construct()
    {
        $orderMutation = new OrderMutation();

        $config = [
            'name' => 'Mutation',
            'fields' => [
                // Include all the fields from your OrderMutation class
                'createOrder' => $orderMutation->getCreateOrderField(),
                // Here you can add more mutation fields such as update, delete, etc.
            ],
        ];
        parent::__construct($config);
    }
}
