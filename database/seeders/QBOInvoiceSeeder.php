<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Jobs\CreateQBOInvoiceJob;
use App\Services\QBOService;
use Illuminate\Support\Facades\Log;

class QBOInvoiceSeeder extends Seeder
{
    public function run(QBOService $qboService)
    {
        $batchSize = 100;  // Fetch customers in batches of 100
        $startPosition = 1;  // Start position for pagination
        do {
            // Fetch customers in batches with pagination
            $customers = $qboService->getCustomersInBatch($startPosition, $batchSize);

            if (!$customers || count($customers) == 0) {
                break; // No more customers to fetch
            }

            // Dispatch a job to create two invoices for each customer
            foreach ($customers as $customer) {
                $customerId = $customer->Id;  // QBO Customer ID
                CreateQBOInvoiceJob::dispatch($customerId)->onQueue('qbo_invoices');
            }

            $startPosition += $batchSize;  // Increment start position for next batch
        } while (count($customers) > 0);

        Log::info('Customer invoice creation jobs dispatched.');
    }
}
