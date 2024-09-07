<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Jobs\CreateQBOCustomerJob;
use App\Jobs\CreateQBOInvoiceJob;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Log;

class QBODataSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // Create 50,000 customers
        for ($i = 0; $i < 48000; $i++) {
            $customerData = [
                "DisplayName" => $faker->name,
                "PrimaryEmailAddr" => [
                    "Address" => $faker->unique()->safeEmail,
                ],
                "PrimaryPhone" => [
                    "FreeFormNumber" => $faker->phoneNumber,
                ],
                "BillAddr" => [
                    "Line1" => $faker->streetAddress,
                    "City" => $faker->city,
                    "Country" => "USA",
                    "PostalCode" => $faker->postcode,
                ],
            ];

            // Dispatch job to create a customer in QBO
            CreateQBOCustomerJob::dispatch($customerData);
        }

        // Create 100,000 invoices
        // for ($i = 0; $i < 100000; $i++) {
        //     $invoiceData = [
        //         'CustomerRef' => [
        //             'value' => $this->getRandomQBOCustomerId(), // Logic to retrieve customer ID
        //         ],
        //         'Line' => [
        //             [
        //                 'Amount' => $faker->randomFloat(2, 100, 10000),
        //                 'DetailType' => 'SalesItemLineDetail',
        //                 'SalesItemLineDetail' => [
        //                     'ItemRef' => [
        //                         'value' => '1',
        //                         'name' => 'Product/Service',
        //                     ],
        //                 ],
        //             ],
        //         ],
        //         'TxnDate' => $faker->date(),
        //         'TotalAmt' => $faker->randomFloat(2, 100, 10000),
        //     ];

        //     // Dispatch job to create an invoice
        //     CreateQBOInvoiceJob::dispatch($invoiceData);
        // }
    }

    // You will also need the getRandomQBOCustomerId() logic here
    protected function getRandomQBOCustomerId()
    {
        // Logic to retrieve a random QBO customer ID
    }
}
