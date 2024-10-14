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
    public static function mapRowToProduct(array $productData): Product
    {
        $product = new Product(
            $productData[0]['id'],
            $productData[0]['name'],
            $productData[0]['description'],
            $productData[0]['in_stock'],
            $productData[0]['brand'],
            $productData[0]['category_name']
        );

        // Handle prices (use array for unique prices)
        $prices = [];
        foreach ($productData as $row) {
            $currencyId = $row['currency_id'];
            // Only add unique prices based on currency_id
            if (!isset($prices[$currencyId])) {
                $currency = new Currency($row['currency_id'], $row['currency_label'], $row['currency_symbol']);
                $prices[$currencyId] = new Price($row['amount'], $currency);
            }
        }
        $product->setPrices(array_values($prices));  // Make sure it's an array of unique prices

        // Handle images
        $images = explode('||', $productData[0]['image_urls']);
        $product->setGallery($images);

        // Handle attributes and their items
        $attributes = [];
        if ($row['attribute_id']) {
            foreach ($productData as $row) {
                $attributeId = $row['attribute_id'];
                if (!isset($attributes[$attributeId])) {
                    $attributes[$attributeId] = new Attribute($row['attribute_id'], $row['attribute_name'], $row['attribute_type']);
                }

                // Add attribute items to the attribute
                $attributeItem = new AttributeItem($row['attribute_item_id'], $row['attribute_item_value'], $row['attribute_item_display_value']);
                $attributes[$attributeId]->addItem($attributeItem);
            }
            $product->setAttributes(array_values($attributes));
        }




        return $product;
    }




    public static function extractPrices(array $rows): array
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

    public static function groupProducts(array $products): array
    {
        $groupedProducts = [];

        foreach ($products as $product) {
            $productId = $product['id'];

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

            $currentPrices = Self::extractPrices([$product]);
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
