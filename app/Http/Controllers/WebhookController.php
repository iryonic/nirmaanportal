<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Order;
use App\Services\RazorpayService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    /**
     * Handle incoming Razorpay Webhook events.
     */
    public function handleRazorpay(Request $request, RazorpayService $razorpayService): JsonResponse
    {
        $signature = $request->header('X-Razorpay-Signature');
        $payload = $request->getContent();

        if (empty($signature)) {
            Log::warning('Razorpay webhook received without X-Razorpay-Signature header');
            return response()->json(['error' => 'Missing signature header'], 400);
        }

        // Verify cryptographic HMAC signature
        if (!$razorpayService->verifyWebhookSignature($payload, $signature)) {
            Log::warning('Razorpay webhook signature verification failed', [
                'signature' => $signature,
            ]);
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        $event = $request->json('event');
        $data = $request->json('payload');

        Log::info('Razorpay webhook event received: ' . $event, ['event' => $event]);

        // Process order / payment success events
        if (in_array($event, ['order.paid', 'payment.captured'])) {
            $payment = $data['payment']['entity'] ?? null;
            $orderEntity = $data['order']['entity'] ?? null;

            $razorpayOrderId = $orderEntity['id'] ?? ($payment['order_id'] ?? null);
            $razorpayPaymentId = $payment['id'] ?? null;

            if ($razorpayOrderId) {
                $order = Order::where('razorpay_order_id', $razorpayOrderId)->first();

                if ($order) {
                    if ($order->status !== 'paid') {
                        $order->update([
                            'status' => 'paid',
                            'payment_id' => $razorpayPaymentId ?: $order->payment_id,
                            'paid_at' => now(),
                        ]);
                    }

                    // Ensure course enrollment is active
                    if ($order->course_id) {
                        Enrollment::firstOrCreate(
                            ['user_id' => $order->user_id, 'course_id' => $order->course_id],
                            [
                                'order_id' => $order->id,
                                'status' => 'active',
                                'enrolled_at' => now(),
                                'expires_at' => now()->addMonths(6),
                            ]
                        );
                    }
                }
            }
        }

        return response()->json(['status' => 'success']);
    }
}
