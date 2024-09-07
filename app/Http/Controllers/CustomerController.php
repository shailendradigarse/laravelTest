<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Services\QBOService;
use QuickBooksOnline\API\Facades\Customer as QBOCustomer;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CustomerController extends Controller
{
    protected $qboService;

    public function __construct(QBOService $qboService)
    {
        $this->qboService = $qboService;
    }

    public function syncCustomers()
    {
        $dataService = $this->qboService->getDataService();
        $batchSize = 100;  // QBO API returns a maximum of 100 customers per request
        $startPosition = 1;  // Initial position for pagination

        do {
            // Query customers with pagination
            $query = "SELECT * FROM Customer STARTPOSITION $startPosition MAXRESULTS $batchSize";
            $customers = $dataService->Query($query);
            // dd($customers);
            if ($dataService->getLastError()) {
                Log::error('Error fetching customers: ' . $dataService->getLastError()->getResponseBody());
                return;
            }
            if (is_null($customers)) {
                Log::info('No more customers found.');
                break;  // Exit the loop if no more customers are returned
            }

            // Sync customers to the database
            foreach ($customers as $customer) {

                try {

                    $createTime = $customer->MetaData->CreateTime;
                    $formattedJoiningDate = Carbon::parse($createTime)->toDateTimeString();

                    Customer::updateOrCreate(
                        ['email' => $customer->PrimaryEmailAddr->Address],
                        [
                            'name' => $customer->DisplayName,
                            'phone' => $customer->PrimaryPhone ? $customer->PrimaryPhone->FreeFormNumber : null,
                            // 'joining_date' => $customer->MetaData->CreateTime,
                            'joining_date' => $formattedJoiningDate,
                        ]
                    );
                    Log::info('Customer synced: ' . $customer->DisplayName);
                } catch (\Exception $e) {
                    Log::error('Error syncing customer ' . $customer->DisplayName . ': ' . $e->getMessage());
                }
            }

            // Increment start position for the next batch
            $startPosition += $batchSize;

        } while (count($customers) > 0);  // Continue fetching until no more customers are returned

        Log::info('Customer sync completed.');
        return redirect()->route('customers.index')->with('success', 'customers synced successfully!');

    }


    public function index(Request $request)
    {
        DB::enableQueryLog();

        // Start the timer
        $startTime = microtime(true);
        // Fetch the search and sort inputs
        $search = $request->input('search');
        $sort = $request->input('sort', 'name'); // default sorting by name
        $order = $request->input('order', 'asc'); // default ascending order

        // Query the customers with search, sort, and pagination
        $customers = Customer::query()
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', '%' . $search . '%')
                             ->orWhere('email', 'like', '%' . $search . '%');
            })
            ->orderBy($sort, $order)
            ->paginate(50)
            ->withQueryString(); // keeps query string intact during pagination

        $endTime = microtime(true);

        // Calculate the execution time in milliseconds
        $executionTime = ($endTime - $startTime) * 1000;

        // Get the executed query log (optional, if you want to see the raw queries)
        $queries = DB::getQueryLog();
        return view('admin.customers.index', compact('customers', 'search', 'sort', 'order', 'executionTime', 'queries'));
    }
    public function show($id)
    {
        $customer = Customer::findOrFail($id);
        return view('admin.customers.show', compact('customer'));
    }

    // Show edit form
    public function edit($id)
    {
        $customer = Customer::findOrFail($id);
        return view('admin.customers.edit', compact('customer'));
    }

    // Handle update request
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email,'.$id,
            'phone' => 'nullable|string|max:15',
            'joining_date' => 'required|date',
        ]);

        $customer = Customer::findOrFail($id);
        $customer->update($request->all());

        return redirect()->route('customers.show', $customer->id)->with('success', 'Customer updated successfully.');
    }
}
