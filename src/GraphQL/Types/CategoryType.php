<?php

namespace App\GraphQL\Types;

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;

class CategoryType extends ObjectType
{
    public function __construct()
    {
        $config = [
            'name' => 'Category',
            'fields' => [
                'id' => [
                    'type' => Type::nonNull(Type::int()),
                    'resolve' => function ($category) {
                        return $category->id;
                    }
                ],
                'name' => [
                    'type' => Type::nonNull(Type::string()),
                    'resolve' => function ($category) {
                        return $category->name;
                    }
                ],

            ]
        ];
        parent::__construct($config);
    }
}
