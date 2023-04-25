<?php

namespace App\Modules\Cart\Connectors\Ports\Outbound;

use App\Modules\Cart\Domain\Entities\CartEntity;

interface SaveProductToCartOutboundPort
{
    function save(string $productId, int $quantity, int $price, string $userId): CartEntity|null;
}

