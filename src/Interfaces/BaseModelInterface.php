<?php

namespace App\Interfaces;

interface BaseModelInterface
{
    public function findById($id);
    public function findAll();
    public function insert($data, $table);
}