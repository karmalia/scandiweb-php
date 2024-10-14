<?php

namespace App\Models;

class Order
{
    private $id;
    private $totalAmount;
    private $currencyId;
    private $status;
    private $createdAt;
    private $updatedAt;
    private $items = [];

    public function __construct($id, $totalAmount, $currencyId, $status, $createdAt, $updatedAt)
    {
        $this->id = $id;
        $this->totalAmount = $totalAmount;
        $this->currencyId = $currencyId;
        $this->status = $status;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    // Find or create an order item by product ID
    public function findOrCreateItem($productId, $quantity, $price, $productName)
    {
        if (!isset($this->items[$productId])) {
            $this->items[$productId] = new OrderItem($productId, $quantity, $price, $productName);
        }

        return $this->items[$productId];
    }

    public function addItem(OrderItem $item)
    {
        $this->items[$item->getId()] = $item;
    }

    // Getters
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
    public function getItems()
    {
        return $this->items;
    }
}
