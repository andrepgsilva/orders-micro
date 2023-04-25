<?php

namespace App\Modules\Cart\Connectors\Adapters\Primary;

use Illuminate\Http\Request;
use App\Modules\Cart\Application\Dtos\GetCartOutputDto;
use App\Modules\Cart\Connectors\Ports\Inbound\GetCartInboundPort;

class GetCartController 
{
   public function __construct(
        private GetCartInboundPort $getCartUseCase
    ) {}

    public function execute(
        Request $request,
        GetCartOutputDto $getCartOutputDto,
    )
    {
        $userId = $request->validate(['user_id' => 'required|uuid'])['user_id'];

        $cart = $this->getCartUseCase->get($userId);
        $responseContent = $getCartOutputDto->transform($cart, $userId);

        return response()->json($responseContent);
    }
}
