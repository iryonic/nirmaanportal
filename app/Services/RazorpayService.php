<?php

namespace App\Services;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class RazorpayService
{
    /**
     * Determine if Razorpay gateway is enabled.
     */
    public function isEnabled(): bool
    {
        $setting = SiteSetting::get('razorpay_enabled');
        if ($setting !== null) {
            return filter_var($setting, FILTER_VALIDATE_BOOLEAN);
        }

        return (bool) config('services.razorpay.enabled', true);
    }

    /**
     * Get active mode: 'test' or 'live'.
     */
    public function getMode(): string
    {
        $mode = SiteSetting::get('razorpay_mode');
        if (!empty($mode)) {
            return strtolower(trim($mode)) === 'live' ? 'live' : 'test';
        }

        return config('services.razorpay.mode', 'test');
    }

    /**
     * Get Razorpay Key ID for the current mode.
     */
    public function getKeyId(): string
    {
        $mode = $this->getMode();
        $storedKey = SiteSetting::get($mode === 'live' ? 'razorpay_live_key_id' : 'razorpay_key_id');

        if (!empty($storedKey)) {
            return trim($storedKey);
        }

        // Generic fallback
        $genericKey = SiteSetting::get('razorpay_key_id');
        if (!empty($genericKey)) {
            return trim($genericKey);
        }

        return (string) config('services.razorpay.key_id', '');
    }

    /**
     * Get Razorpay Key Secret for the current mode.
     */
    public function getKeySecret(): string
    {
        $mode = $this->getMode();
        $storedSecret = SiteSetting::get($mode === 'live' ? 'razorpay_live_key_secret' : 'razorpay_key_secret');

        if (!empty($storedSecret)) {
            return trim($storedSecret);
        }

        $genericSecret = SiteSetting::get('razorpay_key_secret');
        if (!empty($genericSecret)) {
            return trim($genericSecret);
        }

        return (string) config('services.razorpay.key_secret', '');
    }

    /**
     * Get Razorpay Webhook Secret.
     */
    public function getWebhookSecret(): string
    {
        $stored = SiteSetting::get('razorpay_webhook_secret');
        if (!empty($stored)) {
            return trim($stored);
        }

        return (string) config('services.razorpay.webhook_secret', '');
    }

    /**
     * Check if fallback simulation mode is enabled.
     */
    public function isFallbackSimulation(): bool
    {
        $setting = SiteSetting::get('razorpay_fallback_simulation');
        if ($setting !== null) {
            return filter_var($setting, FILTER_VALIDATE_BOOLEAN);
        }

        return (bool) config('services.razorpay.fallback_simulation', true);
    }

    /**
     * Get Merchant/Platform display name.
     */
    public function getMerchantName(): string
    {
        return SiteSetting::get('razorpay_merchant_name', 'Nirmaan Academy');
    }

    /**
     * Get Brand/Theme hex color for Razorpay checkout modal.
     */
    public function getThemeColor(): string
    {
        return SiteSetting::get('razorpay_theme_color', '#172554');
    }

    /**
     * Check whether valid API keys are configured for current mode.
     */
    public function hasValidCredentials(): bool
    {
        return !empty($this->getKeyId()) && !empty($this->getKeySecret());
    }

    /**
     * Create an order on Razorpay servers.
     * If credentials are not present or the API request fails and fallback simulation is on,
     * it generates a safe simulated order ID for local sandbox testing.
     *
     * @param float $amount Amount in INR (e.g. 4999.00)
     * @param string $receipt Internal receipt/order number
     * @param array $notes Optional key-value notes
     * @return array
     */
    public function createOrder(float $amount, string $receipt, array $notes = []): array
    {
        $keyId = $this->getKeyId();
        $keySecret = $this->getKeySecret();
        $amountPaise = (int) round($amount * 100);

        // If credentials exist, call Razorpay Orders API
        if (!empty($keyId) && !empty($keySecret)) {
            try {
                $response = Http::withBasicAuth($keyId, $keySecret)
                    ->timeout(10)
                    ->post('https://api.razorpay.com/v1/orders', [
                        'amount' => $amountPaise,
                        'currency' => 'INR',
                        'receipt' => Str::limit($receipt, 40, ''),
                        'payment_capture' => 1,
                        'notes' => $notes,
                    ]);

                if ($response->successful()) {
                    $data = $response->json();
                    return [
                        'success' => true,
                        'order_id' => $data['id'],
                        'amount' => $data['amount'],
                        'currency' => $data['currency'] ?? 'INR',
                        'key_id' => $keyId,
                        'mode' => $this->getMode(),
                        'is_simulated' => false,
                        'raw' => $data,
                    ];
                }

                Log::warning('Razorpay order creation failed from API', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'receipt' => $receipt,
                ]);
            } catch (\Throwable $e) {
                Log::error('Razorpay order creation exception: ' . $e->getMessage(), [
                    'receipt' => $receipt,
                ]);
            }
        }

        // If fallback simulation is permitted
        if ($this->isFallbackSimulation()) {
            $simulatedOrderId = 'order_sim_' . strtolower(Str::random(16));
            return [
                'success' => true,
                'order_id' => $simulatedOrderId,
                'amount' => $amountPaise,
                'currency' => 'INR',
                'key_id' => $keyId ?: 'rzp_test_simulated_key',
                'mode' => $this->getMode(),
                'is_simulated' => true,
            ];
        }

        return [
            'success' => false,
            'message' => 'Unable to initialize Razorpay payment. Please contact support.',
        ];
    }

    /**
     * Verify payment signature returned by Razorpay Checkout popup.
     */
    public function verifyPaymentSignature(string $razorpayOrderId, string $razorpayPaymentId, string $razorpaySignature): bool
    {
        // Handle simulated orders in fallback mode
        if (
            str_starts_with($razorpayOrderId, 'order_sim_') ||
            str_starts_with($razorpayPaymentId, 'pay_sim_')
        ) {
            return $this->isFallbackSimulation();
        }

        $secret = $this->getKeySecret();
        if (empty($secret)) {
            return false;
        }

        $generatedSignature = hash_hmac('sha256', $razorpayOrderId . '|' . $razorpayPaymentId, $secret);

        return hash_equals($generatedSignature, $razorpaySignature);
    }

    /**
     * Verify Razorpay Webhook signature from X-Razorpay-Signature header.
     */
    public function verifyWebhookSignature(string $rawPayload, string $signature): bool
    {
        $webhookSecret = $this->getWebhookSecret();
        if (empty($webhookSecret)) {
            return false;
        }

        $expectedSignature = hash_hmac('sha256', $rawPayload, $webhookSecret);

        return hash_equals($expectedSignature, $signature);
    }

    /**
     * Fetch payment details from Razorpay API.
     */
    public function fetchPayment(string $paymentId): ?array
    {
        $keyId = $this->getKeyId();
        $keySecret = $this->getKeySecret();

        if (empty($keyId) || empty($keySecret)) {
            return null;
        }

        try {
            $response = Http::withBasicAuth($keyId, $keySecret)
                ->timeout(10)
                ->get("https://api.razorpay.com/v1/payments/{$paymentId}");

            if ($response->successful()) {
                return $response->json();
            }
        } catch (\Throwable $e) {
            Log::error('Razorpay fetch payment error: ' . $e->getMessage());
        }

        return null;
    }
}
