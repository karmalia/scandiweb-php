<?php

namespace App\Models;

class Category extends BaseModel
{

    private string $name;

    public function __construct(int $id,  string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
