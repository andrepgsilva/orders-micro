<?php

namespace App\Modules\Cart\Connectors\Adapters\Primary\Tests;

use Mockery;
use Mockery\MockInterface;
use Illuminate\Http\Request;
use GuzzleHttp\Promise\Create;
use PHPUnit\Framework\TestCase;
use Illuminate\Http\JsonResponse;
use App\Modules\Cart\Domain\Entities\CartEntity;
use Illuminate\Contracts\Routing\ResponseFactory;
use App\Modules\Cart\Application\Dtos\GetCartOutputDto;
use App\Modules\Cart\Domain\Entities\Factories\CartFactory;
use App\Modules\Cart\Application\Usecases\GetCartByUserIdUseCase;
use App\Modules\Cart\Connectors\Adapters\Primary\GetCartController;
use App\Modules\Cart\Connectors\Ports\Outbound\GetProductByIdOutputPort;
use App\Modules\Cart\Connectors\Adapters\Secondary\CartInMemoryRepository;

class GetCartControllerTest extends TestCase
{
    public function test_it_can_get_a_cart() 
    {
        $cartEntity = (new CartFactory())->create([''], 22, 231);

        $getCartUseCase = Mockery::mock(
            GetCartByUserIdUseCase::class,
            function (MockInterface $mock) use ($cartEntity){
                $mock->shouldReceive('get')
                ->andReturn($cartEntity);
            }
        );
        $getCartController = new GetCartController($getCartUseCase);

        $transformedUseCaseResult = GetCartOutputDto::transform(
            $cartEntity,
            'a8c0fd91-d014-4f79-8b4e-7a72ce561d06',
        );

        $mockFactory = Mockery::mock(
            ResponseFactory::class,
            function (MockInterface $mock) use ($transformedUseCaseResult){
                $mock->shouldReceive('json')
                ->andReturn(new JsonResponse($transformedUseCaseResult));
            }
        );
        app()->instance(ResponseFactory::class, $mockFactory);

        $mockRequest = Mockery::mock(
            Request::class,
            function (MockInterface $mock) {
                $mock->shouldReceive('validate')
                ->once()
                ->andReturn(['user_id' => '3bfd8ee4-dcff-4633-87ab-b8cce9b1bf04']);
            }
        );
        $response = $getCartController->execute($mockRequest, new GetCartOutputDto());
        
        $this->assertInstanceOf(JsonResponse::class, $response); 
        $this->assertEquals(200, $response->status());
    }
}
