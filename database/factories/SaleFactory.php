<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sale>
 */
class SaleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'total' => fake()->numberBetween(4200, 45201),
            'customer_id' => Customer::factory()->create()->id,
            'user_id' => User::factory()->create()->id,
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function(Sale $sale): void {
            $installments_count = fake()->randomDigit();
            $installments = [];
            for ($i=0; $i < $installments_count; $i++) { 
                $installments[] = [
                    'due_date' => fake()->dateTimeBetween('-1 year')->format('Y-m-d'),
                    'value' => fake()->numberBetween(500, 700),
                ];
            }
            if ($installments_count > 0) $sale->installments()->createMany($installments);

            $products_count = fake()->numberBetween(2, 9);
            $products = Product::factory($products_count)->create();
            foreach ($products as $product) {
                $sale->products()->attach(
                    $product->id,
                    [
                        'value' => $product->value,
                        'quantity' => fake()->randomDigitNotZero(),
                    ],
                );
            }
            
        });
    }
}
