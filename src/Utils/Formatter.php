<?php

namespace App\Utils;

class Formatter
{
    public static function parseGallery(?string $imageUrls): array
    {
        $gallery = $imageUrls ? preg_split('/\s*,\s*/', $imageUrls, -1, PREG_SPLIT_NO_EMPTY) : [];
        return array_filter(array_map(fn($url) => strpos($url, 'https') === 0 ? $url : 'https' . $url, $gallery));
    }
    public static function toJson(array $data, bool $prettyPrint = false): string
    {
        $options = $prettyPrint ? JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE : 0;
        return json_encode($data, $options);
    }
    
}