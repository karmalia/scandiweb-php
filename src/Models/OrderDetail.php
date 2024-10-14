<?php

namespace App\Models;

class OrderDetail
{
    private $id;
    private $totalAmount;
    private $currencyId;
    private $currencyLabel;
    private $currencySymbol;
    private $status;
    private $createdAt;
    private $updatedAt;
    private $products = [];

    public function __construct($id, $totalAmount, $currencyId, $currencyLabel, $currencySymbol, $status, $createdAt, $updatedAt, array $products = [])
    {
        $this->id = $id;
        $this->totalAmount = $totalAmount;
        $this->currencyId = $currencyId;
        $this->currencyLabel = $currencyLabel;
        $this->currencySymbol = $currencySymbol;
        $this->status = $status;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->products = $products;
    }

    // Getters and Setters
    public function getId()
    {
        return $this->id;
    }

    public function getTotalAmount()
    {
        return $this->totalAmount;
    }

    public function getCurrencyId()
    {
        return $this->currencyId;
    }

    public function getCurrencyLabel()
    {
        return $this->currencyLabel;
    }

    public function getCurrencySymbol()
    {
        return $this->currencySymbol;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function getProducts(): array
    {
        return $this->products;
    }

    public function setProducts(array $products)
    {
        $this->products = $products;
    }

    public function addProduct(ProductDetail $product)
    {
        $this->products[] = $product;
    }

    public function toArray(): array
    {
        return [
            'orderId' => $this->id,
            'totalAmount' => $this->totalAmount,
            'currencyId' => $this->currencyId,
            'currencyLabel' => $this->currencyLabel,
            'currencySymbol' => $this->currencySymbol,
            'status' => $this->status,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
            'products' => array_map(function ($product) {
                return $product->toArray();
            }, $this->products)
        ];
    }
}
