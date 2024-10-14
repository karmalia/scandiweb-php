<?php

namespace App\Repositories;

use App\Models\Currency;

class CurrencyRepository extends BaseRepository
{
    // Fetch a currency by ID using the BaseRepository fetchOne method
    public function getCurrencyById(string $id): ?Currency
    {
        $sql = "SELECT * FROM currencies WHERE id = :id";

        // Use the fetchOne method from BaseRepository to get a single row
        $currency = $this->fetchOne($sql, [':id' => $id]);

        if ($currency) {
            return new Currency($currency['id'], $currency['label'], $currency['symbol']);
        }

        return null;
    }
}
