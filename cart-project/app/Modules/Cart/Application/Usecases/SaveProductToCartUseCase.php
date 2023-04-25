<?php

namespace App\Modules\Cart\Application\Usecases;

use App\Modules\Cart\Domain\Entities\CartEntity;
use App\Modules\Cart\Application\Dtos\SaveProductToCartDto;
use App\Modules\Cart\Connectors\Ports\Outbound\GetProductByIdOutputPort;
use App\Modules\Cart\Connectors\Ports\Inbound\SaveProductToCartInboundPort;
use App\Modules\Cart\Connectors\Ports\Outbound\SaveProductToCartOutboundPort;
use App\Modules\Cart\Application\Usecases\Exceptions\ProductNotFoundException;

class SaveProductToCartUseCase implements SaveProductToCartInboundPort
{
    public function __construct(
        private SaveProductToCartOutboundPort $saveProductAdapter,
        private GetProductByIdOutputPort $getProductByIdAdapter
    ) {}

    public function save(SaveProductToCartDto $payload): CartEntity|null
    {
        if ($this->getProductByIdAdapter->execute($payload->productId) === null) {
            throw new ProductNotFoundException();
        }

        return $this->saveProductAdapter->save(
            $payload->productId,
            $payload->quantity,
            $payload->price,
            $payload->userId,
        );
    }
}