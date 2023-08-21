<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Models\Customer;
use App\Models\Installment;
use App\Models\Product;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class SaleTest extends TestCase
{
    public function test_can_fetch_all_sales(): void
    {
        $sale = Sale::factory()
                            ->for(Customer::factory())
                            ->for(User::factory())
                            ->hasAttached(Product::factory(), [
                                'value' => fake()->randomFloat(2, 300, 5200),
                                'quantity' => fake()->randomDigitNotZero(),
                            ])
                            ->has(Installment::factory())
                            ->create();
        $products_count = $sale->products()->count();
        $installments_count = $sale->installments()->count();
        $response = $this
                        ->withAuth()
                        ->get('/api/sales');
        $response
            ->assertStatus(200)
            ->assertJson(fn(AssertableJson $json): AssertableJson =>
                $json
                    ->hasAll(['meta', 'data', 'links'])
                    ->has('data', 1, fn(AssertableJson $item): AssertableJson =>
                        $item
                            ->where('customer', $sale->customer->name)
                            ->where('user', $sale->user->name)
                            ->where('total', $sale->total)
                            ->where('installments_count', $installments_count)
                            ->where('products_count', $products_count)
                            ->etc()
                    )
            );
    }

    public function test_can_create_sale(): void
    {
        $product = Product::factory()->create();
        $customer = Customer::factory()->create();
        $sale_data = [
            'total' => fake()->randomFloat(2, 3000, 5000),
            'customer_id' => $customer->id,
            'products' => [
                [
                    'id' => $product->id,
                    'value' => $product->value,
                    'quantity' => fake()->randomDigitNotZero(),
                ],
            ],
            'installments' => [
                [
                    'due_date' => fake()->dateTimeBetween('-2 weeks')->format('Y-m-d'),
                    'value' => fake()->randomFloat(2, 300, 500),
                ],
            ],
        ];
        
        $response = $this
                        ->withAuth()
                        ->postJson('/api/sales', $sale_data);
        $response
            ->assertStatus(201)
            ->assertJson(fn(AssertableJson $json): AssertableJson =>
                $json
                    ->has('data')
                    ->first(fn(AssertableJson $item): AssertableJson =>
                        $item
                            ->where('total', $sale_data['total'])
                            ->where('customer_id', $sale_data['customer_id'])
                            ->has('products', 1, fn(AssertableJson $item): AssertableJson =>
                                $item
                                    ->where('id', $sale_data['products'][0]['id'])
                                    ->where('value', $sale_data['products'][0]['value'])
                                    ->where('quantity', $sale_data['products'][0]['quantity'])
                                    ->etc()
                            )
                            ->has('installments', 1, fn(AssertableJson $item): AssertableJson =>
                                $item
                                    ->where('value', $sale_data['installments'][0]['value'])
                                    ->where('due_date', $sale_data['installments'][0]['due_date'])
                                    ->etc()
                            )
                            ->etc()
                    )
            );
    }

    public function test_can_fetch_sale(): void
    {
        $sale = Sale::factory()
                            ->for(Customer::factory())
                            ->for(User::factory())
                            ->hasAttached(Product::factory(), [
                                'value' => fake()->randomFloat(2, 300, 5200),
                                'quantity' => fake()->randomDigitNotZero(),
                            ])
                            ->has(Installment::factory())
                            ->create();
        $product = $sale->products()->first();
        $installment = $sale->installments()->first();
        $response = $this
                        ->withAuth()
                        ->get("/api/sales/$sale->id");
        $response
            ->assertStatus(200)
            ->assertJson(fn(AssertableJson $json): AssertableJson =>
                $json
                    ->has('data')
                    ->first(fn(AssertableJson $item): AssertableJson =>
                        $item
                            ->where('id', $sale->id)
                            ->where('total', $sale->total)
                            ->where('customer_id', $sale->customer_id)
                            ->where('user_id', $sale->user_id)
                            ->has('products', 1, fn(AssertableJson $item): AssertableJson =>
                                $item
                                    ->where('id', $product->id)
                                    ->where('value', $product->pivot->value)
                                    ->where('quantity', $product->pivot->quantity)
                                    ->etc()
                            )
                            ->has('installments', 1, fn(AssertableJson $item): AssertableJson =>
                                $item
                                    ->where('id', $installment->id)
                                    ->where('value', $installment->value)
                                    ->where('due_date', $installment->due_date)
                                    ->etc()
                            )
                            ->etc()
                    )
            );
    }

    public function test_can_delete_sale(): void
    {
        $sale = Sale::factory()
                            ->for(Customer::factory())
                            ->for(User::factory())
                            ->hasAttached(Product::factory(), [
                                'value' => fake()->randomFloat(2, 300, 5200),
                                'quantity' => fake()->randomDigitNotZero(),
                            ])
                            ->has(Installment::factory())
                            ->create();
        $product = $sale->products()->first();
        $installment = $sale->installments()->first();
        $response = $this
                        ->withAuth()
                        ->delete("/api/sales/$sale->id");
        $response->assertStatus(204);
        $this->assertModelMissing($sale);
        $this->assertModelExists($product);
        $this->assertModelExists($sale->customer);
        $this->assertModelMissing($installment);
    }
}
