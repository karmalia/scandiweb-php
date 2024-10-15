<?php

namespace App\Repositories;

use App\Models\Currency;

class CurrencyRepository extends BaseRepository
{
    public function getCurrencyById(string $id): ?Currency
    {
        $sql = "SELECT * FROM currencies WHERE id = :id";

        $currency = $this->fetchOne($sql, [':id' => $id]);

        if ($currency) {
            return new Currency($currency['id'], $currency['label'], $currency['symbol']);
        }

        return null;
    }
}
