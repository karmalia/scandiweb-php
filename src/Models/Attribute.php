<?php

namespace App\Models;

class Attribute extends BaseModel
{
    private string $name;
    private string $type;
    private array $items = [];

    public function __construct(string $id, string $name, string $type, array $items = [])
    {
        $this->id = $id;
        $this->name = $name;
        $this->type = $type;
        $this->items = $items;
    }


    public function jsonSerialize(): array
    {
        return array_merge(parent::jsonSerialize(), [
            'name' => $this->name,
            'type' => $this->type,
            'items' => $this->items
        ]);
    }


    public function addItem(AttributeItem $item)
    {
        $this->items[] = $item;
    }

    public function getId(): string
    {
        return $this->id;
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function getType(): string
    {
        return $this->type;
    }
    public function getItems(): array
    {
        return $this->items;
    }
}
