<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Customer;
use App\Services\QBOService;
use Illuminate\Support\Facades\Log;

class InvoiceController extends Controller
{
    protected $qboService;

    public function __construct(QBOService $qboService)
    {
        $this->qboService = $qboService;
    }

    public function syncInvoices()
    {
        $dataService = $this->qboService->getDataService();
        if (!$dataService) {
            Log::error('DataService not initialized.');
            return;
        }

        $batchSize = 100;  // Number of records to fetch per batch (can be adjusted based on API limits)
        $startPosition = 1;  // Start position for pagination

        do {
            // Fetch invoices with pagination
            $query = "SELECT * FROM Invoice STARTPOSITION $startPosition MAXRESULTS $batchSize";
            $invoices = $dataService->Query($query);

            // Check if invoices are null or empty, and break if no more records are found
            if (is_null($invoices)) {
                Log::info('No more invoices found.');
                break;
            }

            foreach ($invoices as $invoice) {
                // Find the corresponding customer based on QBO CustomerRef
                $customer = Customer::where('id', $invoice->CustomerRef)->first();  // Assuming QBO customers have 'qbo_id'
                if ($customer) {
                    try {
                        // Sync invoice to the local database
                        Invoice::updateOrCreate(
                            ['invoice_number' => $invoice->DocNumber],
                            [
                                'customer_id' => $customer->id, // Local customer ID
                                'invoice_date' => $invoice->TxnDate,
                                'amount' => $invoice->TotalAmt,
                                'tax' => isset($invoice->SalesTaxTotal) ? $invoice->SalesTaxTotal : null,
                            ]
                        );
                        Log::info('Invoice synced: ' . $invoice->DocNumber);
                    } catch (\Exception $e) {
                        Log::error('Error syncing invoice ' . $invoice->DocNumber . ': ' . $e->getMessage());
                    }
                } else {
                    Log::warning('Customer not found for Invoice: ' . $invoice->DocNumber);
                }
            }

            // Increment start position for the next batch
            $startPosition += $batchSize;

        } while (count($invoices) > 0);  // Continue fetching until no more invoices are returned

        Log::info('Invoice sync completed.');
        return redirect()->route('invoices.index')->with('success', 'Invoices synced successfully!');


    }



    public function index(Request $request)
    {
        $search = $request->input('search');
        $sort = $request->input('sort', 'invoice_date');
        $order = $request->input('order', 'asc');

        $invoices = Invoice::with('customer')
            ->when($search, function ($query, $search) {
                return $query->where('invoice_number', 'like', '%' . $search . '%')
                             ->orWhereHas('customer', function($q) use ($search) {
                                 $q->where('name', 'like', '%' . $search . '%');
                             });
            })
            ->orderBy($sort, $order)
            ->paginate(50)
            ->withQueryString();

        return view('admin.invoices.index', compact('invoices', 'search', 'sort', 'order'));
    }

    // Show invoice details
    public function show($id)
    {
        $invoice = Invoice::with('customer')->findOrFail($id);
        return view('admin.invoices.show', compact('invoice'));
    }

    // Show edit form
    public function edit($id)
    {
        $invoice = Invoice::with('customer')->findOrFail($id);
        return view('admin.invoices.edit', compact('invoice'));
    }

    // Update invoice
    public function update(Request $request, $id)
    {
        $request->validate([
            'invoice_number' => 'required|string|max:255|unique:invoices,invoice_number,' . $id,
            'amount' => 'required|numeric',
            'tax' => 'nullable|numeric',
            'invoice_date' => 'required|date',
        ]);

        $invoice = Invoice::findOrFail($id);
        $invoice->update($request->all());

        return redirect()->route('invoices.show', $invoice->id)->with('success', 'Invoice updated successfully.');
    }
}
