<?php

namespace App\GraphQL\InputTypes;

use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\Type;

class SelectedAttributeType extends InputObjectType
{
    public function __construct()
    {
        $config = [
            'name' => 'SelectedAttribute',
            'fields' => [
                'attributeId' => Type::nonNull(Type::string()),
                'attributeItemId' => Type::nonNull(Type::string()),
                'attributeName' => Type::nonNull(Type::string()),
                'attributeValue' => Type::nonNull(Type::string())

            ]
        ];

        parent::__construct($config);
    }
}
