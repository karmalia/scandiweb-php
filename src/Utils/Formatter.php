<?php

namespace App\Utils;

class Formatter
{
    public static function parseGallery(?string $imageUrls): array
    {
        if (!$imageUrls) {
            return [];
        }

        // Clean up each URL by trimming whitespace
        return explode('||', $imageUrls);
    }


    public static function toJson(array $data, bool $prettyPrint = false): string
    {
        $options = $prettyPrint ? JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE : 0;
        return json_encode($data, $options);
    }
}
