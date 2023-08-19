<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class ProductTest extends TestCase
{
    public function test_can_fetch_all_products(): void
    {
        $product = Product::factory()->create();
        $response = $this
                        ->withAuth()
                        ->get('/api/products');
        $response
            ->assertStatus(200)
            ->assertJson(fn(AssertableJson $json): AssertableJson =>
                $json
                    ->hasAll(['meta', 'data', 'links'])
                    ->has('data', 1, fn(AssertableJson $item): AssertableJson =>
                        $item
                            ->where('name', $product->name)
                            ->where('value', $product->value)
                            ->etc()
                    )
            );
    }

    public function test_can_create_product(): void
    {
        $product_data = [
            'name' => fake()->name(),
            'value' => fake()->randomFloat(2, 500, 600),
        ];
        $response = $this
                        ->withAuth()
                        ->postJson('/api/products', $product_data);
        $response
            ->assertStatus(201)
            ->assertJson(fn(AssertableJson $json): AssertableJson =>
                $json
                    ->has('data')
                    ->first(fn(AssertableJson $item): AssertableJson =>
                        $item
                            ->where('name', $product_data['name'])
                            ->where('value', $product_data['value'])
                            ->etc()
                    )
            );
    }

    public function test_can_fetch_product(): void
    {
        $product = Product::factory()->create();
        $response = $this
                        ->withAuth()
                        ->get("/api/products/$product->id");
        $response
            ->assertStatus(200)
            ->assertJson(fn(AssertableJson $json): AssertableJson =>
                $json
                    ->has('data')
                    ->first(fn(AssertableJson $item): AssertableJson =>
                        $item
                            ->where('name', $product->name)
                            ->where('value', $product->value)
                            ->etc()
                    )
            );
    }

    public function test_can_update_product(): void
    {
        $product = Product::factory()->create();
        $product->name = fake()->name();
        $product->value = fake()->randomFloat(2, 30.00, 100.00);
        $response = $this
                        ->withAuth()
                        ->patchJson("/api/products/$product->id", [
                            'name' => $product->name,
                            'value' => $product->value,
                        ]);
        $response
            ->assertStatus(200)
            ->assertJson(fn(AssertableJson $json): AssertableJson =>
                $json
                    ->has('data')
                    ->first(fn(AssertableJson $item): AssertableJson =>
                        $item
                            ->where('name', $product->name)
                            ->where('value', $product->value)
                            ->etc()
                    )
            );
    }

    public function test_can_delete_product(): void
    {
        $product = Product::factory()->create();
        $response = $this
                        ->withAuth()
                        ->delete("/api/products/$product->id");
        $response->assertStatus(204);
        $this->assertModelMissing($product);
    }
}
