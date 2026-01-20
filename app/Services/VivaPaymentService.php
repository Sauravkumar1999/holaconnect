<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class VivaPaymentService
{
    private string $environment;
    private array $urls;
    private string $authHeader;

    public function __construct()
    {
        $this->environment = config('services.viva.environment', 'demo');
        $this->urls = config("services.viva.urls.{$this->environment}", config('services.viva.urls.demo'));
        $this->authHeader = 'Basic ' . base64_encode(
            config('services.viva.merchant_id') . ':' . config('services.viva.api_key')
        );
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
            $response = $this->makeRequest('GET', $this->urls['orders_detail'] . '/' . $orderCode);
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
     * Build order payload from data
     */
    private function buildOrderPayload(array $data): array
    {
        $payload = [
            'amount' => (int) ($data['amount'] * 100), // Convert to cents
            'customerTrns' => $data['customerTrns'] ?? 'Registration Payment',
            'email' => $data['email'] ?? '',
            'fullName' => $data['fullName'] ?? '',
            'requestLang' => 'en-GB',
        ];

        // Add optional fields
        if (!empty($data['phone'])) {
            $payload['phone'] = $data['phone'];
        }
        if (!empty($data['merchantTrns'])) {
            $payload['merchantTrns'] = $data['merchantTrns'];
        }

        return $payload;
    }

    /**
     * Make HTTP request with authentication
     */
    private function makeRequest(string $method, string $url, ?array $payload = null)
    {
        $request = Http::withHeaders([
            'Authorization' => $this->authHeader,
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
