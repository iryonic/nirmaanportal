@extends('layouts.student')

@section('title', 'Tax Invoice & Payment Receipt — ' . $order->order_number)

@section('content')
@php
    $notes = json_decode($order->notes, true);
    $isJsonNotes = json_last_error() === JSON_ERROR_NONE && is_array($notes);
    $couponCode = $isJsonNotes ? ($notes['coupon'] ?? null) : null;
    $couponDiscount = $isJsonNotes ? (float) ($notes['discount'] ?? 0) : 0;
    $originalPrice = $isJsonNotes ? (float) ($notes['original_price'] ?? $order->amount) : (float) $order->amount;
    if ($originalPrice < (float) $order->amount) {
        $originalPrice = (float) $order->amount;
    }
    $methodMeta = $isJsonNotes ? ($notes['meta'] ?? null) : null;
    $cadetId = 'NIR-CADET-' . str_pad($order->user_id, 5, '0', STR_PAD_LEFT);
    $receiptUrl = route('student.orders.receipt', $order->id);
    $qrApiUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=120x120&data=' . urlencode($receiptUrl);

    // Number to Indian Words Helper
    $numberToWords = function($num) {
        $ones = ['', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine', 'Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'];
        $tens = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];
        $n = (int) $num;
        if ($n == 0) return 'Zero';
        $words = '';
        if ($n >= 10000000) { $words .= $ones[(int)($n / 10000000)] . ' Crore '; $n %= 10000000; }
        if ($n >= 100000) { $words .= $ones[(int)($n / 100000)] . ' Lakh '; $n %= 100000; }
        if ($n >= 1000) { 
            $th = (int)($n / 1000);
            $words .= ($th < 20 ? $ones[$th] : ($tens[(int)($th/10)] . ' ' . $ones[$th%10])) . ' Thousand '; 
            $n %= 1000; 
        }
        if ($n >= 100) { $words .= $ones[(int)($n / 100)] . ' Hundred '; $n %= 100; }
        if ($n > 0) {
            $words .= ($n < 20) ? $ones[$n] : ($tens[(int)($n / 10)] . ' ' . $ones[$n % 10]);
        }
        return trim($words) . ' Rupees Only';
    };
    $amountInWords = $numberToWords($order->amount);
@endphp

<style>
@media print {
    body {
        background-color: #ffffff !important;
        color: #000000 !important;
        margin: 0 !important;
        padding: 0 !important;
    }
    header, footer, nav, .print\:hidden {
        display: none !important;
    }
    .print-receipt-container {
        max-width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
        border: none !important;
        box-shadow: none !important;
    }
    .print-receipt-paper {
        border: 1px solid #cbd5e1 !important;
        box-shadow: none !important;
        padding: 24px !important;
        border-radius: 0 !important;
    }
}
</style>

