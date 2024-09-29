<?php

namespace App\Models;

class Product
{
    private string $id;
    private string $name;

    private string $category;
    private string $description;
    private bool $in_stock;
    private string $brand;
    private array $prices = [];  // Array of Price objects

    private array $gallery = [];

    public function __construct(string $id, string $name, string $description, bool $in_stock, string $brand, string $category)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->in_stock = $in_stock;
        $this->brand = $brand;
        $this->category = $category;
    }

    public function __get($property)
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
        throw new \Exception("Property {$property} does not exist on Price.");
    }

    public function getId(): string
    {
        return $this->id;
    }
    

    public function getName(): string
    {
        return $this->name;
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function isInStock(): bool
    {
        return $this->in_stock;
    }

    public function getBrand(): string
    {
        return $this->brand;
    }

    public function getPrices(): array
    {
        return $this->prices;
    }

    public function getGallery(): array
    {
        return $this->gallery;
    }

    public function setGallery(array $gallery): void
    {
        $this->gallery = $gallery;
    }

    public function setPrices(array $prices): void
{
    $this->prices = $prices;
}

}
