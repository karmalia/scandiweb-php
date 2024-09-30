<?php

namespace App\Models;

class AttributeItem implements \JsonSerializable
{
    private string $id;
    private string $value;
    private string $displayValue;

    public function __construct(string $id, string $value, string $displayValue)
    {
        $this->id = $id;
        $this->value = $value;
        $this->displayValue = $displayValue;
    }

    
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'value' => $this->value,
            'displayValue' => $this->displayValue
        ];
    }

    public function getId()
    {
        return $this->id;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getDisplayValue()
    {
        return $this->displayValue;
    } 
}




