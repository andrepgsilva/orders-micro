<?php

namespace App\Modules\Cart\Connectors\Ports\Inbound;

use App\Modules\Cart\Domain\Entities\CartEntity;
use App\Modules\Cart\Application\Dtos\SaveProductToCartDto;

interface SaveProductToCartInboundPort
{
    public function save(SaveProductToCartDto $payload): CartEntity|null;
}