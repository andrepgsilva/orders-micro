<?php

namespace App\Modules\Cart\Application\Usecases\Tests;

use PHPUnit\Framework\TestCase;
use App\Modules\Cart\Domain\Entities\CartEntity;
use App\Modules\Cart\Application\Usecases\GetCartByUserIdUseCase;
use App\Modules\Cart\Connectors\Ports\Outbound\GetCartByUserIdOutboundPort;
use App\Modules\Cart\Connectors\Adapters\Secondary\CartInMemoryRepository;

class GetCartByUserIdUseCaseTest extends TestCase
{
    private GetCartByUserIdOutboundPort $cartRepository;
    private GetCartByUserIdUseCase $getCartByUserIdUseCase;

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
        $this->getCartByUserIdUseCase = new GetCartByUserIdUseCase($this->cartRepository);
    }

    public function test_it_can_expect_a_service() 
    {
        $this->assertInstanceOf(GetCartByUserIdOutboundPort::class, $this->cartRepository);
        $this->assertInstanceOf(GetCartByUserIdUseCase::class, $this->getCartByUserIdUseCase);
    }

    public function test_it_can_get_a_cart_by_user_id()
    {
        $cart = $this->getCartByUserIdUseCase->get(
            '9d725ed7-0b39-46c7-a667-22c50f94a71b'
        );

        $this->assertInstanceOf(CartEntity::class, $cart);
        $this->assertEquals('8d725ed7-0b39-46c7-a667-22c50f94a78b', $cart->id->value);
    }
}