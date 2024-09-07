<?php

namespace Database\Factories;
use App\Models\Customer;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class InvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'invoice_number' => $this->faker->unique()->numberBetween(100000, 999999),
            'customer_id' => Customer::inRandomOrder()->first()->id,
            'invoice_date' => $this->faker->date(),
            'amount' => $this->faker->randomFloat(2, 100, 10000),
            'tax' => $this->faker->randomFloat(2, 0, 1000),
        ];
    }
}
