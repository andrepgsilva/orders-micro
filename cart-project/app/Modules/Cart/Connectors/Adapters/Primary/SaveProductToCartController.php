<?php

namespace App\Modules\Cart\Connectors\Adapters\Primary;

use Illuminate\Routing\Controller;
use App\Modules\Cart\Application\Dtos\SaveProductToCartDto;
use App\Modules\Cart\Application\Dtos\SaveProductToCartOutputDto;
use App\Modules\Cart\Connectors\Ports\Inbound\SaveProductToCartInboundPort;
use App\Modules\Cart\Application\Usecases\Exceptions\ProductNotFoundException;
use App\Modules\Cart\Connectors\Adapters\Primary\Validators\SaveProductToCartRequestValidator;

class SaveProductToCartController extends Controller
{
    public function __construct(
        private SaveProductToCartInboundPort $saveProductToCartUseCase,
    ) {}

    public function execute(
        SaveProductToCartRequestValidator $request, 
        SaveProductToCartDto $saveProductToCartDto
    )
    {
        $validatedPayload = $request->validated();
        $saveProductToCartDto->productId = $validatedPayload['product_id'];
        $saveProductToCartDto->quantity = $validatedPayload['quantity'];
        $saveProductToCartDto->price = $validatedPayload['price'];
        $saveProductToCartDto->userId = $validatedPayload['user_id'];

        try {
            $cart = $this->saveProductToCartUseCase->save($saveProductToCartDto);
        } catch(\Exception $err) {
            if ($err instanceof ProductNotFoundException) {
                abort(404, $err->getMessage());
            }

            abort(500, 'Internal server error');
        }

        $result = SaveProductToCartOutputDto::transform(
            $cart, 
            $validatedPayload['user_id']
        );

        return response()->json($result, 201);
    }
}
