<?php

namespace App\GraphQL\Schema;

use App\GraphQL\Types\Product\CurrencyType;
use App\GraphQL\Types\Product\PriceType;
use App\GraphQL\Types\Order\OrderType;
use App\GraphQL\Types\Product\AttributeItemType;
use App\GraphQL\Types\Product\AttributeType;

class TypeRegistry
{
    private static $types = [];

    public static function getCurrencyType()
    {
        if (!isset(self::$types['currency'])) {
            self::$types['currency'] = new CurrencyType();
        }
        return self::$types['currency'];
    }

    public static function getPriceType()
    {
        if (!isset(self::$types['price'])) {
            self::$types['price'] = new PriceType();
        }
        return self::$types['price'];
    }

    public static function getOrderType()
    {
        if (!isset(self::$types['order'])) {
            self::$types['order'] = new OrderType();
        }
        return self::$types['order'];
    }

    public static function getAttributeType()
    {
        if (!isset(self::$types['attribute'])) {
            self::$types['attribute'] = new AttributeType();
        }
        return self::$types['attribute'];
    }

    public static function getAttributeItemType()
    {
        if (!isset(self::$types['attribute_item'])) {
            self::$types['attribute_item'] = new AttributeItemType();
        }
        return self::$types['attribute_item'];
    }
}
