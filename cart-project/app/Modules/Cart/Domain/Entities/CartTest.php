<?php

namespace App\Modules\Cart\Domain\Entities;

use PHPUnit\Framework\TestCase;
use App\Modules\Cart\Domain\Entities\CartEntity;
use App\Modules\Cart\Domain\Entities\Helpers\Id;

class CartTest extends TestCase
{
    public function test_it_can_create_a_cart(): void
    {
        $cart = new CartEntity([''], 10, 20);

        $this->assertInstanceOf(CartEntity::class, $cart);
        $this->assertInstanceOf(Id::class, $cart->id);
    }

    public function test_it_can_create_a_cart_using_an_uuid(): void
    {
        $cart = new CartEntity([''], 10, 20, '8e6859f9-489c-46c5-b875-16e640a1c34b');

        $this->assertInstanceOf(CartEntity::class, $cart);
        $this->assertInstanceOf(Id::class, $cart->id);
        $this->assertTrue($cart->id->value === '8e6859f9-489c-46c5-b875-16e640a1c34b');
    }

    public function test_it_cannot_create_a_cart_using_an_wrong_uuid(): void
    {
        $this->expectException(\Exception::class);

        new CartEntity([''], 10, 20, '8e6859f9-489c-46c5-b875-16e640a1c34');
    }

    public function test_it_cannot_create_a_cart_using_a_quantity_less_than_one(): void
    {
        $this->expectException(\Exception::class);
        
        new CartEntity([''], 0, 20, '8e6859f9-489c-46c5-b875-16e640a1c34b');
    }

    public function test_it_cannot_create_a_cart_using_a_total_less_than_zero(): void
    {
        $this->expectException(\Exception::class);
        
        new CartEntity([''], 10, -1, '8e6859f9-489c-46c5-b875-16e640a1c34b');
    }

    public function test_it_cannot_create_a_cart_without_products(): void
    {
        $this->expectException(\Exception::class);
        
        new CartEntity([], 10, -1, '8e6859f9-489c-46c5-b875-16e640a1c34b');
    }
}