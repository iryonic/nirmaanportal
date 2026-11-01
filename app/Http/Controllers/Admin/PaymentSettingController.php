<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Services\RazorpayService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PaymentSettingController extends Controller
{
    /**
     * Show the Payment Gateway, Direct UPI, and Banking settings page.
     */
    public function index(RazorpayService $razorpayService): View
    {
        $settings = [
            // Razorpay Gateway
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

            // Direct UPI Gateway & QR
            'upi_enabled' => filter_var(SiteSetting::get('upi_enabled', '1'), FILTER_VALIDATE_BOOLEAN),
            'upi_vpa' => SiteSetting::get('upi_vpa', 'nirmaanacademy@icici'),
            'upi_merchant_name' => SiteSetting::get('upi_merchant_name', 'Nirmaan Defence Academy'),
            'upi_qr_image' => SiteSetting::get('upi_qr_image'),
            'upi_instructions' => SiteSetting::get('upi_instructions', 'Scan QR with any UPI App (GPay, PhonePe, Paytm, BHIM) or pay to VPA. Enter UTR reference to verify.'),

            // Bank Transfer / NEFT / RTGS
            'bank_transfer_enabled' => filter_var(SiteSetting::get('bank_transfer_enabled', '1'), FILTER_VALIDATE_BOOLEAN),
            'bank_name' => SiteSetting::get('bank_name', 'State Bank of India'),
            'bank_account_name' => SiteSetting::get('bank_account_name', 'Nirmaan Academy Educational Foundation'),
            'bank_account_number' => SiteSetting::get('bank_account_number', '4092101004521'),
            'bank_ifsc_code' => SiteSetting::get('bank_ifsc_code', 'SBIN0001234'),
            'bank_branch' => SiteSetting::get('bank_branch', 'Defence Cantt Branch, Jaipur'),
            'bank_instructions' => SiteSetting::get('bank_instructions', 'Transfer via IMPS / NEFT / RTGS and input your bank reference UTR number.'),

            // Alternate Methods
            'method_card_enabled' => filter_var(SiteSetting::get('method_card_enabled', '1'), FILTER_VALIDATE_BOOLEAN),
            'method_netbanking_enabled' => filter_var(SiteSetting::get('method_netbanking_enabled', '1'), FILTER_VALIDATE_BOOLEAN),
            'method_emi_enabled' => filter_var(SiteSetting::get('method_emi_enabled', '1'), FILTER_VALIDATE_BOOLEAN),
            'method_offline_enabled' => filter_var(SiteSetting::get('method_offline_enabled', '1'), FILTER_VALIDATE_BOOLEAN),
            'offline_campus_address' => SiteSetting::get('offline_campus_address', 'Nirmaan Defence Academy, Officer Training Campus, Plot 42, Civil Lines'),
            'offline_contact_phone' => SiteSetting::get('offline_contact_phone', '+91 98765 43210'),
            'offline_instructions' => SiteSetting::get('offline_instructions', 'Visit our academy admissions desk during working hours (9 AM - 6 PM) to finalize registration in cash or DD.'),
        ];

        $qrImageUrl = null;
        if (!empty($settings['upi_qr_image']) && Storage::disk('public')->exists($settings['upi_qr_image'])) {
            $qrImageUrl = Storage::disk('public')->url($settings['upi_qr_image']);
        }
        $settings['upi_qr_url'] = $qrImageUrl;

        $webhookUrl = url('/webhooks/razorpay');

        return view('admin.settings.payment', compact('settings', 'razorpayService', 'webhookUrl'));
    }

    /**
     * Update Payment Gateway, Direct UPI, and Banking settings.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            // Razorpay
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

            // Direct UPI
            'upi_enabled' => 'nullable|boolean',
            'upi_vpa' => 'nullable|string|max:100',
            'upi_merchant_name' => 'nullable|string|max:100',
            'upi_instructions' => 'nullable|string|max:1000',
            'upi_qr_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:4096',
            'remove_upi_qr' => 'nullable|boolean',

            // Direct Bank Transfer
            'bank_transfer_enabled' => 'nullable|boolean',
            'bank_name' => 'nullable|string|max:150',
            'bank_account_name' => 'nullable|string|max:150',
            'bank_account_number' => 'nullable|string|max:50',
            'bank_ifsc_code' => 'nullable|string|max:30',
            'bank_branch' => 'nullable|string|max:150',
            'bank_instructions' => 'nullable|string|max:1000',

            // Alternate Methods
            'method_card_enabled' => 'nullable|boolean',
            'method_netbanking_enabled' => 'nullable|boolean',
            'method_emi_enabled' => 'nullable|boolean',
            'method_offline_enabled' => 'nullable|boolean',
            'offline_campus_address' => 'nullable|string|max:300',
            'offline_contact_phone' => 'nullable|string|max:50',
            'offline_instructions' => 'nullable|string|max:1000',
        ]);

        // 1. Razorpay Updates
        SiteSetting::set('razorpay_enabled', $request->has('razorpay_enabled') ? '1' : '0');
        SiteSetting::set('razorpay_mode', $validated['razorpay_mode']);
        SiteSetting::set('razorpay_merchant_name', $validated['razorpay_merchant_name']);
        SiteSetting::set('razorpay_theme_color', $validated['razorpay_theme_color']);
        SiteSetting::set('razorpay_fallback_simulation', $request->has('razorpay_fallback_simulation') ? '1' : '0');

        if (isset($validated['razorpay_key_id'])) {
            SiteSetting::set('razorpay_key_id', trim($validated['razorpay_key_id']));
        }
        if (!empty($validated['razorpay_key_secret']) && !str_contains($validated['razorpay_key_secret'], '•••')) {
            SiteSetting::set('razorpay_key_secret', trim($validated['razorpay_key_secret']));
        }
        if (isset($validated['razorpay_live_key_id'])) {
            SiteSetting::set('razorpay_live_key_id', trim($validated['razorpay_live_key_id']));
        }
        if (!empty($validated['razorpay_live_key_secret']) && !str_contains($validated['razorpay_live_key_secret'], '•••')) {
            SiteSetting::set('razorpay_live_key_secret', trim($validated['razorpay_live_key_secret']));
        }
        if (!empty($validated['razorpay_webhook_secret']) && !str_contains($validated['razorpay_webhook_secret'], '•••')) {
            SiteSetting::set('razorpay_webhook_secret', trim($validated['razorpay_webhook_secret']));
        }

        // 2. Direct UPI Updates
        if ($request->has('upi_enabled') || $request->has('upi_vpa')) {
            SiteSetting::set('upi_enabled', $request->has('upi_enabled') ? '1' : '0');
        }
        if (!empty($validated['upi_vpa'])) {
            SiteSetting::set('upi_vpa', trim($validated['upi_vpa']));
        }
        if (!empty($validated['upi_merchant_name'])) {
            SiteSetting::set('upi_merchant_name', trim($validated['upi_merchant_name']));
        }
        if (isset($validated['upi_instructions'])) {
            SiteSetting::set('upi_instructions', $validated['upi_instructions']);
        }

        // Handle QR Code Removal
        if ($request->boolean('remove_upi_qr')) {
            $existingQr = SiteSetting::get('upi_qr_image');
            if ($existingQr && Storage::disk('public')->exists($existingQr)) {
                Storage::disk('public')->delete($existingQr);
            }
            SiteSetting::set('upi_qr_image', '');
        }

        // Handle QR Code Upload
        if ($request->hasFile('upi_qr_file')) {
            $existingQr = SiteSetting::get('upi_qr_image');
            if ($existingQr && Storage::disk('public')->exists($existingQr)) {
                Storage::disk('public')->delete($existingQr);
            }
            $qrPath = $request->file('upi_qr_file')->store('payment', 'public');
            SiteSetting::set('upi_qr_image', $qrPath);
        }

        // 3. Direct Bank Transfer Updates
        if ($request->has('bank_transfer_enabled') || $request->has('bank_name')) {
            SiteSetting::set('bank_transfer_enabled', $request->has('bank_transfer_enabled') ? '1' : '0');
        }
        if (!empty($validated['bank_name'])) {
            SiteSetting::set('bank_name', trim($validated['bank_name']));
        }
        if (!empty($validated['bank_account_name'])) {
            SiteSetting::set('bank_account_name', trim($validated['bank_account_name']));
        }
        if (!empty($validated['bank_account_number'])) {
            SiteSetting::set('bank_account_number', trim($validated['bank_account_number']));
        }
        if (!empty($validated['bank_ifsc_code'])) {
            SiteSetting::set('bank_ifsc_code', strtoupper(trim($validated['bank_ifsc_code'])));
        }
        if (isset($validated['bank_branch'])) {
            SiteSetting::set('bank_branch', trim($validated['bank_branch']));
        }
        if (isset($validated['bank_instructions'])) {
            SiteSetting::set('bank_instructions', $validated['bank_instructions']);
        }

        // 4. Alternate Methods Updates
        SiteSetting::set('method_card_enabled', $request->has('method_card_enabled') ? '1' : '0');
        SiteSetting::set('method_netbanking_enabled', $request->has('method_netbanking_enabled') ? '1' : '0');
        SiteSetting::set('method_emi_enabled', $request->has('method_emi_enabled') ? '1' : '0');
        SiteSetting::set('method_offline_enabled', $request->has('method_offline_enabled') ? '1' : '0');
        SiteSetting::set('offline_campus_address', trim($validated['offline_campus_address'] ?? ''));
        SiteSetting::set('offline_contact_phone', trim($validated['offline_contact_phone'] ?? ''));
        SiteSetting::set('offline_instructions', $validated['offline_instructions'] ?? '');

        return redirect()->route('admin.settings.payment')->with('success', 'All payment channels, UPI settings, and banking details updated successfully.');
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
