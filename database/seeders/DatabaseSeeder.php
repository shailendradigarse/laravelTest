<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Customer;
use App\Models\Invoice;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    /**
     * Seed the application's database.
     */
    public function run()
    {
        // Create 50,000 customers in batches
        $customers = Customer::factory(50000)->create();

        // Now create 100,000 invoices by looping over customers
        $invoices = [];
        foreach ($customers as $customer) {
            $invoices[] = [
                'invoice_number' => fake()->unique()->numberBetween(100000, 999999),
                'customer_id' => $customer->id,
                'invoice_date' => fake()->date(),
                'amount' => fake()->randomFloat(2, 100, 10000),
                'tax' => fake()->randomFloat(2, 0, 1000),
            ];
            $invoices[] = [
                'invoice_number' => fake()->unique()->numberBetween(100000, 999999),
                'customer_id' => $customer->id,
                'invoice_date' => fake()->date(),
                'amount' => fake()->randomFloat(2, 100, 10000),
                'tax' => fake()->randomFloat(2, 0, 1000),
            ];
        }

        // Insert all invoices in one query, use chunking to avoid memory overload
        foreach (array_chunk($invoices, 1000) as $chunk) {
            Invoice::insert($chunk);
        }
    }
}
