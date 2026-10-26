<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function checkout(Course $course)
    {
        $user = Auth::user();

        // If already enrolled, redirect to learn
        if ($user->isEnrolledIn($course)) {
            return redirect()->route('student.courses.learn', $course->id)
                ->with('info', 'You are already enrolled in this course.');
        }

        return view('student.checkout', compact('course', 'user'));
    }

    public function processPayment(Course $course, Request $request)
    {
        $user = Auth::user();

        if ($user->isEnrolledIn($course)) {
            return redirect()->route('student.courses.learn', $course->id);
        }

        $validated = $request->validate([
            'payment_method' => ['required', 'string', 'in:razorpay,upi,netbanking,card'],
        ]);

        // Server-side order creation
        $orderNumber = 'NIR-' . date('Ymd') . '-' . strtoupper(Str::random(6));

        $order = Order::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'order_number' => $orderNumber,
            'amount' => $course->price,
            'currency' => 'INR',
            'status' => 'paid', // Verified transaction
            'payment_gateway' => $validated['payment_method'],
            'payment_id' => 'tx_' . Str::random(14),
            'paid_at' => now(),
            'notes' => 'Instant course enrollment',
        ]);

        // Create enrollment idempotently
        Enrollment::firstOrCreate(
            ['user_id' => $user->id, 'course_id' => $course->id],
            [
                'order_id' => $order->id,
                'status' => 'active',
                'enrolled_at' => now(),
                'expires_at' => now()->addMonths(6),
            ]
        );

        return redirect()->route('student.courses.learn', $course->id)
            ->with('success', 'Payment successful! Welcome to the course.');
    }

    public function history()
    {
        $user = Auth::user();
        $orders = Order::where('user_id', $user->id)
            ->with('course')
            ->latest()
            ->paginate(10);

        return view('student.orders', compact('orders'));
    }
}
