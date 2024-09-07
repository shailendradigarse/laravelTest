<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Services\QBOService;
use QuickBooksOnline\API\Facades\Customer as QBOCustomer;
use Illuminate\Support\Facades\Log;

class CustomerController extends Controller
{
    protected $qboService;

    public function __construct(QBOService $qboService)
    {
        $this->qboService = $qboService;
    }

    public function pushCustomersToQBO()
    {
        $dataService = $this->qboService->getDataService();

        // Retrieve the customers you want to push
        $customers = Customer::limit(50000)->get();

        foreach ($customers as $customer) {
            $qboCustomer = QBOCustomer::create([
                "DisplayName" => $customer->name,
                "PrimaryEmailAddr" => [
                    "Address" => $customer->email,
                ],
                "PrimaryPhone" => [
                    "FreeFormNumber" => $customer->phone,
                ],
            ]);

            // Add the customer to QuickBooks
            $resultingCustomerObj = $dataService->Add($qboCustomer);

            if (!$resultingCustomerObj) {
                $error = $dataService->getLastError();
                Log::error("Error adding customer: " . $error->getResponseBody());
            } else {
                Log::info("Customer added: " . $resultingCustomerObj->Id);
            }
        }

        return response()->json(['message' => 'Customers pushed to QBO successfully']);
    }
}
