<?php

namespace App\Utils;

class ArrayHelper
{
    public static function containsItem(array $items, string $itemId, string $getterMethod): bool
    {
        foreach ($items as $item) {
            if (method_exists($item, $getterMethod) && $item->$getterMethod() === $itemId) {
                return true;
            }
        }
        return false;
    }
    
}