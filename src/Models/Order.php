<?php

namespace App\Models;

class Order
{
    public $id;
    public $totalAmount;
    public $currencyId;
    public $status;
    public $createdAt;
    public $updatedAt;

    public function __construct($id, $totalAmount, $currencyId, $status, $createdAt, $updatedAt)
    {
        $this->id = $id;
        $this->totalAmount = $totalAmount;
        $this->currencyId = $currencyId;
        $this->status = $status;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }
}
