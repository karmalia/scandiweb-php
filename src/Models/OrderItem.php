<?php

namespace App\Models;

class OrderItem
{
    private $productId;
    private $quantity;
    private $price;
    private $productName;
    private $attributes = [];

    public function __construct($productId, $quantity, $price, $productName)
    {
        $this->productId = $productId;
        $this->quantity = $quantity;
        $this->price = $price;
        $this->productName = $productName;
    }

    //write magicGet



    // Method to add attributes to the order item
    public function addAttribute(array $attribute)
    {
        $this->attributes[] = $attribute;
    }

    // Getters for all fields
    public function getId()
    {
        return $this->productId;
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
