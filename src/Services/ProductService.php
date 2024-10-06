<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Price;
use App\Models\Currency;
use App\Models\Attribute;
use App\Models\AttributeItem;
use App\Utils\Formatter;
use App\Utils\ArrayHelper;

class ProductService
{
    // Can be converted to a static method in future?
    public function mapRowToProduct(array $rows): ?Product
    {

        if (empty($rows)) {
            return null;
        }

        $firstRow = $rows[0];
        $product = new Product(
            $firstRow['id'],
            $firstRow['name'],
            $firstRow['description'],
            (bool) $firstRow['in_stock'],
            $firstRow['brand'],
            $firstRow['category_name']
        );


        $product->setGallery(Formatter::parseGallery($firstRow['image_urls']));
        $product->setPrices($this->extractPrices($rows));
        $product->setAttributes($this->groupAttributesByProduct($rows));

        return $product;
    }



    public function extractPrices(array $rows): array
    {
        $prices = [];
        $seenPrices = [];

        foreach ($rows as $row) {
            $priceKey = $row['amount'] . '-' . $row['currency_id'];
            if (!empty($row['amount']) && !isset($seenPrices[$priceKey])) {
                $currency = new Currency($row['currency_id'], $row['currency_label'], $row['currency_symbol']);
                $prices[] = new Price($row['amount'], $currency);
                $seenPrices[$priceKey] = true;
            }
        }

        return $prices;
    }

    public function groupAttributesByProduct(array $productData): array
    {
        $attributes = [];

        foreach ($productData as $product) {
            $attributeId = $product['attribute_id'];
            if (!$attributeId) {
                continue;
            }

            if (!isset($attributes[$attributeId])) {
                $attributes[$attributeId] = new Attribute($attributeId, $product['attribute_name'], $product['attribute_type'], []);
            }

            $attributeItemId = $product['attribute_item_id'];
            if ($attributeItemId) {
                $attributeItem = new AttributeItem($attributeItemId, $product['attribute_item_value'], $product['attribute_item_display_value']);
                $existingItems = $attributes[$attributeId]->getItems();

                if (!ArrayHelper::containsItem($existingItems, $attributeItemId, 'getId')) {
                    $attributes[$attributeId]->addItem($attributeItem);
                }
            }
        }

        return array_values($attributes);
    }

    public function groupProducts(array $products): array
    {
        $groupedProducts = [];

        foreach ($products as $product) {
            $productId = $product['id'];

            // If the product is not in the grouped array, add it
            if (!isset($groupedProducts[$productId])) {

                $groupedProducts[$productId] = new Product(
                    $product['id'],
                    $product['name'],
                    $product['description'],
                    (bool) $product['in_stock'],
                    $product['brand'],
                    $product['category_name']
                );
            }

            // Only add the price if all required price fields are present
            $currentPrices = $this->extractPrices([$product]);
            if (!empty($currentPrices)) {
                $groupedProducts[$productId]->setPrices($currentPrices);
            }

            if ($product['image_urls']) {
                $groupedProducts[$productId]->setGallery(Formatter::parseGallery($product['image_urls']));
            }
        }

        return array_values($groupedProducts);
    }
}
