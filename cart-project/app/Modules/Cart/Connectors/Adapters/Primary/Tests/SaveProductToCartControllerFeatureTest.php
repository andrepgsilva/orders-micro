<?php

namespace App\Modules\Cart\Connectors\Adapters\Primary\Tests;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;

/**
 * @group feature
 */
class SaveProductToCartControllerFeatureTest extends TestCase
{
    public function test_it_can_save_a_product_that_exists_to_cart(): void
    {
        $createProductOnCatalogRoute = config('external-routes.products_catalog.create');
        $response = Http::post($createProductOnCatalogRoute, [
            "name" => "Random Product",
            "description" => "Random Product Description",
            "quantity" => 1,
            "price" => 1,
        ]);

        $productId = json_decode($response->body())->id;

        $payload = [
            'product_id' => $productId,
            'quantity' => 554645656,
            'price' => 321312,
            'user_id' => '03d28b87-71c7-48b8-bfb1-85d57f0ee732'
        ];

        $response = $this->postJson('/api/v1/cart/add', $payload);
        $responseContent = json_decode($response->content(), true);
        $shouldBeEqualTheseKeys = [
            'id', 'user_id', 'total', 
            'quantity', 'products', 
            'created_at', 'updated_at'
        ];

        $this->assertEquals($shouldBeEqualTheseKeys, array_keys($responseContent));
        $response->assertStatus(201);
    }
}