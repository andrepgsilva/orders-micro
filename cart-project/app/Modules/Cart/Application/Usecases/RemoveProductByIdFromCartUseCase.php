<?php

namespace App\Modules\Cart\Application\Usecases;
use App\Modules\Cart\Application\Dtos\RemoveProductFromCartDto;
use App\Modules\Cart\Connectors\Ports\Inbound\RemoveProductFromCartInboundPort;
use App\Modules\Cart\Connectors\Ports\Outbound\RemoveProductByIdFromCartOutboundPort;

class RemoveProductByIdFromCartUseCase implements RemoveProductFromCartInboundPort
{
    public function __construct(
        private RemoveProductByIdFromCartOutboundPort $removeProductAdapter
    ) {}

    public function remove(RemoveProductFromCartDto $payload): bool
    {
        return $this->removeProductAdapter->remove(
            $payload->userId,
            $payload->productId
        );
    }
}