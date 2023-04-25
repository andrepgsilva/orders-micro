<?php

namespace App\Modules\Cart\Domain\Entities\Factories;

use App\Modules\Cart\Domain\Entities\CartEntity;

class CartFactory
{
    static function create(
        array $products,
        int $quantity,
        int $total,
        string $id = null,
        \Datetime $createdAt = null,
        \Datetime $updatedAt = null
    ): CartEntity {
        return new CartEntity(
            $products, 
            $quantity, 
            $total, 
            $id, 
            $createdAt, 
            $updatedAt
        );
    }
}
