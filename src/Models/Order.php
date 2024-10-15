<?php

namespace App\Models;

class Order extends BaseModel
{

    private $totalAmount;
    private Currency $currency;
    private $status;

    private $items = [];

    public function __construct($id, $totalAmount, Currency $currency, $status, $createdAt, $updatedAt)
    {
        $this->id = $id;
        $this->totalAmount = $totalAmount;
        $this->currency = $currency;
        $this->status = $status;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

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


    public function getTotalAmount()
    {
        return $this->totalAmount;
    }
    public function getCurrency()
    {
        return $this->currency;
    }
    public function getStatus()
    {
        return $this->status;
    }

    public function getItems()
    {
        return $this->items;
    }


    public function jsonSerialize(): array
    {
        return array_merge(parent::jsonSerialize(), [
            'totalAmount' => $this->totalAmount,
            'currency' => $this->currency,
            'status' => $this->status,
            'items' => $this->items
        ]);
    }
}
