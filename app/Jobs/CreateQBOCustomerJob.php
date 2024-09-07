<?php

namespace App\Jobs;

use App\Services\QBOService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Log;
use QuickBooksOnline\API\Facades\Customer as QBOCustomer;

class CreateQBOCustomerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $customerData;

    public function __construct($customerData)
    {
        Log::info("Starting job to create customerData dd: ");
        $this->customerData = $customerData;
    }

    public function handle(QBOService $qboService)
    {
        Log::info("Starting job to create customer: ");
        $dataService = $qboService->getDataService();

        // Create the QBO Customer object
        $qboCustomer = QBOCustomer::create($this->customerData);

        // Add the customer to QuickBooks
        $resultingCustomerObj = $dataService->Add($qboCustomer);

        if ($error = $dataService->getLastError()) {
            Log::error('Error creating customer: ' . $error->getResponseBody());
        } else {
            Log::info('Customer created successfully: ' . $resultingCustomerObj->Id);
        }
    }
}
