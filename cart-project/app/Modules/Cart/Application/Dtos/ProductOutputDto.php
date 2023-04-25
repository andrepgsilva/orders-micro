<?php

namespace App\Modules\Cart\Application\Dtos;

class ProductOutputDto 
{
    public string $id;
    public string $name;
    public string $description;
    public int $quantity;
    public int $price;
    public \Datetime $createdAt;
    public \Datetime $updatedAt;
}