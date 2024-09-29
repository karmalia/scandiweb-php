<?php

namespace App\Models;

class AttributeItem
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

    public function getId(): string { return $this->id; }
    public function getValue(): string { return $this->value; }
    public function getDisplayValue(): string { return $this->displayValue; }
}
