<?php

namespace App\Modules\Cart\Connectors\Ports\Outbound;

use App\Modules\Cart\Domain\Entities\CartEntity;

interface GetCartByUserIdOutboundPort
{
    function getByUserId(string $userId): CartEntity|null;
}
