@extends('layouts.student')

@section('title', 'Enrollment Checkout — ' . $course->title)

@section('content')
<div class="py-10">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-8">
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900">Course Checkout &amp; Enrollment</h1>
            <p class="text-xs text-gray-500 mt-1">Review your batch details and complete secure checkout to activate full access.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Left Order Details & Payment Selection -->
            <div class="lg:col-span-7 space-y-6">
                
                <form action="{{ route('student.checkout.pay', $course->id) }}" method="POST" id="checkout-form">
                    @csrf

                    <!-- Course Card -->
                    <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-xs mb-6">
                        <span class="text-xs font-bold uppercase tracking-wider text-blue-700 bg-blue-50 px-2.5 py-0.5 rounded-full mb-3 inline-block">
                            {{ $course->examCategory ? $course->examCategory->name : 'Defence Batch' }}
                        </span>
                        <h2 class="text-lg font-bold text-gray-900">{{ $course->title }}</h2>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $course->subtitle }}</p>

                        <div class="mt-4 pt-4 border-t border-gray-100 grid grid-cols-2 gap-2 text-xs text-gray-600">
                            <div>• {{ $course->modules->count() }} Curriculum Modules</div>
                            <div>• {{ $course->lessons->count() }} High-Definition Lessons</div>
                            <div>• Full Mock Tests Included</div>
                            <div>• {{ $course->duration_text ?? '6 Months Access' }}</div>
                        </div>
                    </div>

                    <!-- Payment Methods (Indian Gateway Options: UPI, NetBanking, Cards) -->
                    <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-xs space-y-4">
                        <h3 class="text-sm font-bold text-gray-900">Select Payment Method</h3>

                        <div class="space-y-3" x-data="{ method: 'razorpay' }">
                            <!-- Option 1: Razorpay / UPI Fast Checkout -->
                            <label class="flex items-center justify-between p-4 rounded-2xl border cursor-pointer transition" :class="method === 'razorpay' ? 'border-blue-600 bg-blue-50/50' : 'border-gray-200 hover:bg-gray-50'">
                                <div class="flex items-center space-x-3">
                                    <input type="radio" name="payment_method" value="razorpay" x-model="method" class="text-blue-600 focus:ring-blue-500">
                                    <div>
                                        <span class="text-xs font-bold text-gray-900 block">UPI (Google Pay, PhonePe, Paytm, BHIM)</span>
                                        <span class="text-[11px] text-gray-500">Fast, 0% convenience fee</span>
                                    </div>
                                </div>
                                <span class="text-xs font-bold text-blue-700">Recommended</span>
                            </label>

                            <!-- Option 2: Cards -->
                            <label class="flex items-center justify-between p-4 rounded-2xl border cursor-pointer transition" :class="method === 'card' ? 'border-blue-600 bg-blue-50/50' : 'border-gray-200 hover:bg-gray-50'">
                                <div class="flex items-center space-x-3">
                                    <input type="radio" name="payment_method" value="card" x-model="method" class="text-blue-600 focus:ring-blue-500">
                                    <div>
                                        <span class="text-xs font-bold text-gray-900 block">Debit / Credit Card (RuPay, Visa, Mastercard)</span>
                                        <span class="text-[11px] text-gray-500">Domestic bank cards supported</span>
                                    </div>
                                </div>
                            </label>

                            <!-- Option 3: Net Banking -->
                            <label class="flex items-center justify-between p-4 rounded-2xl border cursor-pointer transition" :class="method === 'netbanking' ? 'border-blue-600 bg-blue-50/50' : 'border-gray-200 hover:bg-gray-50'">
                                <div class="flex items-center space-x-3">
                                    <input type="radio" name="payment_method" value="netbanking" x-model="method" class="text-blue-600 focus:ring-blue-500">
                                    <div>
                                        <span class="text-xs font-bold text-gray-900 block">Net Banking</span>
                                        <span class="text-[11px] text-gray-500">All major Indian banks supported</span>
                                    </div>
                                </div>
                            </label>
                        </div>

                        <div class="pt-4">
                            <button type="submit" class="w-full py-4 rounded-full text-sm font-bold text-white bg-blue-700 hover:bg-blue-800 shadow-md shadow-blue-500/20 transition transform hover:-translate-y-0.5">
                                Pay {{ $course->formatted_price }} &amp; Enroll Immediately
                            </button>
                        </div>
                    </div>

                </form>

            </div>

            <!-- Right Price Summary -->
            <div class="lg:col-span-5 sticky top-24 space-y-4">
                <div class="bg-white rounded-3xl p-6 sm:p-8 border border-gray-100 shadow-lg space-y-4">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-gray-400">Order Summary</h3>

                    <div class="space-y-2.5 text-xs text-gray-600 border-b border-gray-100 pb-4">
                        <div class="flex justify-between">
                            <span>Base Course Price:</span>
                            <span>₹ {{ number_format($course->original_price ?? ($course->price * 1.5), 0) }}</span>
                        </div>
                        <div class="flex justify-between text-emerald-700 font-semibold">
                            <span>Special Cadet Discount:</span>
                            <span>-₹ {{ number_format(($course->original_price ?? ($course->price * 1.5)) - $course->price, 0) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Digital Study Notes &amp; GST:</span>
                            <span class="text-emerald-700 font-semibold">FREE</span>
                        </div>
                    </div>

                    <div class="flex items-baseline justify-between pt-1">
                        <span class="text-sm font-bold text-gray-800">Total Payable Amount:</span>
                        <span class="text-2xl font-black text-gray-900">{{ $course->formatted_price }}</span>
                    </div>

                    <div class="pt-4 border-t border-gray-100 text-[11px] text-gray-400 space-y-1">
                        <p>🔒 256-bit SSL Bank Grade Secure Transaction</p>
                        <p>✓ Instant activation &amp; receipt sent to: <strong>{{ $user->email }}</strong></p>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>
@endsection
