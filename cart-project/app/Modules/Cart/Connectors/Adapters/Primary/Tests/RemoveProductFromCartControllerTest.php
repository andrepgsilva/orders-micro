<?php

namespace App\Modules\Cart\Connectors\Adapters\Primary\Tests;

use Mockery;
use Mockery\MockInterface;
use Illuminate\Http\Response;
use PHPUnit\Framework\TestCase;
use Illuminate\Contracts\Routing\ResponseFactory;
use App\Modules\Cart\Application\Dtos\RemoveProductFromCartDto;
use App\Modules\Cart\Connectors\Adapters\Secondary\CartInMemoryRepository;
use App\Modules\Cart\Application\Usecases\RemoveProductByIdFromCartUseCase;
use App\Modules\Cart\Connectors\Adapters\Primary\RemoveProductFromCartController;
use App\Modules\Cart\Connectors\Adapters\Primary\Validators\RemoveProductFromCartRequestValidator;

class RemoveProductFromCartControllerTest extends TestCase
{
    public function test_it_can_remove_a_product_from_cart() 
    {
        $removeProductFromCartUseCase =  new RemoveProductByIdFromCartUseCase(new CartInMemoryRepository()); 
        $removeProductFromCartControler = new RemoveProductFromCartController($removeProductFromCartUseCase);
        
        $payload = [
            'user_id' => '59e44b4e-7ed8-43ab-b2f9-f8bf16c81744',
            'product_id' => '6f14fa9c-0c53-41e7-a57b-33e0445b1bac'
        ];

        $mockValidator = Mockery::mock(
            RemoveProductFromCartRequestValidator::class, 
            function (MockInterface $mock) use ($payload) {
                $mock->shouldReceive('all')
                ->once()
                ->andReturn($payload);
            }
        );
        
        app()->instance(
            ResponseFactory::class,
            Mockery::mock(ResponseFactory::class, function (MockInterface $mock) {
                $mock->shouldReceive('make')
                    ->once()
                    ->andReturn(new Response('', 201));
            })
        );

        $response = $removeProductFromCartControler->execute(
            $mockValidator, 
            new RemoveProductFromCartDto()
        );

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(201, $response->status());
    }
}
