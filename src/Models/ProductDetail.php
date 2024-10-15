<?php

namespace App\Models;

class ProductDetail extends BaseModel
{
    private $productName;
    private $quantity;
    private $price;
    private $attributes = [];

    public function __construct($productId, $productName, $quantity, $price, array $attributes = [])
    {
        $this->id = $productId;
        $this->productName = $productName;
        $this->quantity = $quantity;
        $this->price = $price;
        $this->attributes = $attributes;
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
            'productId' => $this->id,
            'productName' => $this->productName,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'attributes' => $this->attributes
        ];
    }
}
