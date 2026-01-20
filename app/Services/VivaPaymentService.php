<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class VivaPaymentService
{
    private string $environment;
    private array $urls;
    private string $clientId;
    private string $clientSecret;

    public function __construct()
    {
        $this->environment = config('services.viva.environment', 'demo');
        $this->urls = config("services.viva.urls.{$this->environment}", config('services.viva.urls.demo'));
        $this->clientId = config('services.viva.client_id');
        $this->clientSecret = config('services.viva.client_secret');
    }

    /**
     * Get OAuth2 Access Token
     */
    protected function getAccessToken(): ?string
    {
        $cacheKey = 'viva_access_token_' . $this->environment;

        if (\Illuminate\Support\Facades\Cache::has($cacheKey)) {
            return \Illuminate\Support\Facades\Cache::get($cacheKey);
        }

        try {
            $authHeader = 'Basic ' . base64_encode($this->clientId . ':' . $this->clientSecret);

            $response = Http::withHeaders([
                'Authorization' => $authHeader,
                'Content-Type' => 'application/x-www-form-urlencoded',
            ])->asForm()->post($this->urls['auth'], [
                        'grant_type' => 'client_credentials',
                    ]);

            if ($response->successful()) {
                $data = $response->json();
                $accessToken = $data['access_token'];
                $expiresIn = $data['expires_in'] ?? 3600;

                // Cache the token (subtract 60s for safety)
                \Illuminate\Support\Facades\Cache::put($cacheKey, $accessToken, $expiresIn - 60);

                return $accessToken;
            }

            Log::error('Viva Payment: Failed to get access token', [
                'url' => $this->urls['auth'],
                'status' => $response->status(),
                'body' => $response->body()
            ]);

        } catch (\Exception $e) {
            Log::error('Viva Payment: Exception getting access token', ['message' => $e->getMessage()]);
        }

        return null;
    }

    /**
     * Create a payment order
     */
    public function createPaymentOrder(array $data): ?array
    {
        try {
            $payload = $this->buildOrderPayload($data);
            $response = $this->makeRequest('POST', $this->urls['orders'], $payload);

            if ($response->successful()) {
                $orderData = $response->json();
                if ($orderCode = $orderData['orderCode'] ?? null) {
                    return [
                        'orderCode' => $orderCode,
                        'checkoutUrl' => $this->urls['checkout'] . '?ref=' . $orderCode,
                        'transactionId' => $orderData['transactionId'] ?? null,
                    ];
                }
            }

            $this->logError('createPaymentOrder', $response, $payload);
            return null;
        } catch (\Exception $e) {
            Log::error('Viva Payment: Exception creating payment order', ['message' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Get payment order details
     */
    public function getOrderDetails(string $orderCode): ?array
    {
        try {
            // v2 often uses transactionId or just checking status via orderCode might differ
            // For checking status of an order Code, the URL usually is /checkout/v2/orders/{orderCode} (GET) ??
            // OR /checkout/v2/transactions?orderCode={orderCode}
            // Sticking to configured URL which for v2 should be /checkout/v2/orders if querying by order code
            $response = $this->makeRequest('GET', $this->urls['orders'] . '/' . $orderCode);
            return $response->successful() ? $response->json() : null;
        } catch (\Exception $e) {
            Log::error('Viva Payment: Exception getting order details', ['message' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Verify payment transaction
     */
    public function verifyTransaction(string $transactionId): ?array
    {
        try {
            $response = $this->makeRequest('GET', $this->urls['transactions'] . '/' . $transactionId);
            return $response->successful() ? $response->json() : null;
        } catch (\Exception $e) {
            Log::error('Viva Payment: Exception verifying transaction', ['message' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Build order payload from data (v2 structure)
     */
    private function buildOrderPayload(array $data): array
    {
        $payload = [
            'amount' => (int) ($data['amount'] * 100), // Convert to cents
            'currencyCode' => '978', // EUR
            'customerTrns' => $data['customerTrns'] ?? 'Registration Payment',
            'merchantTrns' => $data['merchantTrns'] ?? '',
            'customer' => [
                'email' => $data['email'] ?? '',
                'fullName' => $data['fullName'] ?? '',
                'phone' => $data['phone'] ?? '',
                'requestLang' => 'en-GB',
            ],
            'sourceCode' => 'Default', // Optional, often 'Default'
        ];

        return $payload;
    }

    /**
     * Make HTTP request with OAuth2 authentication
     */
    private function makeRequest(string $method, string $url, ?array $payload = null)
    {
        $token = $this->getAccessToken();

        if (!$token) {
            throw new \Exception('Unable to obtain Viva Access Token');
        }

        $request = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json',
        ]);

        return $method === 'GET'
            ? $request->get($url)
            : $request->post($url, $payload);
    }

    /**
     * Log error with context
     */
    private function logError(string $method, $response, ?array $payload = null): void
    {
        Log::error("Viva Payment: Failed to {$method}", [
            'response' => $response->body(),
            'status' => $response->status(),
            'payload' => $payload,
        ]);
    }
}
