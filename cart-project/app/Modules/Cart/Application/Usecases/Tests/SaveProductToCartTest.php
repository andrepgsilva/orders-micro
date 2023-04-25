<?php

namespace App\Modules\Cart\Application\Usecases\Tests;

use Mockery;
use stdClass;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use App\Modules\Cart\Domain\Entities\CartEntity;
use App\Modules\Cart\Application\Dtos\ProductOutputDto;
use App\Modules\Cart\Application\Dtos\SaveProductToCartDto;
use App\Modules\Cart\Application\Usecases\SaveProductToCartUseCase;
use App\Modules\Cart\Connectors\Ports\Outbound\GetProductByIdOutputPort;
use App\Modules\Cart\Connectors\Adapters\Secondary\CartInMemoryRepository;
use App\Modules\Cart\Connectors\Ports\Outbound\SaveProductToCartOutboundPort;

class SaveProductToCartTest extends TestCase
{
    private SaveProductToCartOutboundPort $cartRepository;
    private SaveProductToCartUseCase $saveProductToCartUseCase;

    public function setUp(): void
    {
        parent::setUp();

        $this->cartRepository = new CartInMemoryRepository([
            [
                'id' => '8d725ed7-0b39-46c7-a667-22c50f94a78b',
                'user_id' => '9d725ed7-0b39-46c7-a667-22c50f94a71b',
                'total' => 1321,
                'quantity' => 1,
                'products' => [
                    [
                        'id' => '2d7459f1-4b19-72c7-a667-22c50f94a71b',
                        'price' => 1321,
                        'quantity' => 1
                    ]
                ],
                'createdAt' => '2095-03-21 21:12:30',
                'updatedAt' => '2096-05-25 05:22:40',
            ]
        ]);

        $productOutputDto = new ProductOutputDto();
        $productOutputDto->id = '2d7459f1-4b19-72c7-a667-22c50f94a71b';
        $productOutputDto->name = 'ok';
        $productOutputDto->description = 'great';
        $productOutputDto->quantity = 1321;
        $productOutputDto->price = 1;
        $productOutputDto->createdAt = new \Datetime('2095-03-21 21:12:30');
        $productOutputDto->updatedAt = new \Datetime('2096-05-25 05:22:40');

        $mockGetProductByIdAdapter = Mockery::mock(
            GetProductByIdOutputPort::class,
            function (MockInterface $mock) use ($productOutputDto) {
                $mock->shouldReceive('execute')
                ->once()
                ->andReturn($productOutputDto);
            }
        );
        
        $this->saveProductToCartUseCase = new SaveProductToCartUseCase(
            $this->cartRepository,
            $mockGetProductByIdAdapter
        );
    }

    private function clearAllCartsBeforeTest()
    {
        $productOutputDto = new ProductOutputDto();
        $productOutputDto->id = '2d7459f1-4b19-72c7-a667-22c50f94a71b';
        $productOutputDto->name = 'ok';
        $productOutputDto->description = 'great';
        $productOutputDto->quantity = 1321;
        $productOutputDto->price = 1;
        $productOutputDto->createdAt = new \Datetime('2095-03-21 21:12:30');
        $productOutputDto->updatedAt = new \Datetime('2096-05-25 05:22:40');

        $mockGetProductByIdAdapter = Mockery::mock(
            GetProductByIdOutputPort::class,
            function (MockInterface $mock) use ($productOutputDto) {
                $mock->shouldReceive('execute')
                ->once()
                ->andReturn($productOutputDto);
            }
        );
        
        $this->cartRepository = new CartInMemoryRepository();
        $this->saveProductToCartUseCase = new SaveProductToCartUseCase(
            $this->cartRepository,
            $mockGetProductByIdAdapter
        );
    }

    public function test_it_can_add_a_product_to_cart()
    {
        $data = new SaveProductToCartDto();
        $data->userId = '9d725ed7-0b39-46c7-a667-22c50f94a71b';
        $data->productId = '1aa52e33-30e5-474d-b181-f333a74ca9ce';
        $data->price = 521;
        $data->quantity = 2;

        $cart = $this->saveProductToCartUseCase->save($data);

        $this->assertInstanceOf(CartEntity::class, $cart);
        $this->assertEquals($cart->products[1], [
            'id' => $data->productId,
            'quantity' => $data->quantity,
            'price' => $data->price
        ]);

        $this->assertEquals('8d725ed7-0b39-46c7-a667-22c50f94a78b', $cart->id->value);
    }

    public function test_it_can_add_a_product_when_it_already_exists()
    {
        $data = new SaveProductToCartDto();
        $data->userId = '9d725ed7-0b39-46c7-a667-22c50f94a71b';
        $data->productId = '2d7459f1-4b19-72c7-a667-22c50f94a71b';
        $data->price = 1321;
        $data->quantity = 4;

        $cart = $this->saveProductToCartUseCase->save($data);

        $totalQuantityBefore = $cart->products[0]['quantity'] - $data->quantity;

        $this->assertInstanceOf(CartEntity::class, $cart);
        $this->assertEquals($cart->products[0], [
            'id' => $data->productId,
            'quantity' => $data->quantity + $totalQuantityBefore,
            'price' => $data->price
        ]);
        $this->assertEquals('8d725ed7-0b39-46c7-a667-22c50f94a78b', $cart->id->value);
    }

    public function test_it_can_add_a_product_to_an_empty_cart()
    {
        $this->clearAllCartsBeforeTest();
        
        $data = new SaveProductToCartDto();
        $data->userId = '9d725ed7-0b39-46c7-a667-22c50f94a71b';
        $data->productId = '2d7459f1-4b19-72c7-a667-22c50f94a71b';
        $data->price = 1321;
        $data->quantity = 4;
        
        $cart = $this->saveProductToCartUseCase->save($data);

        $this->assertInstanceOf(CartEntity::class, $cart);
        $this->assertEquals($cart->total, $data->price * $data->quantity);
        $this->assertEquals($cart->products[0], [
            'id' => $data->productId,
            'quantity' => $data->quantity,
            'price' => $data->price
        ]);
    }
}
