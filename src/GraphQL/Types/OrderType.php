<?php

namespace App\GraphQL\Types;

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;

class OrderType extends ObjectType
{
    public function __construct()
    {
        $config = [
            'name' => 'Order',
            'fields' => [
                'id' => Type::nonNull(Type::int()),
                'total_amount' => Type::nonNull(Type::float()),
                'currency_id' => Type::nonNull(Type::string()),
                'status' => Type::nonNull(Type::string()),
                'created_at' => Type::string(),
                'updated_at' => Type::string(),
            ],
        ];
        parent::__construct($config);
    }
}
