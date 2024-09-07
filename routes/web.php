<?php

use Illuminate\Support\Facades\Route;
use QuickBooksOnline\API\DataService\DataService;
use App\Models\Token;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\InvoiceController;


Route::get('/', function () {
    return view('welcome');
});
Route::get('/connect-qbo', function () {
    // Configure the DataService
    $dataService = DataService::Configure([
        'auth_mode' => 'oauth2',
        'ClientID' => config('services.qbo.client_id'),
        'ClientSecret' => config('services.qbo.client_secret'),
        'RedirectURI' => config('services.qbo.redirect_uri'),
        'scope' => 'com.intuit.quickbooks.accounting',
        'baseUrl' => config('services.qbo.sandbox') ? "Development" : "Production",
    ]);

    // Get the OAuth2LoginHelper to perform initial token exchange
    $OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();

    // Get the OAuth 2.0 URL for initial authentication
    $authUrl = $OAuth2LoginHelper->getAuthorizationCodeURL();

    // Redirect the user to the Intuit OAuth 2.0 login page
    return redirect($authUrl);
});

Route::get('/qbo-callback', function () {
    $dataService = DataService::Configure([
        'auth_mode' => 'oauth2',
        'ClientID' => config('services.qbo.client_id'),
        'ClientSecret' => config('services.qbo.client_secret'),
        'RedirectURI' => config('services.qbo.redirect_uri'),
        'scope' => 'com.intuit.quickbooks.accounting',
        'baseUrl' => config('services.qbo.sandbox') ? "Development" : "Production",
    ]);

    if (!$dataService) {
        Log::error('DataService initialization failed');
        return response()->json(['error' => 'DataService initialization failed'], 500);
    }

    // Get the authorization code from the query parameters
    $authCode = request()->query('code');
    if (!$authCode) {
        Log::error('Authorization code not found in the callback');
        return response()->json(['error' => 'Authorization code not found'], 400);
    }
    $realmId = request()->query('realmId');
    // Exchange the authorization code for access and refresh tokens
    $OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();
    $accessToken = $OAuth2LoginHelper->exchangeAuthorizationCodeForToken($authCode, $realmId);

    if ($accessToken) {
        // Store the access token, refresh token, and realm ID in the database

        Token::updateOrCreate(
            ['service' => 'qbo'],
            [
                'access_token' => $accessToken->getAccessToken(),
                'refresh_token' => $accessToken->getRefreshToken(),
                'realm_id' => $realmId,
            ]
        );

        return response()->json(['message' => 'Tokens and Realm ID saved successfully']);
    } else {
        $error = $dataService->getLastError();
        Log::error('Error exchanging authorization code: ' . $error->getResponseBody());
        return response()->json(['error' => 'Failed to exchange authorization code'], 500);
    }
});



// Customer routes
Route::get('/syncCustomers', [CustomerController::class, 'syncCustomers'])->name('customers.syncCustomers');
Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
Route::get('/customers/{id}', [CustomerController::class, 'show'])->name('customers.show');
Route::get('/customers/{id}/edit', [CustomerController::class, 'edit'])->name('customers.edit');
Route::put('/customers/{id}', [CustomerController::class, 'update'])->name('customers.update');


// Invoice routes
Route::get('/syncInvoices', [InvoiceController::class, 'syncInvoices'])->name('invoices.syncInvoices');
Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
Route::get('/invoices/{id}', [InvoiceController::class, 'show'])->name('invoices.show');
Route::get('/invoices/{id}/edit', [InvoiceController::class, 'edit'])->name('invoices.edit');
Route::put('/invoices/{id}', [InvoiceController::class, 'update'])->name('invoices.update');

