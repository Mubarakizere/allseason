<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WeFlexfyService
{
    protected string $baseUrl;
    protected string $accessKey;
    protected string $secretKey;
    protected string $recipientNumber;
    protected string $currency;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('payments.weflexfy.base_url', 'https://api.weflexfy.com'), '/');
        $this->accessKey = config('payments.weflexfy.access_key', '');
        $this->secretKey = config('payments.weflexfy.secret_key', '');
        $this->recipientNumber = config('payments.weflexfy.recipient_number', '');
        $this->currency = config('payments.weflexfy.currency', 'RWF');
    }

    /**
     * Initiate a payment request with WeFlexfy API
     *
     * @param float|int $amount Total amount billed
     * @param string $billName Customer full name
     * @param string $billEmail Customer email
     * @param string $billPhone Customer phone number
     * @param array $transfers Array of transfer objects
     * @param string|null $currency Currency code ("RWF", "USD")
     * @param string $billCountry 2-letter country code
     * @return array
     */
    public function initiatePayment(
        $amount,
        string $billName,
        string $billEmail,
        string $billPhone,
        array $transfers = [],
        ?string $currency = null,
        string $billCountry = 'RW'
    ): array {
        try {
            // Default transfer if none provided
            if (empty($transfers)) {
                $transfers = [
                    [
                        'percentage' => 100,
                        'recipientNumber' => $this->recipientNumber ?: $billPhone,
                        'payload' => null,
                    ]
                ];
            }

            // Standardize recipient numbers if empty
            foreach ($transfers as &$transfer) {
                if (empty($transfer['recipientNumber'])) {
                    $transfer['recipientNumber'] = $this->recipientNumber ?: $billPhone;
                }
            }
            unset($transfer);

            $payload = [
                'amount' => (float) $amount,
                'currency' => strtoupper($currency ?: $this->currency),
                'billName' => $billName,
                'billEmail' => $billEmail,
                'billPhone' => $billPhone,
                'billCountry' => $billCountry,
                'transfers' => $transfers,
            ];

            Log::info('WeFlexfy Initiate Payment Request:', ['url' => $this->baseUrl . '/api/v1/payment/initiate', 'payload' => $payload]);

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'access_key' => $this->accessKey,
            ])->post($this->baseUrl . '/api/v1/payment/initiate', $payload);

            $responseData = $response->json();

            Log::info('WeFlexfy Initiate Payment Response:', ['status' => $response->status(), 'response' => $responseData]);

            if ($response->successful() && isset($responseData['data']['iframeUrl'])) {
                return [
                    'success' => true,
                    'message' => $responseData['message'] ?? 'Payment initiated successfully',
                    'requestToken' => $responseData['data']['requestToken'] ?? null,
                    'iframeUrl' => $responseData['data']['iframeUrl'] ?? null,
                    'transfers' => $responseData['data']['transfers'] ?? [],
                    'data' => $responseData['data'] ?? [],
                ];
            }

            return [
                'success' => false,
                'message' => $responseData['message'] ?? 'Failed to initiate payment with WeFlexfy',
                'error' => $responseData ?? 'HTTP Error ' . $response->status(),
            ];

        } catch (Exception $e) {
            Log::error('WeFlexfy Initiate Payment Exception: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'message' => 'An error occurred while connecting to the payment gateway: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Decode and verify incoming Webhook JWT token
     *
     * @param string $jwtToken
     * @return array|null Decoded payload array or null if verification fails
     */
    public function verifyWebhookJwt(string $jwtToken): ?array
    {
        try {
            $parts = explode('.', trim($jwtToken));
            if (count($parts) !== 3) {
                Log::warning('WeFlexfy JWT Verification Failed: Token does not contain 3 parts.');
                return null;
            }

            [$headerB64, $payloadB64, $sigB64] = $parts;

            // Verify HMAC SHA256 Signature
            $computedSigRaw = hash_hmac('sha256', "$headerB64.$payloadB64", $this->secretKey, true);
            $computedSigB64 = self::base64UrlEncode($computedSigRaw);

            if (!hash_equals($computedSigB64, $sigB64)) {
                // Fallback attempt: raw base64 decode comparison if url-safe string formats differ
                $decodedSig = self::base64UrlDecode($sigB64);
                if (!hash_equals($computedSigRaw, $decodedSig)) {
                    Log::warning('WeFlexfy JWT Verification Failed: Signature mismatch.');
                    return null;
                }
            }

            $payloadJson = self::base64UrlDecode($payloadB64);
            $decodedPayload = json_decode($payloadJson, true);

            if (!is_array($decodedPayload)) {
                Log::warning('WeFlexfy JWT Verification Failed: Payload is not valid JSON.');
                return null;
            }

            return $decodedPayload;

        } catch (Exception $e) {
            Log::error('WeFlexfy JWT Verification Exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Helper to URL-safe Base64 encode
     */
    public static function base64UrlEncode(string $input): string
    {
        return rtrim(strtr(base64_encode($input), '+/', '-_'), '=');
    }

    /**
     * Helper to URL-safe Base64 decode
     */
    public static function base64UrlDecode(string $input): string
    {
        $remainder = strlen($input) % 4;
        if ($remainder) {
            $input .= str_repeat('=', 4 - $remainder);
        }
        return base64_decode(strtr($input, '-_', '+/'));
    }
}
