<?php

namespace App\Modules\Cart\Connectors\Adapters\Primary;

use App\Modules\Cart\Application\Dtos\RemoveProductFromCartDto;
use App\Modules\Cart\Connectors\Ports\Inbound\RemoveProductFromCartInboundPort;
use App\Modules\Cart\Connectors\Adapters\Primary\Validators\RemoveProductFromCartRequestValidator;

class RemoveProductFromCartController 
{
    public function __construct(
        private RemoveProductFromCartInboundPort $removeProductFromCartUseCase
    ) {}

    public function execute(
        RemoveProductFromCartRequestValidator $request,
        RemoveProductFromCartDto $removeProductFromCartDto
    )
    {
        $removeProductFromCartDto->userId = $request->user_id;
        $removeProductFromCartDto->productId = $request->product_id;

        $this->removeProductFromCartUseCase->remove($removeProductFromCartDto);

        return response('', 201);
    }
}