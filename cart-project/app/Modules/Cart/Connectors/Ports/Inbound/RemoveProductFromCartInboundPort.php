<?php

namespace App\Modules\Cart\Connectors\Ports\Inbound;

use App\Modules\Cart\Application\Dtos\RemoveProductFromCartDto;

interface RemoveProductFromCartInboundPort 
{
    function remove(RemoveProductFromCartDto $payload): bool;
}