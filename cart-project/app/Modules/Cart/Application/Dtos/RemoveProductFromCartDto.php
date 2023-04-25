<?php

namespace App\Modules\Cart\Application\Dtos;

class RemoveProductFromCartDto 
{
    public string $userId;
    public string $productId;
}