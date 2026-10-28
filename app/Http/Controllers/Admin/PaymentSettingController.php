<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Services\RazorpayService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class PaymentSettingController extends Controller
{
    /**
     * Show the Razorpay Payment Gateway settings page.
     */
    public function index(RazorpayService $razorpayService): View
    {
        $settings = [
            'razorpay_enabled' => filter_var(SiteSetting::get('razorpay_enabled', config('services.razorpay.enabled', true)), FILTER_VALIDATE_BOOLEAN),
            'razorpay_mode' => SiteSetting::get('razorpay_mode', config('services.razorpay.mode', 'test')),
            'razorpay_key_id' => SiteSetting::get('razorpay_key_id', config('services.razorpay.key_id', '')),
            'razorpay_key_secret' => SiteSetting::get('razorpay_key_secret', config('services.razorpay.key_secret', '')),
            'razorpay_live_key_id' => SiteSetting::get('razorpay_live_key_id', ''),
            'razorpay_live_key_secret' => SiteSetting::get('razorpay_live_key_secret', ''),
            'razorpay_webhook_secret' => SiteSetting::get('razorpay_webhook_secret', config('services.razorpay.webhook_secret', '')),
            'razorpay_fallback_simulation' => filter_var(SiteSetting::get('razorpay_fallback_simulation', config('services.razorpay.fallback_simulation', true)), FILTER_VALIDATE_BOOLEAN),
            'razorpay_merchant_name' => SiteSetting::get('razorpay_merchant_name', 'Nirmaan Academy'),
            'razorpay_theme_color' => SiteSetting::get('razorpay_theme_color', '#172554'),
        ];

        $webhookUrl = url('/webhooks/razorpay');

        return view('admin.settings.payment', compact('settings', 'razorpayService', 'webhookUrl'));
    }

    /**
     * Update Razorpay Payment Gateway settings.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'razorpay_enabled' => 'nullable|boolean',
            'razorpay_mode' => 'required|in:test,live',
            'razorpay_key_id' => 'nullable|string|max:255',
            'razorpay_key_secret' => 'nullable|string|max:255',
            'razorpay_live_key_id' => 'nullable|string|max:255',
            'razorpay_live_key_secret' => 'nullable|string|max:255',
            'razorpay_webhook_secret' => 'nullable|string|max:255',
            'razorpay_fallback_simulation' => 'nullable|boolean',
            'razorpay_merchant_name' => 'required|string|max:100',
            'razorpay_theme_color' => 'required|string|max:20',
        ]);

        SiteSetting::set('razorpay_enabled', $request->has('razorpay_enabled') ? '1' : '0');
        SiteSetting::set('razorpay_mode', $validated['razorpay_mode']);
        SiteSetting::set('razorpay_merchant_name', $validated['razorpay_merchant_name']);
        SiteSetting::set('razorpay_theme_color', $validated['razorpay_theme_color']);
        SiteSetting::set('razorpay_fallback_simulation', $request->has('razorpay_fallback_simulation') ? '1' : '0');

        // Test Credentials
        if (isset($validated['razorpay_key_id'])) {
            SiteSetting::set('razorpay_key_id', trim($validated['razorpay_key_id']));
        }
        if (!empty($validated['razorpay_key_secret']) && !str_contains($validated['razorpay_key_secret'], '•••')) {
            SiteSetting::set('razorpay_key_secret', trim($validated['razorpay_key_secret']));
        }

        // Live Credentials
        if (isset($validated['razorpay_live_key_id'])) {
            SiteSetting::set('razorpay_live_key_id', trim($validated['razorpay_live_key_id']));
        }
        if (!empty($validated['razorpay_live_key_secret']) && !str_contains($validated['razorpay_live_key_secret'], '•••')) {
            SiteSetting::set('razorpay_live_key_secret', trim($validated['razorpay_live_key_secret']));
        }

        // Webhook Secret
        if (!empty($validated['razorpay_webhook_secret']) && !str_contains($validated['razorpay_webhook_secret'], '•••')) {
            SiteSetting::set('razorpay_webhook_secret', trim($validated['razorpay_webhook_secret']));
        }

        return redirect()->route('admin.settings.payment')->with('success', 'Razorpay payment settings updated successfully.');
    }

    /**
     * Test Razorpay API connection.
     */
    public function testConnection(RazorpayService $razorpayService): JsonResponse
    {
        $keyId = $razorpayService->getKeyId();
        $keySecret = $razorpayService->getKeySecret();
        $mode = $razorpayService->getMode();

        if (empty($keyId) || empty($keySecret)) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot test connection: Razorpay Key ID and Secret are not yet configured for ' . strtoupper($mode) . ' mode.',
            ], 422);
        }

        try {
            $response = Http::withBasicAuth($keyId, $keySecret)
                ->timeout(8)
                ->get('https://api.razorpay.com/v1/payments', ['count' => 1]);

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Connection verified successfully! Authenticated with Razorpay in ' . strtoupper($mode) . ' mode.',
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Razorpay API returned HTTP ' . $response->status() . ': ' . ($response->json('error.description') ?? 'Authentication failed.'),
            ], 400);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Network error connecting to Razorpay: ' . $e->getMessage(),
            ], 500);
        }
    }
}
