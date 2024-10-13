<?php

namespace App\Models;

class Gallery
{
    private string $imageUrl;


    public function __construct(string $imageUrl)
    {

        $this->imageUrl = $imageUrl;
    }

    public function getImageUrl(): string
    {
        return $this->imageUrl;
    }
}
