<?php

namespace App\Jobs;

use App\Services\QBOService;
use QuickBooksOnline\API\Facades\Invoice as QBOInvoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CreateQBOInvoiceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $customerId;  // QBO Customer ID

    public function __construct($customerId)
    {
        $this->customerId = $customerId;
    }

    public function handle(QBOService $qboService)
    {
        $dataService = $qboService->getDataService();

        // Create two invoices for the customer
        for ($i = 0; $i < 2; $i++) {
            try {
                // Create invoice data
                $invoiceData = [
                    "CustomerRef" => [
                        "value" => $this->customerId
                    ],
                    "Line" => [
                        [
                            "Amount" => rand(100, 1000),  // Random invoice amount between $100 and $1000
                            "DetailType" => "SalesItemLineDetail",
                            "SalesItemLineDetail" => [
                                "ItemRef" => [
                                    "value" => "1",  // Assuming this is a valid item ID in QBO
                                    "name" => "Test Item"
                                ],
                            ],
                        ]
                    ],
                    "TxnDate" => now()->toDateString(), // Invoice date
                    "TotalAmt" => rand(100, 1000),  // Random total amount for the invoice
                ];

                // Add the invoice to QuickBooks
                $qboInvoice = QBOInvoice::create($invoiceData);
                $resultingInvoiceObj = $dataService->Add($qboInvoice);

                if ($error = $dataService->getLastError()) {
                    Log::error('Error creating invoice: ' . $error->getResponseBody());
                } else {
                    Log::info('Invoice created successfully: ' . $resultingInvoiceObj->Id);
                }
            } catch (\Exception $e) {
                Log::error('Error processing invoice for customer ' . $this->customerId . ': ' . $e->getMessage());
            }
        }
    }
}
