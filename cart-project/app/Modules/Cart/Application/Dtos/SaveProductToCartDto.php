<?php

namespace App\Modules\Cart\Application\Dtos;

class SaveProductToCartDto 
{
    public string $productId;
    public int $quantity;
    public int $price;
    public string $userId;
}