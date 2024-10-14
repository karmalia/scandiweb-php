<?php

namespace App\Models;

class ProductDetail
{
    private $productId;
    private $productName;
    private $quantity;
    private $price;
    private $attributes = [];

    public function __construct($productId, $productName, $quantity, $price, array $attributes = [])
    {
        $this->productId = $productId;
        $this->productName = $productName;
        $this->quantity = $quantity;
        $this->price = $price;
        $this->attributes = $attributes;
    }

    // Getters and Setters
    public function getProductId()
    {
        return $this->productId;
    }

    public function getProductName()
    {
        return $this->productName;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function setAttributes(array $attributes)
    {
        $this->attributes = $attributes;
    }

    public function toArray(): array
    {
        return [
            'productId' => $this->productId,
            'productName' => $this->productName,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'attributes' => $this->attributes
        ];
    }
}
