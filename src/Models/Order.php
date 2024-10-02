<?php

namespace App\Models;

class Order
{
    public $id;
    public $total_amount;
    public $currency_id;
    public $status;
    public $created_at;
    public $updated_at;

    public function __construct($id, $totalAmount, $currencyId, $status, $createdAt, $updatedAt)
    {
        $this->id = $id;
        $this->total_amount = $totalAmount;
        $this->currency_id = $currencyId;
        $this->status = $status;
        $this->created_at = $createdAt;
        $this->updated_at = $updatedAt;
    }
}
