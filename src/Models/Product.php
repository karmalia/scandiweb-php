<?php

namespace App\Models;


class Product extends BaseModel
{
    private string $name;

    private string $category;
    private string $description;
    private bool $in_stock;
    private string $brand;
    private array $prices = [];

    private array $gallery = [];

    private array $attributes = [];

    public function __construct(string $id, string $name, string $description, bool $in_stock, string $brand, string $category)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->in_stock = $in_stock;
        $this->brand = $brand;
        $this->category = $category;
    }

    public function jsonSerialize(): array
    {
        return array_merge(parent::jsonSerialize(), [
            'name' => $this->name,
            'category' => $this->category,
            'description' => $this->description,
            'in_stock' => $this->in_stock,
            'brand' => $this->brand,
            'prices' => $this->prices,
            'gallery' => $this->gallery,
            'attributes' => $this->attributes
        ]);
    }



    public function logProduct(): void
    {
        echo "Product: {$this->name} - {$this->description} - {$this->brand} - {$this->category} - {$this->in_stock} - {$this->id} - Attributes: \n" . var_dump($this->attributes);
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

    public function getAttributes(): array
    {

        return $this->attributes;
    }

    public function setAttributes(array $attributes): void
    {
        try {

            $this->attributes = $attributes;
        } catch (\Throwable $th) {
            echo "ERROR" . $th;
        }
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
