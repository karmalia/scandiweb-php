<?php

namespace App\Models;

class Currency implements \JsonSerializable
{
    private string $id;        
    private string $label;     
    private string $symbol;    

    public function __construct(string $id, string $label, string $symbol)
    {
        $this->id = $id;
        $this->label = $label;
        $this->symbol = $symbol;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getSymbol(): string
    {
        return $this->symbol;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'label' => $this->label,
            'symbol' => $this->symbol
        ];
    }

    public function __get($property)
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
        throw new \Exception("Property {$property} does not exist on Currency.");
    }
}
