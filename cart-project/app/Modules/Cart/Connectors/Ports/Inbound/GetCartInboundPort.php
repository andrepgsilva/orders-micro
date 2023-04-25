<?php

namespace App\Modules\Cart\Connectors\Ports\Inbound;

use App\Modules\Cart\Domain\Entities\CartEntity;

interface GetCartInboundPort 
{
    function get(string $userId): CartEntity|null;
}