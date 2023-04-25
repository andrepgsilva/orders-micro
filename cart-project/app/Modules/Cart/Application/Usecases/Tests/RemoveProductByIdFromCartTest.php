<?php

namespace App\Modules\Cart\Application\Usecases\Tests;

use PHPUnit\Framework\TestCase;
use App\Modules\Cart\Application\Dtos\RemoveProductFromCartDto;
use App\Modules\Cart\Connectors\Adapters\Secondary\CartInMemoryRepository;
use App\Modules\Cart\Application\Usecases\RemoveProductByIdFromCartUseCase;
use App\Modules\Cart\Connectors\Ports\Outbound\RemoveProductByIdFromCartOutboundPort;

class RemoveProductByIdFromCartTest extends TestCase
{
    private RemoveProductByIdFromCartOutboundPort $cartRepository;
    private RemoveProductByIdFromCartUseCase $removeProductFromCartUseCase;

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
            ],
        ]);
        
        $this->removeProductFromCartUseCase = new RemoveProductByIdFromCartUseCase($this->cartRepository);
    }

    public function test_it_can_expect_a_service()
    {
        $this->assertInstanceOf(RemoveProductByIdFromCartOutboundPort::class, $this->cartRepository);
        $this->assertInstanceOf(RemoveProductByIdFromCartUseCase::class, $this->removeProductFromCartUseCase);
    }

    public function test_it_can_remove_a_product_from_cart()
    {
        $removeProductFromCartDto = new RemoveProductFromCartDto();
        $removeProductFromCartDto->userId = '9d725ed7-0b39-46c7-a667-22c50f94a71b';
        $removeProductFromCartDto->productId = '2d7459f1-4b19-72c7-a667-22c50f94a71b';

        $result = $this->removeProductFromCartUseCase->remove($removeProductFromCartDto);

        $this->assertTrue($result);
    }
}