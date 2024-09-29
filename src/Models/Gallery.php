<?php

namespace App\Models;

class Gallery
{
    private string $imageUrl;


    public function __construct(string $imageUrl)
    {
        
        $this->imageUrl = $imageUrl;
    }

    public function __get($property)
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
        throw new \Exception("Property {$property} does not exist on Currency.");
    }
}
