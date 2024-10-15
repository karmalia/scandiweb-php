<?php

namespace App\Models;

class Gallery extends BaseModel
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

    public function setImageUrl(string $imageUrl): void
    {
        $this->imageUrl = $imageUrl;
    }
}
