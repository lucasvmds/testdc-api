<?php

namespace Database\Seeders\Develop;

use App\Models\Customer;
use App\Models\Installment;
use App\Models\Product;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Sale::factory(30)
                    ->for(Customer::factory())
                    ->for(User::factory())
                    ->hasAttached(Product::factory(5), [
                        'value' => fake()->randomFloat(2, 200, 3000),
                        'quantity' => fake()->randomDigitNotZero(),
                    ])
                    ->has(Installment::factory(2))
                    ->create();
    }
}
