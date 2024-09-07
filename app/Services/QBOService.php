<?php

namespace App\Services;

use QuickBooksOnline\API\DataService\DataService;
use Illuminate\Support\Facades\Log;
use App\Models\Token;

class QBOService
{
    protected $dataService;

    public function __construct()
    {
        // Fetch token from the database
        $token = Token::where('service', 'qbo')->first();

        // Ensure token exists
        if (!$token) {
            Log::error('No token found in the database.');
            return;
        }

        // Configure the DataService and assign it to $this->dataService
        $this->dataService = DataService::Configure([
            'auth_mode' => 'oauth2',
            'ClientID' => config('services.qbo.client_id'),
            'ClientSecret' => config('services.qbo.client_secret'),
            'RedirectURI' => config('services.qbo.redirect_uri'),
            'baseUrl' => config('services.qbo.sandbox') ? "Development" : "Production",
            'accessTokenKey' => $token->access_token,
            'refreshTokenKey' => $token->refresh_token,
            'QBORealmID' => $token->realm_id, // Retrieve and use the stored Realm ID
        ]);
        Log::error('Refresh access token if needed.');
        // Refresh access token if needed
        $this->refreshAccessTokenIfNecessary($token);
    }

    public function refreshAccessTokenIfNecessary($token)
    {
        if (!$this->dataService) {
            Log::error('DataService is not initialized.');
            return;
        }

        // Get the OAuth2LoginHelper from the DataService
        $OAuth2LoginHelper = $this->dataService->getOAuth2LoginHelper();

        // Attempt to refresh the access token using the refresh token
        $newAccessToken = $OAuth2LoginHelper->refreshAccessTokenWithRefreshToken($token->refresh_token);

        if ($newAccessToken) {
            // Update the token in the database
            $token->update([
                'access_token' => $newAccessToken->getAccessToken(),
                'refresh_token' => $newAccessToken->getRefreshToken(),
            ]);

            // Re-configure DataService with the new access token
            $this->dataService->updateOAuth2Token($newAccessToken);

        } else {
            $error = $this->dataService->getLastError();
            Log::error('Error refreshing token: ' . $error->getResponseBody());
        }
    }

    public function getDataService()
    {
        return $this->dataService;
    }

    public function getAllCustomers()
    {
        $dataService = $this->dataService;

        // Fetch all customers from QBO
        $customers = $dataService->Query("SELECT * FROM Customer");

        if ($dataService->getLastError()) {
            Log::error('Error fetching customers: ' . $dataService->getLastError()->getResponseBody());
            return null;
        }

        return $customers;
    }

    public function getCustomersInBatch($startPosition, $batchSize)
    {
        $dataService = $this->dataService;
        $query = "SELECT * FROM Customer STARTPOSITION $startPosition MAXRESULTS $batchSize";

        // Fetch customers with pagination
        $customers = $dataService->Query($query);

        if ($dataService->getLastError()) {
            Log::error('Error fetching customers: ' . $dataService->getLastError()->getResponseBody());
            return null;
        }

        return $customers;
    }
}