<div class="py-6 sm:py-10 bg-slate-100/70 min-h-screen text-slate-900">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4 print-receipt-container">

        <!-- Top Actions Bar (Hidden in Print) -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white p-3.5 sm:p-4 rounded-xl border border-slate-200 shadow-2xs print:hidden">
            <div class="flex items-center space-x-2">
                <a href="{{ route('student.orders') }}" class="inline-flex items-center space-x-1.5 text-xs font-semibold text-slate-600 hover:text-blue-950 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    <span>My Order History</span>
                </a>
                <span class="text-slate-300">/</span>
                <span class="text-xs font-bold text-slate-900 font-mono">{{ $order->order_number }}</span>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <button type="button" onclick="window.print()" 
                        class="inline-flex items-center space-x-1.5 px-3.5 py-2 rounded-lg text-xs font-bold text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 active:scale-95 transition-all shadow-2xs cursor-pointer">
                    <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    <span>Print / Save PDF</span>
                </button>

                @if($order->course_id)
                    <a href="{{ route('student.courses.learn', $order->course_id) }}" 
                       class="inline-flex items-center space-x-1.5 px-4 py-2 rounded-lg text-xs font-bold text-white bg-blue-950 hover:bg-blue-900 active:scale-95 transition-all shadow-xs">
                        <span>Go to Course LMS →</span>
                    </a>
                @elseif($order->mock_test_id)
                    <a href="{{ route('student.tests.instructions', $order->mock_test_id) }}" 
                       class="inline-flex items-center space-x-1.5 px-4 py-2 rounded-lg text-xs font-bold text-white bg-blue-950 hover:bg-blue-900 active:scale-95 transition-all shadow-xs">
                        <span>Launch Mock Test Engine →</span>
                    </a>
                @endif
            </div>
        </div>

        <!-- Official Tax Invoice & Admission Receipt Paper -->
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm p-6 sm:p-10 relative overflow-hidden print-receipt-paper">
            
            <!-- Subtle Watermark Seal -->
            <div class="absolute inset-0 flex items-center justify-center pointer-events-none select-none opacity-[0.03]">
                <div class="text-center font-extrabold text-[120px] sm:text-[160px] tracking-widest text-slate-900 transform -rotate-12">
                    NIRMAAN
                </div>
            </div>

            <!-- Header Badge Line -->
            <div class="flex flex-wrap items-center justify-between gap-2 pb-5 border-b-2 border-blue-950/20 text-xs">
                <div class="flex items-center space-x-2">
                    <span class="inline-block w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="text-[11px] font-extrabold uppercase tracking-wider text-blue-950">
                        Government of India • Ministry of Corporate Affairs Recognized Institute
                    </span>
                </div>
                <div class="text-[11px] font-mono text-slate-500">
                    Original for Recipient (Student Copy)
                </div>
            </div>

            <!-- Academy & Invoice Header -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6 pt-6 pb-6 border-b border-slate-200 items-start">
                
                <!-- Academy Brand & Registered Info (7 Cols) -->
                <div class="md:col-span-7 space-y-2">
                    <div class="flex items-center space-x-3">
                        <div class="w-11 h-11 rounded-xl bg-blue-950 text-white font-extrabold flex items-center justify-center text-lg shadow-sm border border-blue-900">
                            N
                        </div>
                        <div>
                            <h1 class="text-xl font-extrabold tracking-tight text-slate-950 leading-tight">NIRMAAN DEFENCE ACADEMY</h1>
                            <p class="text-xs font-semibold text-blue-950">Centre for Excellence in Armed Forces &amp; Officer Training</p>
                        </div>
                    </div>
                    
                    <div class="text-[11px] text-slate-600 space-y-0.5 leading-relaxed pt-1">
                        <p class="font-medium">National Officer Training Campus, Plot 42, Civil Lines Cantt, Jaipur / Delhi NCR</p>
                        <p class="font-mono text-slate-500">GSTIN: <span class="text-slate-800 font-bold">07AABCN1234F1Z8</span> • SAC / HSN Code: <span class="text-slate-800 font-bold">999293</span></p>
                        <p class="text-slate-500">State: <span class="font-semibold text-slate-700">07 (Delhi)</span> • Email: <a href="mailto:accounts@nirmaan.edu.in" class="text-blue-950 font-semibold underline">accounts@nirmaan.edu.in</a> • Desk: <span class="font-semibold text-slate-700">+91 98765 43210</span></p>
                    </div>
                </div>

                <!-- Invoice Details & Live QR Code (5 Cols) -->
                <div class="md:col-span-5 flex flex-col sm:flex-row md:flex-row items-start sm:items-center md:items-start justify-end gap-4 text-right">
                    
                    <div class="space-y-1 text-left sm:text-right w-full sm:w-auto">
                        <div class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-emerald-100 text-emerald-800 border border-emerald-300">
                            ✓ {{ strtoupper($order->status) }} &amp; ENROLLED
                        </div>
                        <h2 class="text-xs font-black uppercase tracking-wider text-slate-700 pt-1">TAX INVOICE / RECEIPT</h2>
                        <div class="text-sm font-mono font-extrabold text-blue-950">{{ $order->order_number }}</div>
                        <div class="text-[11px] text-slate-500">
                            Date: <span class="font-semibold text-slate-800">{{ $order->paid_at ? $order->paid_at->format('d M, Y h:i A') : $order->created_at->format('d M, Y h:i A') }}</span>
                        </div>
                    </div>

                    <!-- Live Dynamic Verification QR Code -->
                    <div class="shrink-0 bg-white p-1.5 rounded-lg border border-slate-200 shadow-2xs text-center hidden sm:block">
                        <img src="{{ $qrApiUrl }}" alt="Verify Receipt QR" class="w-20 h-20 object-contain mx-auto">
                        <span class="text-[8px] font-extrabold text-slate-400 uppercase tracking-tighter block mt-0.5">Scan to Verify</span>
                    </div>

                </div>

            </div>

            <!-- Cadet & Transaction Meta Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 py-6 border-b border-slate-200 text-xs">
                
                <!-- Candidate Details -->
                <div class="space-y-2 bg-slate-50/70 p-4 rounded-xl border border-slate-200/80">
                    <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block">Billed To (Cadet / Candidate)</span>
                    <div>
                        <span class="text-sm font-extrabold text-slate-900 block">{{ $order->user->name }}</span>
                        <span class="text-slate-600 block">{{ $order->user->email }}</span>
                        <span class="text-slate-600 block">{{ $order->user->phone ?? 'Phone registered on record' }}</span>
                    </div>
                    <div class="pt-1.5 flex flex-wrap gap-2 text-[10px]">
                        <span class="px-2 py-0.5 rounded bg-blue-100 text-blue-950 font-bold border border-blue-200">
                            {{ $cadetId }}
                        </span>
                        <span class="px-2 py-0.5 rounded bg-emerald-50 text-emerald-800 font-bold border border-emerald-200">
                            Official Defence Cadet
                        </span>
                    </div>
                </div>

                <!-- Payment Audit Details -->
                <div class="space-y-1.5 bg-slate-50/70 p-4 rounded-xl border border-slate-200/80">
                    <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block mb-1">Transaction Audit Trail</span>
                    
                    <div class="flex justify-between">
                        <span class="text-slate-500">Payment ID:</span>
                        <span class="font-mono font-bold text-slate-900">{{ $order->payment_id ?? $order->payment_reference ?? 'PAY_VERIFIED' }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-slate-500">Gateway:</span>
                        <span class="font-bold text-blue-950 uppercase">{{ $order->payment_gateway === 'razorpay' ? 'Razorpay Secure Checkout' : strtoupper($order->payment_gateway) }}</span>
                    </div>

                    @if($order->razorpay_order_id)
                        <div class="flex justify-between">
                            <span class="text-slate-500">Razorpay Order:</span>
                            <span class="font-mono text-slate-700">{{ $order->razorpay_order_id }}</span>
                        </div>
                    @endif

                    @if($methodMeta)
                        <div class="flex justify-between">
                            <span class="text-slate-500">Channel / Ref:</span>
                            <span class="font-medium text-slate-800">{{ $methodMeta }}</span>
                        </div>
                    @endif

                    <div class="flex justify-between">
                        <span class="text-slate-500">Place of Supply:</span>
                        <span class="font-semibold text-slate-800">State Code 07 (Delhi) • {{ strtoupper($order->currency ?? 'INR') }}</span>
                    </div>
                </div>

            </div>

            <!-- Itemized Description Table -->
            <div class="py-6 border-b border-slate-200 overflow-x-auto">
                <table class="w-full text-left text-xs min-w-[500px]">
                    <thead>
                        <tr class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 border-b border-slate-200 pb-2">
                            <th class="pb-3 w-10 text-center">#</th>
                            <th class="pb-3">Course / Exam Module Description</th>
                            <th class="pb-3 text-center">Service SAC</th>
                            <th class="pb-3 text-center">Enrollment Type</th>
                            <th class="pb-3 text-right">Standard Fee</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr>
                            <td class="py-4 text-center font-bold text-slate-400">01</td>
                            <td class="py-4 pr-4">
                                @if($order->course)
                                    <div class="font-bold text-slate-900 text-sm leading-snug">{{ $order->course->title }}</div>
                                    <div class="text-[11px] text-slate-500 mt-0.5 leading-relaxed">
                                        Full Officer Curriculum • Recorded &amp; Live Batches • Downloadable DPP Notes &amp; SSB Guidance
                                    </div>
                                    <div class="mt-1 text-[10px] font-semibold text-blue-900">
                                        Batch Validity: Full Cycle / Lifetime Cadet Access
                                    </div>
                                @elseif($order->mockTest)
                                    <div class="font-bold text-slate-900 text-sm leading-snug">{{ $order->mockTest->title }}</div>
                                    <div class="text-[11px] text-slate-500 mt-0.5 leading-relaxed">
                                        Online CBT Test Series Engine • Real UPSC Exam Pattern • Instant AIR Analytics &amp; Solutions
                                    </div>
                                    <div class="mt-1 text-[10px] font-semibold text-blue-900">
                                        Validity: Complete Mock Series Access
                                    </div>
                                @else
                                    <div class="font-bold text-slate-900 text-sm leading-snug">Educational Course Subscription</div>
                                    <div class="text-[11px] text-slate-500 mt-0.5">Online Learning Portal Access</div>
                                @endif
                            </td>
                            <td class="py-4 text-center font-mono font-bold text-slate-700">
                                999293
                            </td>
                            <td class="py-4 text-center">
                                <span class="px-2.5 py-1 rounded-md text-[10px] font-extrabold bg-blue-50 text-blue-950 border border-blue-200">
                                    {{ $order->course ? 'ACADEMY COURSE' : 'CBT TEST SERIES' }}
                                </span>
                            </td>
                            <td class="py-4 text-right font-bold text-slate-900 font-mono text-sm">
                                ₹{{ number_format($originalPrice, 2) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Financial Breakdown & Totals -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6 py-6 border-b border-slate-200 items-start">
                
                <!-- Left: Amount in Words & Exemption Clause (7 Cols) -->
                <div class="md:col-span-7 space-y-3">
                    <div class="bg-blue-50/50 p-3.5 rounded-xl border border-blue-100 space-y-1">
                        <span class="text-[10px] font-extrabold uppercase tracking-wider text-blue-900 block">Amount in Words</span>
                        <p class="text-xs font-bold text-slate-900 italic">
                            {{ $amountInWords }}
                        </p>
                    </div>

                    <div class="text-[11px] text-slate-500 space-y-1 leading-relaxed">
                        <p class="font-bold text-slate-700">Tax Exemption Classification:</p>
                        <p>Services provided by an educational institution to its students, faculty, and staff are exempted from GST under Notification No. 12/2017 - Central Tax (Rate), Entry 66, Heading 9992.</p>
                    </div>
                </div>

                <!-- Right: Calculations Summary (5 Cols) -->
                <div class="md:col-span-5 space-y-2 text-xs">
                    
                    <div class="flex justify-between text-slate-600">
                        <span>Standard Gross Fee:</span>
                        <span class="font-mono font-bold">₹{{ number_format($originalPrice, 2) }}</span>
                    </div>

                    @if($couponCode && $couponDiscount > 0)
                        <div class="flex justify-between items-center text-emerald-700 font-bold bg-emerald-50 px-2 py-1 rounded border border-emerald-200">
                            <span>Scholarship Promo ({{ $couponCode }}):</span>
                            <span class="font-mono">-₹{{ number_format($couponDiscount, 2) }}</span>
                        </div>
                    @elseif($originalPrice > (float) $order->amount)
                        <div class="flex justify-between items-center text-emerald-700 font-bold bg-emerald-50 px-2 py-1 rounded border border-emerald-200">
                            <span>Academy Special Concession:</span>
                            <span class="font-mono">-₹{{ number_format($originalPrice - (float) $order->amount, 2) }}</span>
                        </div>
                    @endif

                    <div class="flex justify-between text-slate-600">
                        <span>CGST (0.00%):</span>
                        <span class="font-mono text-emerald-700 font-semibold">₹0.00</span>
                    </div>

                    <div class="flex justify-between text-slate-600">
                        <span>SGST / UTGST (0.00%):</span>
                        <span class="font-mono text-emerald-700 font-semibold">₹0.00</span>
                    </div>

                    <div class="flex justify-between text-slate-600">
                        <span>IGST (0.00%):</span>
                        <span class="font-mono text-emerald-700 font-semibold">₹0.00</span>
                    </div>

                    <!-- Net Paid Box -->
                    <div class="pt-2 border-t-2 border-slate-200 flex justify-between items-baseline">
                        <span class="text-xs font-black uppercase tracking-wider text-slate-900">Total Fee Paid:</span>
                        <span class="text-xl font-extrabold text-blue-950 font-mono">
                            ₹{{ number_format($order->amount, 2) }}
                        </span>
                    </div>

                </div>

            </div>

            <!-- Signatory & Terms Section -->
            <div class="pt-6 grid grid-cols-1 md:grid-cols-12 gap-6 items-end">
                
                <!-- Terms & Instructions (8 Cols) -->
                <div class="md:col-span-8 text-[11px] text-slate-500 space-y-1.5 leading-relaxed">
                    <p class="font-extrabold text-slate-800 uppercase tracking-wider text-[10px]">Official Terms &amp; Student Notice:</p>
                    <p>1. This electronic receipt serves as official proof of fee payment and enrollment in Nirmaan Defence Academy.</p>
                    <p>2. Course access, CBT mock test credentials, and study materials are linked automatically to your verified registered email address (<span class="font-semibold text-slate-700">{{ $order->user->email }}</span>).</p>
                    <p>3. This document is electronically generated under Section 31 of the Central Goods and Services Tax Act, 2017 and is valid without physical signature or seal stamp.</p>
                </div>

                <!-- Official Signature Seal (4 Cols) -->
                <div class="md:col-span-4 text-center sm:text-right space-y-2">
                    <div class="inline-block p-3 rounded-xl border-2 border-dashed border-slate-300 text-center bg-slate-50/50">
                        <div class="text-[9px] font-black uppercase tracking-wider text-blue-950">NIRMAAN ACADEMY</div>
                        <div class="w-24 h-8 mx-auto my-1 flex items-center justify-center font-serif italic text-sm text-slate-600">
                            Digitally Verified
                        </div>
                        <div class="text-[9px] font-bold text-slate-700 border-t border-slate-200 pt-1">
                            Accounts &amp; Admissions Officer
                        </div>
                    </div>
                </div>

            </div>

        </div>

        <!-- Help / Support Footer (Hidden in Print) -->
        <div class="text-center text-xs text-slate-500 py-2 print:hidden">
            Need admission assistance or invoice rectification? Contact our Accounts Wing at 
            <a href="mailto:accounts@nirmaan.edu.in" class="font-bold text-blue-950 underline">accounts@nirmaan.edu.in</a> 
            or call <span class="font-bold text-slate-800">+91 98765 43210</span>.
        </div>

    </div>
</div>
@endsection
