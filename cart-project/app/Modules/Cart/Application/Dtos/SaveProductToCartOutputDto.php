<?php

namespace App\Modules\Cart\Application\Dtos;
use App\Modules\Cart\Domain\Entities\CartEntity;

class SaveProductToCartOutputDto
{
    public static function transform(CartEntity $cart, string $userId)
    {
        return [
            'id' => $cart->id->value,
            'user_id' => $userId,
            'total' => $cart->total,
            'quantity' => $cart->quantity,
            'products' => $cart->products,
            'created_at' => $cart->createdAt,
            'updated_at' => $cart->updatedAt,
        ];
    }
}