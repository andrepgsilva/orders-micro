<?php

namespace App\Modules\Cart\Connectors\Adapters\Primary\Tests;

use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Factory;
use Illuminate\Translation\Translator;
use Illuminate\Translation\ArrayLoader;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Routing\ResponseFactory;
use App\Modules\Cart\Application\Dtos\SaveProductToCartDto;
use App\Modules\Cart\Domain\Entities\Factories\CartFactory;
use App\Modules\Cart\Application\Dtos\SaveProductToCartOutputDto;
use App\Modules\Cart\Connectors\Ports\Inbound\SaveProductToCartInboundPort;
use App\Modules\Cart\Connectors\Adapters\Primary\SaveProductToCartController;
use App\Modules\Cart\Connectors\Adapters\Primary\Validators\SaveProductToCartRequestValidator;


class SaveProductToCartControllerTest extends TestCase
{
    public function test_it_can_save_a_product_in_the_cart() 
    {
        $payload =  [
            'product_id' => '4002d872-8df7-4f7b-a32f-ea87ca26a66e',
            'quantity' => 96,
            'price' => 41,
            'user_id' => '39caf222-d55c-4ea5-91d8-ebac68a58123',
        ];

        $dtoData = new SaveProductToCartDto();
        $dtoData->productId = $payload['product_id'];
        $dtoData->quantity = $payload['quantity'];
        $dtoData->price = $payload['price'];
        $dtoData->userId = $payload['user_id'];

        $cartEntity = CartFactory::create([$payload], $dtoData->quantity, $dtoData->price);
    
        $mock = Mockery::mock(
            SaveProductToCartInboundPort::class, 
            function (MockInterface $mock) use ($cartEntity, $dtoData) {
                $mock->shouldReceive('save')
                    ->once()
                    ->with(Mockery::on(function ($receivedObject) use ($dtoData) {
                        return $receivedObject == $dtoData;
                    }))
                    ->andReturn($cartEntity);
            }
        );

        $saveProductToCartController = new SaveProductToCartController($mock);

        $mockValidator = Mockery::mock(
            SaveProductToCartRequestValidator::class, 
            function (MockInterface $mock) use ($payload) {
                $mock->shouldReceive('validated')
                    ->once()
                    ->andReturn($payload);
            }
        );

        $transformedUseCaseResult = SaveProductToCartOutputDto::transform(
            $cartEntity,
            $dtoData->userId
        );

        $mockFactory = Mockery::mock(
            ResponseFactory::class,
            function (MockInterface $mock) use ($transformedUseCaseResult){
                $mock->shouldReceive('json')
                ->andReturn(new JsonResponse($transformedUseCaseResult));
            }
        );

        app()->instance(ResponseFactory::class, $mockFactory);

        $result = $saveProductToCartController->execute(
            $mockValidator,
            new SaveProductToCartDto()
        );

        $this->assertInstanceOf(JsonResponse::class, $result);
        $this->assertEquals($result->content(), json_encode($transformedUseCaseResult));
    }

    public function test_it_cannot_save_a_product_in_the_cart_with_a_wrong_uuid_as_payload() 
    {
        $this->expectException(ValidationException::class);

        $payload =  [
            'product_id' => '4002d872-8df7-4f7b-a32f',
            'quantity' => 96,
            'price' => 41,
            'user_id' => '39caf222-d55c-4ea5-91d8-ebac68a58123',
        ];

        $mockValidator = Mockery::mock(
            SaveProductToCartRequestValidator::class, 
            function (MockInterface $mock) use ($payload) {
                $mock->shouldReceive('validated')
                    ->once()
                    ->andReturnUsing(function() use ($payload) {
                        $loader = new ArrayLoader();
                        $translator = new Translator($loader, 'en');
                
                        $cartValidator = new SaveProductToCartRequestValidator();
                        return (new Factory($translator))
                                ->make($payload, $cartValidator->rules())
                                ->validated();
                    });
            }
        );

        $mock = Mockery::mock(
            SaveProductToCartInboundPort::class, 
            function (MockInterface $mock) {
                $mock->shouldReceive('save')
                    ->once();
            }
        );

        $saveProductToCartController = new SaveProductToCartController($mock);
        $saveProductToCartController->execute($mockValidator, new SaveProductToCartDto());
    }

    public function test_it_cannot_save_a_product_in_the_cart_that_does_not_exist() 
    {
        $this->expectException(\Throwable::class);

        $payload =  [
            'product_id' => '4002d872-8df7-4f7b-a32f-ea87ca26a66e',
            'quantity' => 96,
            'price' => 41,
            'user_id' => '39caf222-d55c-4ea5-91d8-ebac68a58123',
        ];

        $mockValidator = Mockery::mock(
            SaveProductToCartRequestValidator::class, 
            function (MockInterface $mock) use ($payload) {
                $mock->shouldReceive('validated')
                    ->once()
                    ->andReturnUsing(function() use ($payload) {
                        $loader = new ArrayLoader();
                        $translator = new Translator($loader, 'en');
                
                        $cartValidator = new SaveProductToCartRequestValidator();
                        return (new Factory($translator))
                                ->make($payload, $cartValidator->rules())
                                ->validated();
                    });
            }
        );

        $mock = Mockery::mock(
            SaveProductToCartInboundPort::class, 
            function (MockInterface $mock) {
                $mock->shouldReceive('save')
                ->once()
                ->andThrows(Throwable::class);
            },
        );

        $saveProductToCartController = new SaveProductToCartController($mock);
        $saveProductToCartController->execute($mockValidator, new SaveProductToCartDto());
    }
}
