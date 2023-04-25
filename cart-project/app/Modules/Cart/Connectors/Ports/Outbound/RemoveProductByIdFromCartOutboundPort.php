<?php

namespace App\Modules\Cart\Connectors\Ports\Outbound;

interface RemoveProductByIdFromCartOutboundPort
{
    public function remove(string $userId, string $productId): bool;
}