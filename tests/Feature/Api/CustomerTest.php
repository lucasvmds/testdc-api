<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class CustomerTest extends TestCase
{
    public function test_can_fetch_all_customers(): void
    {
        $customer = Customer::factory()->create();
        $response = $this
                        ->withAuth()
                        ->get('/api/customers');
        $response
            ->assertStatus(200)
            ->assertJson(fn(AssertableJson $json): AssertableJson =>
                $json
                    ->hasAll(['meta', 'data', 'links'])
                    ->has('data', 1, fn(AssertableJson $item): AssertableJson =>
                        $item
                            ->where('name', $customer->name)
                            ->where('phone', $customer->phone)
                            ->where('address', $customer->address)
                            ->etc()
                    )
            );
    }

    public function test_can_create_customer(): void
    {
        $customer_data = [
            'name' => fake()->name(),
            'phone' => (int) fake()->numerify('##########'),
            'address' => fake()->address(),
        ];
        $response = $this
                        ->withAuth()
                        ->postJson('/api/customers', $customer_data);
        $response
            ->assertStatus(201)
            ->assertJson(fn(AssertableJson $json): AssertableJson =>
                $json
                    ->has('data')
                    ->first(fn(AssertableJson $item): AssertableJson =>
                        $item
                            ->where('name', $customer_data['name'])
                            ->where('phone', $customer_data['phone'])
                            ->where('address', $customer_data['address'])
                            ->etc()
                    )
            );
    }

    public function test_can_fetch_customer(): void
    {
        $customer = Customer::factory()->create();
        $response = $this
                        ->withAuth()
                        ->get("/api/customers/$customer->id");
        $response
            ->assertStatus(200)
            ->assertJson(fn(AssertableJson $json): AssertableJson =>
                $json
                    ->has('data')
                    ->first(fn(AssertableJson $item): AssertableJson =>
                        $item
                            ->where('name', $customer->name)
                            ->where('phone', $customer->phone)
                            ->where('address', $customer->address)
                            ->etc()
                    )
            );
    }

    public function test_can_update_customer(): void
    {
        $customer = Customer::factory()->create();
        $customer->name = fake()->name();
        $customer->phone = (int) fake()->numerify('#######');
        $customer->address = fake()->address();
        $response = $this
                        ->withAuth()
                        ->patchJson("/api/customers/$customer->id", [
                            'name' => $customer->name,
                            'phone' => $customer->phone,
                            'address' => $customer->address,
                        ]);
        $response
            ->assertStatus(200)
            ->assertJson(fn(AssertableJson $json): AssertableJson =>
                $json
                    ->has('data')
                    ->first(fn(AssertableJson $item): AssertableJson =>
                        $item
                            ->where('name', $customer->name)
                            ->where('phone', $customer->phone)
                            ->where('address', $customer->address)
                            ->etc()
                    )
            );
    }

    public function test_can_delete_customer(): void
    {
        $customer = Customer::factory()->create();
        $response = $this
                        ->withAuth()
                        ->delete("/api/customers/$customer->id");
        $response->assertStatus(204);
        $this->assertModelMissing($customer);
    }
}
