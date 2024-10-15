<?php

namespace App\Models;

class OrderItem extends BaseModel
{
    private $quantity;
    private $price;
    private $productName;
    private $attributes = [];

    public function __construct($productId, $quantity, $price, $productName)
    {
        $this->id = $productId;
        $this->quantity = $quantity;
        $this->price = $price;
        $this->productName = $productName;
    }

    public function addAttribute(array $attribute)
    {
        $this->attributes[] = $attribute;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }
    public function getPrice()
    {
        return $this->price;
    }
    public function getProductName()
    {
        return $this->productName;
    }
    public function getAttributes()
    {
        return $this->attributes;
    }
}
