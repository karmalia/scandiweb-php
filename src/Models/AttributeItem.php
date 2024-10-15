<?php

namespace App\Models;

class AttributeItem extends BaseModel
{
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
        return array_merge(parent::jsonSerialize(), [
            'value' => $this->value,
            'displayValue' => $this->displayValue
        ]);
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
