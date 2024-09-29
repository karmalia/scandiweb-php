<?php

namespace App\Models;

class Category
{
    
    private int $id;
    private string $name;

    public function __construct(int $id,  string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    
    public function __get($property)
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
        throw new \Exception("Property {$property} does not exist on Currency.");
    }
}
