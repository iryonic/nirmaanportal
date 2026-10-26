@extends('layouts.student')

@section('title', 'Payment Receipt — ' . $order->order_number)

@section('content')
@php
    $notes = json_decode($order->notes, true);
    $isJsonNotes = json_last_error() === JSON_ERROR_NONE && is_array($notes);
    $couponCode = $isJsonNotes ? ($notes['coupon'] ?? null) : null;
    $couponDiscount = $isJsonNotes ? (float) ($notes['discount'] ?? 0) : 0;
    $originalPrice = $isJsonNotes ? (float) ($notes['original_price'] ?? $order->amount) : (float) $order->amount;
    $methodMeta = $isJsonNotes ? ($notes['meta'] ?? null) : null;
@endphp

<div class="py-8 sm:py-12 bg-gray-50 min-h-screen">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <!-- Top Actions Bar (Hidden in Print) -->
        <div class="flex items-center justify-between print:hidden">
            <a href="{{ route('student.orders') }}" class="inline-flex items-center space-x-1.5 text-xs font-bold text-gray-600 hover:text-gray-900 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <span>Back to Order History</span>
            </a>

            <div class="flex items-center space-x-3">
                <button type="button" onclick="window.print()" class="inline-flex items-center space-x-2 px-4 py-2 rounded-xl text-xs font-bold text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 transition shadow-2xs">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    <span>Print Tax Invoice</span>
                </button>

                @if($order->course_id)
                    <a href="{{ route('student.courses.learn', $order->course_id) }}" class="inline-flex items-center space-x-2 px-4 py-2 rounded-xl text-xs font-bold text-white bg-blue-700 hover:bg-blue-800 transition shadow-2xs">
                        <span>Open Course LMS →</span>
                    </a>
                @elseif($order->mock_test_id)
                    <a href="{{ route('student.tests.instructions', $order->mock_test_id) }}" class="inline-flex items-center space-x-2 px-4 py-2 rounded-xl text-xs font-bold text-white bg-blue-700 hover:bg-blue-800 transition shadow-2xs">
                        <span>Launch CBT Engine →</span>
                    </a>
                @endif
            </div>
        </div>

        <!-- Official Receipt Card -->
        <div class="bg-white rounded-2xl border border-gray-200 p-6 sm:p-10 text-gray-900 shadow-xs print:border-none print:p-0 print:shadow-none">
            
            <!-- Receipt Header -->
            <div class="flex flex-col sm:flex-row sm:items-start justify-between pb-8 border-b border-gray-200 gap-6">
                <div>
                    <div class="flex items-center space-x-2.5">
                        <span class="w-8 h-8 rounded-lg bg-blue-700 text-white font-black text-sm flex items-center justify-center">
                            N
                        </span>
                        <span class="text-xl font-bold tracking-tight text-gray-900">NIRMAAN</span>
                    </div>
                    <p class="text-xs text-gray-600 mt-1.5 font-medium">Premier Defence &amp; Security Services Academy</p>
                    <p class="text-[11px] text-gray-400">GSTIN: <span class="font-mono font-medium text-gray-600">07AABCN1234F1Z8</span> • SAC: <span class="font-mono font-medium text-gray-600">999293</span></p>
                    <p class="text-[11px] text-gray-400">support@nirmaan.edu.in • State Code: 07 (Delhi)</p>
                </div>

                <div class="sm:text-right space-y-1">
                    <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-50 text-emerald-800 border border-emerald-200 inline-block mb-1">
                        {{ strtoupper($order->status) }}
                    </span>
                    <h2 class="text-sm font-bold text-gray-900">TAX INVOICE / RECEIPT</h2>
                    <p class="text-xs font-mono font-bold text-gray-600">{{ $order->order_number }}</p>
                    <p class="text-[11px] text-gray-400">Date: {{ $order->paid_at ? $order->paid_at->format('d M, Y h:i A') : $order->created_at->format('d M, Y h:i A') }}</p>
                </div>
            </div>

            <!-- Billed To & Payment Meta -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 py-6 border-b border-gray-200 text-xs">
                <div>
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block mb-1.5">Billed To (Cadet)</span>
                    <p class="text-sm font-bold text-gray-900">{{ $order->user->name }}</p>
                    <p class="text-gray-500">{{ $order->user->email }}</p>
                    <p class="text-gray-500">{{ $order->user->phone ?? 'Phone on verified record' }}</p>
                    <span class="inline-block mt-1 text-[10px] font-medium text-blue-700 bg-blue-50 px-2 py-0.5 rounded border border-blue-100">
                        Candidate Category: Defence Aspirant
                    </span>
                </div>

                <div class="sm:text-right space-y-1">
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider block mb-1.5">Transaction Metadata</span>
                    <p class="text-gray-600">Transaction ID: <span class="font-mono font-semibold text-gray-900">{{ $order->payment_id ?? 'N/A' }}</span></p>
                    <p class="text-gray-600">Gateway: <span class="uppercase font-semibold text-gray-900">{{ $order->payment_gateway }}</span></p>
                    @if($methodMeta)
                        <p class="text-gray-600">Payment Reference: <span class="font-mono font-medium text-gray-800">{{ $methodMeta }}</span></p>
                    @endif
                    <p class="text-gray-600">Currency / Place of Supply: <span class="font-semibold text-gray-900">{{ $order->currency }} (07-Delhi)</span></p>
                </div>
            </div>

            <!-- Purchased Item Details -->
            <div class="py-6 border-b border-gray-200">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="text-[10px] font-bold uppercase tracking-wider text-gray-400 border-b border-gray-100 pb-2">
                            <th class="pb-3">Item Description</th>
                            <th class="pb-3 text-center">SAC Code</th>
                            <th class="pb-3 text-center">Category</th>
                            <th class="pb-3 text-right">Base Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr>
                            <td class="py-4 pr-4">
                                @if($order->course)
                                    <div class="font-bold text-gray-900 text-sm">{{ $order->course->title }}</div>
                                    <div class="text-[11px] text-gray-500 mt-0.5">Comprehensive Defence Curriculum, Video Modules &amp; Mentorship</div>
                                @elseif($order->mockTest)
                                    <div class="font-bold text-gray-900 text-sm">{{ $order->mockTest->title }}</div>
                                    <div class="text-[11px] text-gray-500 mt-0.5">Full-Length Online CBT Test Series with Negative Marking &amp; Solutions</div>
                                @else
                                    <div class="font-bold text-gray-900 text-sm">Educational Subscription Access</div>
                                    <div class="text-[11px] text-gray-500 mt-0.5">Online Learning Portal Access</div>
                                @endif
                            </td>
                            <td class="py-4 text-center font-mono text-gray-600">
                                999293
                            </td>
                            <td class="py-4 text-center">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-100">
                                    {{ $order->course ? 'Course Batch' : 'Test Series' }}
                                </span>
                            </td>
                            <td class="py-4 text-right font-bold text-gray-900 font-mono text-sm">
                                ₹ {{ number_format($originalPrice, 2) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Calculation Totals -->
            <div class="py-6 border-b border-gray-200">
                <div class="max-w-xs ml-auto space-y-2 text-xs">
                    <div class="flex justify-between text-gray-600">
                        <span>Original Base Fee:</span>
                        <span class="font-mono font-medium">₹ {{ number_format($originalPrice, 2) }}</span>
                    </div>

                    @if($couponCode && $couponDiscount > 0)
                        <div class="flex justify-between text-emerald-700 font-bold">
                            <span>Scholarship Promo ({{ $couponCode }}):</span>
                            <span class="font-mono">-₹ {{ number_format($couponDiscount, 2) }}</span>
                        </div>
                    @endif

                    <div class="flex justify-between text-gray-600">
                        <span>Educational GST (Exempt):</span>
                        <span class="font-mono font-medium text-emerald-700">₹ 0.00</span>
                    </div>

                    <div class="flex justify-between text-sm font-bold text-gray-900 pt-2 border-t border-gray-200">
                        <span>Net Paid:</span>
                        <span class="font-mono text-emerald-700">₹ {{ number_format($order->amount, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Footer Notes & Terms -->
            <div class="pt-6 text-[11px] text-gray-500 space-y-1.5 leading-relaxed">
                <p class="font-bold text-gray-700">Terms &amp; Enrollment Instructions:</p>
                <p>1. This is a system-generated electronic tax invoice pursuant to Section 31 of the CGST Act, 2017 and requires no physical signature.</p>
                <p>2. Access to online course materials and CBT mock tests is active immediately on your Nirmaan student portal.</p>
                <p>3. For questions regarding your enrollment or fee verification, contact <span class="font-semibold text-gray-700">accounts@nirmaan.edu.in</span> quoting Order ID <span class="font-mono font-semibold text-gray-800">{{ $order->order_number }}</span>.</p>
            </div>

        </div>

    </div>
</div>
@endsection
