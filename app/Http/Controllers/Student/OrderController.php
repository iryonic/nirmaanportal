<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\MockTest;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    protected $coupons = [
        'AGNIVEER50' => [
            'type' => 'percent',
            'value' => 50,
            'max' => 5000,
            'label' => 'Special Agniveer 50% Defence Scholarship',
        ],
        'OFFICER20' => [
            'type' => 'percent',
            'value' => 20,
            'max' => 2000,
            'label' => 'Officer Aspirants 20% Merit Concession',
        ],
        'WARRIOR15' => [
            'type' => 'percent',
            'value' => 15,
            'max' => 1500,
            'label' => 'Defence Personnel Dependent 15% Concession',
        ],
        'NIRMAAN10' => [
            'type' => 'percent',
            'value' => 10,
            'max' => 1000,
            'label' => 'Nirmaan Early Bird 10% Concession',
        ],
        'CADET100' => [
            'type' => 'percent',
            'value' => 100,
            'max' => 99999,
            'label' => 'Param Vishisht 100% Full Academy Scholarship',
        ],
    ];

    public function validateCoupon(Request $request)
    {
        $code = strtoupper(trim($request->input('code', '')));
        $type = $request->input('type', 'course');
        $itemId = $request->input('item_id');

        $basePrice = 0;
        if ($type === 'course') {
            $course = Course::find($itemId);
            $basePrice = $course ? (float) $course->price : 0;
        } else {
            $test = MockTest::find($itemId);
            $basePrice = $test ? (float) ($test->price > 0 ? $test->price : 499.00) : 0;
        }

        if (empty($code)) {
            return response()->json([
                'valid' => false,
                'message' => 'Please enter a coupon code.',
            ], 422);
        }

        if (!isset($this->coupons[$code])) {
            return response()->json([
                'valid' => false,
                'message' => 'Invalid or expired promo code. Please check and try again.',
            ], 422);
        }

        $coupon = $this->coupons[$code];
        $discount = round(($basePrice * $coupon['value']) / 100, 2);
        if ($discount > $coupon['max']) {
            $discount = (float) $coupon['max'];
        }
        $finalAmount = max(0, round($basePrice - $discount, 2));

        return response()->json([
            'valid' => true,
            'code' => $code,
            'discount_type' => $coupon['type'],
            'discount_value' => $coupon['value'],
            'discount_amount' => $discount,
            'final_amount' => $finalAmount,
            'formatted_discount' => '₹ ' . number_format($discount, 2),
            'formatted_final' => '₹ ' . number_format($finalAmount, 2),
            'label' => $coupon['label'],
            'message' => 'Coupon code applied successfully! You saved ₹ ' . number_format($discount, 2),
        ]);
    }

    protected function calculateDiscount(string $code, float $basePrice): array
    {
        $code = strtoupper(trim($code));
        if (!isset($this->coupons[$code])) {
            return ['code' => null, 'discount' => 0.0, 'final' => $basePrice, 'label' => null];
        }

        $coupon = $this->coupons[$code];
        $discount = round(($basePrice * $coupon['value']) / 100, 2);
        if ($discount > $coupon['max']) {
            $discount = (float) $coupon['max'];
        }
        $final = max(0, round($basePrice - $discount, 2));

        return [
            'code' => $code,
            'discount' => $discount,
            'final' => $final,
            'label' => $coupon['label'],
        ];
    }

    public function checkout(Course $course)
    {
        $user = Auth::user();

        if ($user->isEnrolledIn($course)) {
            return redirect()->route('student.courses.learn', $course->id)
                ->with('info', 'You are already enrolled in this course.');
        }

        $type = 'course';
        $item = $course;

        return view('student.checkout', compact('item', 'type', 'user'));
    }

    public function processPayment(Course $course, Request $request)
    {
        $user = Auth::user();

        if ($user->isEnrolledIn($course)) {
            return redirect()->route('student.courses.learn', $course->id);
        }

        $validated = $request->validate([
            'payment_method' => ['required', 'string', 'in:upi,netbanking,card,emi,mock,razorpay,paylater'],
            'coupon_code' => ['nullable', 'string', 'max:30'],
            'payment_reference' => ['nullable', 'string', 'max:100'],
            'method_meta' => ['nullable', 'string', 'max:255'],
        ]);

        $basePrice = (float) $course->price;
        $couponData = $this->calculateDiscount($validated['coupon_code'] ?? '', $basePrice);
        $finalAmount = $couponData['final'];

        $orderNumber = 'NIR-' . date('Ymd') . '-' . strtoupper(Str::random(6));
        $paymentRef = !empty($validated['payment_reference'])
            ? $validated['payment_reference']
            : ($validated['payment_method'] === 'upi' ? 'upi_' . Str::random(12) : 'pay_' . Str::random(14));

        $notesArray = [
            'item_title' => $course->title,
            'sac_code' => '999293',
            'method' => strtoupper($validated['payment_method']),
            'meta' => $validated['method_meta'] ?? null,
            'coupon' => $couponData['code'],
            'discount' => $couponData['discount'],
            'original_price' => $basePrice,
        ];

        $order = Order::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'order_number' => $orderNumber,
            'amount' => $finalAmount,
            'currency' => 'INR',
            'status' => 'paid',
            'payment_gateway' => $validated['payment_method'],
            'payment_id' => $paymentRef,
            'paid_at' => now(),
            'notes' => json_encode($notesArray),
        ]);

        Enrollment::firstOrCreate(
            ['user_id' => $user->id, 'course_id' => $course->id],
            [
                'order_id' => $order->id,
                'status' => 'active',
                'enrolled_at' => now(),
                'expires_at' => now()->addMonths(6),
            ]
        );

        return redirect()->route('student.orders.receipt', $order->id)
            ->with('success', 'Payment verified successfully! Your batch enrollment is active.');
    }

    public function checkoutTest(MockTest $test)
    {
        $user = Auth::user();

        if ($test->is_free) {
            return redirect()->route('student.tests.instructions', $test->id)
                ->with('info', 'This mock test is completely free to attempt.');
        }

        $hasPaid = Order::where('user_id', $user->id)
            ->where('mock_test_id', $test->id)
            ->where('status', 'paid')
            ->exists();

        if ($hasPaid) {
            return redirect()->route('student.tests.instructions', $test->id)
                ->with('info', 'You have already unlocked this test series.');
        }

        $type = 'test';
        $item = $test;

        return view('student.checkout', compact('item', 'type', 'user'));
    }

    public function processTestPayment(MockTest $test, Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'payment_method' => ['required', 'string', 'in:upi,netbanking,card,emi,mock,razorpay,paylater'],
            'coupon_code' => ['nullable', 'string', 'max:30'],
            'payment_reference' => ['nullable', 'string', 'max:100'],
            'method_meta' => ['nullable', 'string', 'max:255'],
        ]);

        $basePrice = (float) ($test->price > 0 ? $test->price : 499.00);
        $couponData = $this->calculateDiscount($validated['coupon_code'] ?? '', $basePrice);
        $finalAmount = $couponData['final'];

        $orderNumber = 'NIR-TEST-' . date('Ymd') . '-' . strtoupper(Str::random(6));
        $paymentRef = !empty($validated['payment_reference'])
            ? $validated['payment_reference']
            : ($validated['payment_method'] === 'upi' ? 'upi_' . Str::random(12) : 'pay_' . Str::random(14));

        $notesArray = [
            'item_title' => $test->title,
            'sac_code' => '999293',
            'method' => strtoupper($validated['payment_method']),
            'meta' => $validated['method_meta'] ?? null,
            'coupon' => $couponData['code'],
            'discount' => $couponData['discount'],
            'original_price' => $basePrice,
        ];

        $order = Order::create([
            'user_id' => $user->id,
            'course_id' => null,
            'mock_test_id' => $test->id,
            'order_number' => $orderNumber,
            'amount' => $finalAmount,
            'currency' => 'INR',
            'status' => 'paid',
            'payment_gateway' => $validated['payment_method'],
            'payment_id' => $paymentRef,
            'paid_at' => now(),
            'notes' => json_encode($notesArray),
        ]);

        return redirect()->route('student.orders.receipt', $order->id)
            ->with('success', 'Test series unlocked! You can now start your CBT examination.');
    }

    public function receipt(Order $order)
    {
        if ($order->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }

        $order->load(['course', 'mockTest', 'user']);

        return view('student.orders.receipt', compact('order'));
    }

    public function history()
    {
        $user = Auth::user();
        $orders = Order::where('user_id', $user->id)
            ->with(['course', 'mockTest'])
            ->latest()
            ->paginate(10);

        return view('student.orders', compact('orders'));
    }
}
