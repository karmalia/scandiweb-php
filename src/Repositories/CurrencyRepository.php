<?php

namespace App\Repositories;

use App\Models\Currency;
use App\Core\Database;

class CurrencyRepository
{
    // Not used in current implementation
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->connect();
    }

    public function getCurrencyById(string $id): ?Currency
    {
        $stmt = $this->db->prepare("SELECT * FROM currencies WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $currency = $stmt->fetch();

        if ($currency) {
            return new Currency($currency['id'], $currency['label'], $currency['symbol']);
        }

        return null;
    }
}
