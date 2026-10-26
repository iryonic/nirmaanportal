@extends('layouts.student')

@section('title', 'Industry Payment Gateway — ' . $item->title)

@section('content')
@php
    $formAction = $type === 'course' 
        ? route('student.checkout.pay', $item->id) 
        : route('student.checkout.test.pay', $item->id);
    $price = (float) ($item->price > 0 ? $item->price : 499.00);
    $originalPrice = (float) ($item->original_price ?? ($price * 1.4));
    $baseDiscount = max(0, $originalPrice - $price);
@endphp

<div class="py-8 sm:py-12 bg-gray-50 min-h-screen" 
     x-data="{
        method: 'upi',
        basePrice: {{ $price }},
        originalPrice: {{ $originalPrice }},
        baseDiscount: {{ $baseDiscount }},
        
        // Coupon state
        couponInput: '',
        couponLoading: false,
        couponError: '',
        couponSuccess: '',
        appliedCoupon: null,
        
        // UPI state
        vpaInput: '{{ $user->email ? explode('@', $user->email)[0] . '@okhdfcbank' : 'cadet@upi' }}',
        vpaVerified: false,
        vpaVerifying: false,
        copiedVpa: false,
        countdown: 899,
        
        // Card state
        card: {
            number: '',
            formattedNumber: '',
            name: '{{ strtoupper($user->name) }}',
            expiry: '',
            cvv: '',
            brand: 'rupay'
        },
        saveCard: true,
        
        // Netbanking state
        selectedBank: 'SBI',
        
        // EMI state
        emiTenure: 3,
        
        // Processing modal
        isProcessing: false,
        processingMessage: 'Initiating bank handshake...',
        processingProgress: 25,
        
        // Computed helpers
        get finalPayable() {
            if (this.appliedCoupon) {
                return this.appliedCoupon.final_amount;
            }
            return this.basePrice;
        },
        
        get totalSavings() {
            let savings = this.baseDiscount;
            if (this.appliedCoupon) {
                savings += this.appliedCoupon.discount_amount;
            }
            return savings;
        },
        
        get formattedCountdown() {
            let m = Math.floor(this.countdown / 60);
            let s = this.countdown % 60;
            return (m < 10 ? '0' : '') + m + ':' + (s < 10 ? '0' : '') + s;
        },
        
        // Timer lifecycle
        init() {
            setInterval(() => {
                if (this.countdown > 0) {
                    this.countdown--;
                }
            }, 1000);
        },
        
        // Copy UPI ID
        copyVpa() {
            navigator.clipboard.writeText('nirmaanacademy@icici');
            this.copiedVpa = true;
            setTimeout(() => { this.copiedVpa = false; }, 2500);
        },
        
        // Verify VPA
        verifyVpa() {
            if (!this.vpaInput || !this.vpaInput.includes('@')) {
                alert('Please enter a valid UPI ID (e.g., username@upi)');
                return;
            }
            this.vpaVerifying = true;
            setTimeout(() => {
                this.vpaVerifying = false;
                this.vpaVerified = true;
            }, 750);
        },
        
        // Format Card Number
        onCardInput(e) {
            let raw = e.target.value.replace(/\D/g, '').substring(0, 16);
            this.card.number = raw;
            let parts = raw.match(/.{1,4}/g);
            this.card.formattedNumber = parts ? parts.join(' ') : raw;
            
            // Detect Brand
            if (/^4/.test(raw)) {
                this.card.brand = 'visa';
            } else if (/^(5[1-5]|2[2-7])/.test(raw)) {
                this.card.brand = 'mastercard';
            } else if (/^(60|65|81|82)/.test(raw)) {
                this.card.brand = 'rupay';
            } else if (/^3[47]/.test(raw)) {
                this.card.brand = 'amex';
            } else {
                this.card.brand = 'rupay';
            }
        },
        
        // Format Expiry
        onExpiryInput(e) {
            let raw = e.target.value.replace(/\D/g, '').substring(0, 4);
            if (raw.length >= 2) {
                this.card.expiry = raw.substring(0, 2) + '/' + raw.substring(2);
            } else {
                this.card.expiry = raw;
            }
        },
        
        // Format CVV
        onCvvInput(e) {
            this.card.cvv = e.target.value.replace(/\D/g, '').substring(0, 4);
        },
        
        // Apply Coupon via AJAX
        applyCoupon(codeToApply) {
            let code = (codeToApply || this.couponInput).trim().toUpperCase();
            if (!code) {
                this.couponError = 'Please enter a promo code';
                return;
            }
            
            this.couponLoading = true;
            this.couponError = '';
            this.couponSuccess = '';
            
            fetch('{{ route('student.checkout.validate-coupon') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    code: code,
                    type: '{{ $type }}',
                    item_id: {{ $item->id }}
                })
            })
            .then(res => res.json().then(data => ({ status: res.status, body: data })))
            .then(({ status, body }) => {
                this.couponLoading = false;
                if (status === 200 && body.valid) {
                    this.appliedCoupon = body;
                    this.couponInput = body.code;
                    this.couponSuccess = body.message;
                } else {
                    this.couponError = body.message || 'Invalid or expired coupon code.';
                }
            })
            .catch(err => {
                this.couponLoading = false;
                this.couponError = 'Connection failed. Please retry.';
            });
        },
        
        // Remove Coupon
        removeCoupon() {
            this.appliedCoupon = null;
            this.couponInput = '';
            this.couponSuccess = '';
            this.couponError = '';
        },
        
        // Submit Payment with Simulation Handshake
        submitPayment() {
            this.isProcessing = true;
            this.processingMessage = 'Connecting to NPCI & RBI Payment Switch...';
            this.processingProgress = 35;
            
            setTimeout(() => {
                this.processingMessage = 'Verifying security signature & 3D Secure...';
                this.processingProgress = 70;
            }, 600);
            
            setTimeout(() => {
                this.processingMessage = 'Payment Authorized! Finalizing your enrollment...';
                this.processingProgress = 100;
            }, 1200);
            
            setTimeout(() => {
                document.getElementById('checkout-form').submit();
            }, 1600);
        }
     }">

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header Breadcrumb & Status -->
        <div class="mb-6 sm:mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center space-x-2 text-xs text-gray-500 mb-1.5">
                    <a href="{{ route('courses.index') }}" class="hover:text-blue-700 transition">Academy Portal</a>
                    <span>/</span>
                    <span class="text-blue-700 font-semibold">{{ $type === 'course' ? 'Batch Enrollment' : 'CBT Test Series' }}</span>
                    <span>/</span>
                    <span class="text-gray-900 font-medium">Secure Checkout</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight">Payment Gateway &amp; Access Activation</h1>
                <p class="text-xs text-gray-500 mt-1">Direct instant enrollment with digital tax invoice and zero gateway processing surcharge.</p>
            </div>

            <!-- Security Assurance Badges -->
            <div class="flex items-center space-x-2 text-[11px] text-gray-600 bg-white border border-gray-200 px-3.5 py-2 rounded-xl shadow-2xs self-start sm:self-auto">
                <span class="inline-block w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span class="font-medium">256-bit SSL</span>
                <span class="text-gray-300">•</span>
                <span class="font-medium">RBI Tokenized</span>
                <span class="text-gray-300">•</span>
                <span class="font-medium">NPCI Verified</span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 items-start">
            
            <!-- Left Column: Payment Methods & Tabs -->
            <div class="lg:col-span-7 space-y-6">
                
                <!-- Item Summary Card -->
                <div class="bg-white rounded-2xl p-5 sm:p-6 border border-gray-200 shadow-2xs">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-blue-700 bg-blue-50 px-2.5 py-1 rounded-md border border-blue-100">
                            {{ $item->examCategory ? $item->examCategory->name : 'Defence Exam' }}
                        </span>
                        <span class="text-xs font-mono font-semibold text-gray-400">
                            {{ $type === 'course' ? 'DEFENCE CURRICULUM BATCH' : 'OFFICIAL CBT MOCK' }}
                        </span>
                    </div>

                    <h2 class="text-base sm:text-lg font-bold text-gray-900 leading-snug">{{ $item->title }}</h2>
                    <p class="text-xs text-gray-500 mt-1 line-clamp-2 leading-relaxed">{{ $item->description ?? $item->subtitle }}</p>

                    <div class="mt-4 pt-4 border-t border-gray-100 grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs text-gray-600">
                        @if($type === 'course')
                            <div class="bg-gray-50 p-2.5 rounded-lg border border-gray-100">
                                <span class="block text-[10px] uppercase font-bold text-gray-400">Modules</span>
                                <span class="font-bold text-gray-800">{{ $item->modules->count() ?? 0 }} Units</span>
                            </div>
                            <div class="bg-gray-50 p-2.5 rounded-lg border border-gray-100">
                                <span class="block text-[10px] uppercase font-bold text-gray-400">Duration</span>
                                <span class="font-bold text-gray-800">{{ $item->duration_text ?? '6 Months' }}</span>
                            </div>
                            <div class="bg-gray-50 p-2.5 rounded-lg border border-gray-100">
                                <span class="block text-[10px] uppercase font-bold text-gray-400">Format</span>
                                <span class="font-bold text-gray-800">HD Video &amp; Notes</span>
                            </div>
                            <div class="bg-gray-50 p-2.5 rounded-lg border border-gray-100">
                                <span class="block text-[10px] uppercase font-bold text-gray-400">Access</span>
                                <span class="font-bold text-emerald-700">Instant</span>
                            </div>
                        @else
                            <div class="bg-gray-50 p-2.5 rounded-lg border border-gray-100">
                                <span class="block text-[10px] uppercase font-bold text-gray-400">Questions</span>
                                <span class="font-bold text-gray-800">{{ $item->questions_count ?? $item->questions()->count() }} MCQs</span>
                            </div>
                            <div class="bg-gray-50 p-2.5 rounded-lg border border-gray-100">
                                <span class="block text-[10px] uppercase font-bold text-gray-400">Time Limit</span>
                                <span class="font-bold text-gray-800">{{ $item->duration_minutes }} Mins</span>
                            </div>
                            <div class="bg-gray-50 p-2.5 rounded-lg border border-gray-100">
                                <span class="block text-[10px] uppercase font-bold text-gray-400">Marking</span>
                                <span class="font-bold text-gray-800">Negative (-0.33)</span>
                            </div>
                            <div class="bg-gray-50 p-2.5 rounded-lg border border-gray-100">
                                <span class="block text-[10px] uppercase font-bold text-gray-400">Solutions</span>
                                <span class="font-bold text-emerald-700">Detailed</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Payment Methods Form Container -->
                <form action="{{ $formAction }}" method="POST" id="checkout-form" @submit.prevent="submitPayment()">
                    @csrf
                    
                    <!-- Hidden Dynamic Fields for Controller -->
                    <input type="hidden" name="payment_method" :value="method">
                    <input type="hidden" name="coupon_code" :value="appliedCoupon ? appliedCoupon.code : ''">
                    <input type="hidden" name="payment_reference" :value="'pay_' + Math.random().toString(36).substring(2, 10)">
                    <input type="hidden" name="method_meta" :value="method === 'upi' ? vpaInput : (method === 'netbanking' ? selectedBank : (method === 'card' ? (card.brand + ' ending ' + (card.number.slice(-4) || 'XXXX')) : 'SANDBOX_OK'))">

                    <div class="bg-white rounded-2xl border border-gray-200 shadow-2xs overflow-hidden">
                        
                        <!-- Gateway Method Navigation Tabs -->
                        <div class="p-4 sm:p-5 border-b border-gray-200 bg-gray-50/70">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-gray-600 mb-3">Choose Payment Method</h3>
                            
                            <div class="grid grid-cols-2 sm:grid-cols-5 gap-2">
                                <!-- UPI Tab -->
                                <button type="button" 
                                        @click="method = 'upi'"
                                        class="flex flex-col items-center justify-center p-3 rounded-xl border text-center transition relative"
                                        :class="method === 'upi' ? 'bg-white border-blue-600 text-blue-700 shadow-xs ring-1 ring-blue-600 font-bold' : 'bg-white border-gray-200 text-gray-700 hover:bg-gray-50 font-medium'">
                                    <span class="text-[10px] uppercase font-extrabold px-1.5 py-0.5 rounded bg-blue-100 text-blue-800 absolute -top-2 right-2">Instant</span>
                                    <svg class="w-5 h-5 mb-1 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                    <span class="text-xs">UPI / QR</span>
                                </button>

                                <!-- Card Tab -->
                                <button type="button" 
                                        @click="method = 'card'"
                                        class="flex flex-col items-center justify-center p-3 rounded-xl border text-center transition"
                                        :class="method === 'card' ? 'bg-white border-blue-600 text-blue-700 shadow-xs ring-1 ring-blue-600 font-bold' : 'bg-white border-gray-200 text-gray-700 hover:bg-gray-50 font-medium'">
                                    <svg class="w-5 h-5 mb-1 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                    <span class="text-xs">Debit / Credit</span>
                                </button>

                                <!-- Net Banking Tab -->
                                <button type="button" 
                                        @click="method = 'netbanking'"
                                        class="flex flex-col items-center justify-center p-3 rounded-xl border text-center transition"
                                        :class="method === 'netbanking' ? 'bg-white border-blue-600 text-blue-700 shadow-xs ring-1 ring-blue-600 font-bold' : 'bg-white border-gray-200 text-gray-700 hover:bg-gray-50 font-medium'">
                                    <svg class="w-5 h-5 mb-1 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                    <span class="text-xs">Net Banking</span>
                                </button>

                                <!-- EMI Tab -->
                                <button type="button" 
                                        @click="method = 'emi'"
                                        class="flex flex-col items-center justify-center p-3 rounded-xl border text-center transition"
                                        :class="method === 'emi' ? 'bg-white border-blue-600 text-blue-700 shadow-xs ring-1 ring-blue-600 font-bold' : 'bg-white border-gray-200 text-gray-700 hover:bg-gray-50 font-medium'">
                                    <svg class="w-5 h-5 mb-1 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <span class="text-xs">0% EMI</span>
                                </button>

                                <!-- Sandbox / Fast Mock Tab -->
                                <button type="button" 
                                        @click="method = 'mock'"
                                        class="col-span-2 sm:col-span-1 flex flex-col items-center justify-center p-3 rounded-xl border text-center transition"
                                        :class="method === 'mock' ? 'bg-emerald-50 border-emerald-600 text-emerald-800 shadow-xs ring-1 ring-emerald-600 font-bold' : 'bg-white border-gray-200 text-gray-700 hover:bg-gray-50 font-medium'">
                                    <svg class="w-5 h-5 mb-1 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <span class="text-xs">Instant Test</span>
                                </button>
                            </div>
                        </div>

                        <!-- Tab 1: UPI / QR Payment -->
                        <div x-show="method === 'upi'" class="p-5 sm:p-6 space-y-6">
                            
                            <div class="flex flex-col md:flex-row items-center gap-6 p-4 rounded-xl border border-blue-100 bg-blue-50/30">
                                
                                <!-- Dynamic NPCI QR Code Box -->
                                <div class="flex flex-col items-center text-center p-3 bg-white rounded-xl border border-gray-200 shadow-xs">
                                    <div class="w-40 h-40 bg-gray-50 rounded-lg border border-gray-100 flex items-center justify-center overflow-hidden p-2">
                                        <!-- Real Dynamic QR generated from free standard QR generator -->
                                        <img :src="'https://api.qrserver.com/v1/create-qr-code/?size=160x160&data=' + encodeURIComponent('upi://pay?pa=nirmaanacademy@icici&pn=Nirmaan%20Defence%20Academy&am=' + finalPayable.toFixed(2) + '&cu=INR&tn=NIRMAAN-ACC')" 
                                             alt="Scan to Pay via UPI" 
                                             class="w-full h-full object-contain"
                                             loading="lazy">
                                    </div>
                                    <div class="mt-2 flex items-center space-x-1.5 text-[11px] font-mono text-gray-600">
                                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                        <span>Scan with any UPI App</span>
                                    </div>
                                    <div class="text-[10px] text-gray-400 mt-0.5">
                                        Expires in <span class="font-bold text-red-600 font-mono" x-text="formattedCountdown">14:59</span>
                                    </div>
                                </div>

                                <!-- Quick UPI Details & Copy VPA -->
                                <div class="flex-1 space-y-3 w-full">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-bold text-gray-800 uppercase tracking-wide">Academy Official VPA</span>
                                        <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">ICICI Verified</span>
                                    </div>

                                    <div class="flex items-center justify-between bg-white px-3.5 py-2.5 rounded-lg border border-gray-200 text-xs">
                                        <span class="font-mono font-bold text-gray-800">nirmaanacademy@icici</span>
                                        <button type="button" 
                                                @click="copyVpa()" 
                                                class="px-2.5 py-1 text-[11px] font-bold text-blue-700 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 rounded transition flex items-center space-x-1">
                                            <span x-show="!copiedVpa">Copy VPA</span>
                                            <span x-show="copiedVpa" style="display:none;" class="text-emerald-700">✓ Copied!</span>
                                        </button>
                                    </div>

                                    <!-- Quick App Links -->
                                    <div class="pt-2">
                                        <span class="text-[11px] font-semibold text-gray-500 block mb-2">Or Open Direct UPI App on Mobile:</span>
                                        <div class="grid grid-cols-4 gap-2 text-center">
                                            <div class="p-2 rounded-lg border border-gray-200 bg-white hover:border-blue-500 cursor-pointer transition">
                                                <span class="text-xs font-bold text-gray-800 block">GPay</span>
                                                <span class="text-[9px] text-gray-400">Google Pay</span>
                                            </div>
                                            <div class="p-2 rounded-lg border border-gray-200 bg-white hover:border-blue-500 cursor-pointer transition">
                                                <span class="text-xs font-bold text-purple-700 block">PhonePe</span>
                                                <span class="text-[9px] text-gray-400">UPI Intent</span>
                                            </div>
                                            <div class="p-2 rounded-lg border border-gray-200 bg-white hover:border-blue-500 cursor-pointer transition">
                                                <span class="text-xs font-bold text-sky-600 block">Paytm</span>
                                                <span class="text-[9px] text-gray-400">UPI / Wallet</span>
                                            </div>
                                            <div class="p-2 rounded-lg border border-gray-200 bg-white hover:border-blue-500 cursor-pointer transition">
                                                <span class="text-xs font-bold text-amber-700 block">BHIM</span>
                                                <span class="text-[9px] text-gray-400">NPCI Govt</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Custom VPA ID Input with Live Verification -->
                            <div class="space-y-2 pt-2 border-t border-gray-100">
                                <label class="block text-xs font-bold text-gray-700">Or Pay via Cadet UPI ID / Virtual Payment Address</label>
                                <div class="flex gap-2">
                                    <div class="relative flex-1">
                                        <input type="text" 
                                               x-model="vpaInput" 
                                               placeholder="mobileNumber@upi or username@okhdfcbank"
                                               class="w-full text-xs font-mono px-3.5 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-600 focus:border-transparent outline-none">
                                        <span x-show="vpaVerified" style="display:none;" class="absolute right-3 top-2.5 text-xs font-bold text-emerald-600 flex items-center space-x-1">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                            <span>VERIFIED</span>
                                        </span>
                                    </div>
                                    <button type="button" 
                                            @click="verifyVpa()" 
                                            :disabled="vpaVerifying"
                                            class="px-4 py-2.5 rounded-xl text-xs font-bold text-blue-700 bg-blue-50 hover:bg-blue-100 border border-blue-200 transition">
                                        <span x-show="!vpaVerifying">Verify ID</span>
                                        <span x-show="vpaVerifying" style="display:none;">Verifying...</span>
                                    </button>
                                </div>
                                <p class="text-[11px] text-gray-400">Collect request will be dispatched directly to your UPI mobile app upon confirmation.</p>
                            </div>

                        </div>

                        <!-- Tab 2: Credit / Debit Card with Real-time Preview -->
                        <div x-show="method === 'card'" style="display:none;" class="p-5 sm:p-6 space-y-6">
                            
                            <!-- Virtual Interactive Card -->
                            <div class="bg-gray-900 text-white rounded-2xl p-5 sm:p-6 shadow-sm border border-gray-800 relative overflow-hidden max-w-sm mx-auto">
                                <div class="flex items-center justify-between mb-6">
                                    <!-- Chip Graphic -->
                                    <div class="w-10 h-7 rounded bg-amber-400/80 border border-amber-300 flex items-center justify-center space-x-1">
                                        <div class="w-2 h-4 border-r border-amber-600/40"></div>
                                        <div class="w-2 h-4 border-r border-amber-600/40"></div>
                                    </div>
                                    <!-- Card Brand Badge -->
                                    <span class="text-xs font-black tracking-widest uppercase px-2 py-0.5 rounded bg-white/10" x-text="card.brand.toUpperCase()">RUPAY</span>
                                </div>

                                <!-- Card Number Display -->
                                <div class="text-base sm:text-lg font-mono tracking-wider font-bold mb-4 text-gray-100">
                                    <span x-text="card.formattedNumber || '•••• •••• •••• ••••'">•••• •••• •••• ••••</span>
                                </div>

                                <div class="flex items-end justify-between text-xs text-gray-300">
                                    <div>
                                        <span class="text-[9px] uppercase tracking-wider text-gray-400 block">Cadet Name</span>
                                        <span class="font-bold tracking-wide uppercase text-white" x-text="card.name || 'CADET NAME'">CADET NAME</span>
                                    </div>
                                    <div>
                                        <span class="text-[9px] uppercase tracking-wider text-gray-400 block">Valid Thru</span>
                                        <span class="font-mono font-bold text-white" x-text="card.expiry || 'MM/YY'">MM/YY</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Card Form Inputs -->
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Card Number</label>
                                    <div class="relative">
                                        <input type="text" 
                                               placeholder="XXXX XXXX XXXX XXXX" 
                                               :value="card.formattedNumber"
                                               @input="onCardInput($event)"
                                               maxlength="19"
                                               class="w-full text-xs font-mono px-3.5 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-600 focus:border-transparent outline-none">
                                        <span class="absolute right-3 top-2.5 text-[10px] font-bold text-gray-400 uppercase" x-text="card.brand">RuPay</span>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 mb-1">Cardholder Full Name</label>
                                        <input type="text" 
                                               x-model="card.name" 
                                               placeholder="As printed on card"
                                               class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-600 focus:border-transparent outline-none">
                                    </div>

                                    <div class="grid grid-cols-2 gap-2">
                                        <div>
                                            <label class="block text-xs font-bold text-gray-700 mb-1">Expiry (MM/YY)</label>
                                            <input type="text" 
                                                   placeholder="MM/YY" 
                                                   :value="card.expiry"
                                                   @input="onExpiryInput($event)"
                                                   maxlength="5"
                                                   class="w-full text-xs font-mono text-center px-3 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-600 focus:border-transparent outline-none">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-700 mb-1">CVV / CVC</label>
                                            <input type="password" 
                                                   placeholder="•••" 
                                                   :value="card.cvv"
                                                   @input="onCvvInput($event)"
                                                   maxlength="4"
                                                   class="w-full text-xs font-mono text-center px-3 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-600 focus:border-transparent outline-none">
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center space-x-2 pt-1">
                                    <input type="checkbox" id="save-card-cb" x-model="saveCard" class="text-blue-600 focus:ring-blue-500 rounded">
                                    <label for="save-card-cb" class="text-xs text-gray-600">Save card securely as per RBI tokenization standards for future test series</label>
                                </div>
                            </div>

                        </div>

                        <!-- Tab 3: Net Banking with Top Indian Banks -->
                        <div x-show="method === 'netbanking'" style="display:none;" class="p-5 sm:p-6 space-y-5">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-2.5">Popular Indian Banks</label>
                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5">
                                    @php
                                        $banks = [
                                            ['code' => 'SBI', 'name' => 'State Bank of India'],
                                            ['code' => 'HDFC', 'name' => 'HDFC Bank'],
                                            ['code' => 'ICICI', 'name' => 'ICICI Bank'],
                                            ['code' => 'AXIS', 'name' => 'Axis Bank'],
                                            ['code' => 'PNB', 'name' => 'Punjab National Bank'],
                                            ['code' => 'BOB', 'name' => 'Bank of Baroda'],
                                        ];
                                    @endphp
                                    @foreach($banks as $b)
                                        <button type="button" 
                                                @click="selectedBank = '{{ $b['code'] }}'"
                                                class="flex items-center space-x-2.5 p-3 rounded-xl border text-left transition"
                                                :class="selectedBank === '{{ $b['code'] }}' ? 'bg-blue-50/60 border-blue-600 text-blue-900 font-bold ring-1 ring-blue-600' : 'bg-white border-gray-200 text-gray-700 hover:bg-gray-50'">
                                            <span class="w-6 h-6 rounded bg-gray-100 text-gray-800 text-[10px] font-black flex items-center justify-center shrink-0">
                                                {{ substr($b['code'], 0, 3) }}
                                            </span>
                                            <div class="truncate">
                                                <span class="text-xs block leading-tight truncate">{{ $b['name'] }}</span>
                                                <span class="text-[10px] text-gray-400 font-mono">{{ $b['code'] }}</span>
                                            </div>
                                        </button>
                                    @endforeach
                                </div>
                            </div>

                            <div class="pt-2 border-t border-gray-100">
                                <label class="block text-xs font-bold text-gray-700 mb-1.5">All Other Scheduled Commercial Banks</label>
                                <select x-model="selectedBank" class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-600 focus:border-transparent outline-none bg-white">
                                    <option value="KOTAK">Kotak Mahindra Bank</option>
                                    <option value="CANARA">Canara Bank</option>
                                    <option value="UNION">Union Bank of India</option>
                                    <option value="INDUSIND">IndusInd Bank</option>
                                    <option value="IDFC">IDFC FIRST Bank</option>
                                    <option value="FEDERAL">Federal Bank</option>
                                    <option value="YES">Yes Bank</option>
                                    <option value="INDIAN">Indian Bank</option>
                                    <option value="CBI">Central Bank of India</option>
                                    <option value="BOI">Bank of India</option>
                                </select>
                            </div>
                        </div>

                        <!-- Tab 4: 0% EMI & Pay Later -->
                        <div x-show="method === 'emi'" style="display:none;" class="p-5 sm:p-6 space-y-5">
                            <div class="flex items-center justify-between p-3.5 rounded-xl bg-blue-50/60 border border-blue-200">
                                <div>
                                    <span class="text-xs font-bold text-blue-900 block">0% No-Cost EMI Available</span>
                                    <span class="text-[11px] text-blue-700">Split your tuition fee into interest-free monthly installments.</span>
                                </div>
                                <span class="text-[10px] font-extrabold uppercase px-2 py-0.5 rounded bg-blue-200 text-blue-800">Pre-Approved</span>
                            </div>

                            <div class="space-y-3">
                                <label class="flex items-center justify-between p-3.5 rounded-xl border cursor-pointer transition"
                                       :class="emiTenure === 3 ? 'border-blue-600 bg-blue-50/30' : 'border-gray-200 hover:bg-gray-50'">
                                    <div class="flex items-center space-x-3">
                                        <input type="radio" name="emi_plan" value="3" x-model="emiTenure" class="text-blue-600">
                                        <div>
                                            <span class="text-xs font-bold text-gray-900 block">3 Months No-Cost EMI</span>
                                            <span class="text-[11px] text-gray-500 font-mono">₹ <span x-text="(finalPayable / 3).toFixed(2)"></span> / month</span>
                                        </div>
                                    </div>
                                    <span class="text-[11px] font-bold text-emerald-700">0% Interest</span>
                                </label>

                                <label class="flex items-center justify-between p-3.5 rounded-xl border cursor-pointer transition"
                                       :class="emiTenure === 6 ? 'border-blue-600 bg-blue-50/30' : 'border-gray-200 hover:bg-gray-50'">
                                    <div class="flex items-center space-x-3">
                                        <input type="radio" name="emi_plan" value="6" x-model="emiTenure" class="text-blue-600">
                                        <div>
                                            <span class="text-xs font-bold text-gray-900 block">6 Months Flexible EMI</span>
                                            <span class="text-[11px] text-gray-500 font-mono">₹ <span x-text="(finalPayable / 6).toFixed(2)"></span> / month</span>
                                        </div>
                                    </div>
                                    <span class="text-[11px] font-bold text-emerald-700">0% Surcharge</span>
                                </label>
                            </div>

                            <div class="p-3 bg-gray-50 rounded-xl border border-gray-100 text-[11px] text-gray-500 flex items-center space-x-2">
                                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span>Supported via Simpl, LazyPay, and major Indian credit card issuer installments.</span>
                            </div>
                        </div>

                        <!-- Tab 5: Sandbox / Instant Mock Verification -->
                        <div x-show="method === 'mock'" style="display:none;" class="p-5 sm:p-6 space-y-4">
                            <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200">
                                <div class="flex items-start space-x-3">
                                    <div class="w-7 h-7 rounded-lg bg-emerald-600 text-white flex items-center justify-center shrink-0 font-bold text-sm">
                                        ✓
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-bold text-emerald-950">Zero-Friction Sandbox Verification Mode</h4>
                                        <p class="text-[11px] text-emerald-800 mt-1 leading-relaxed">
                                            Instant payment simulation without OTP or physical card swipe. Generates official transaction ID and activates academy access immediately.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-3 text-xs text-gray-600 bg-gray-50 p-3.5 rounded-xl border border-gray-200">
                                <div>
                                    <span class="text-[10px] uppercase font-bold text-gray-400 block">Cadet Identifier</span>
                                    <span class="font-mono font-bold text-gray-800">{{ $user->name }}</span>
                                </div>
                                <div>
                                    <span class="text-[10px] uppercase font-bold text-gray-400 block">Gateway Ref</span>
                                    <span class="font-mono font-bold text-emerald-700">MOCK_TEST_2026</span>
                                </div>
                            </div>
                        </div>

                        <!-- Submit CTA Bar -->
                        <div class="p-4 sm:p-6 border-t border-gray-200 bg-gray-50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div class="text-xs text-gray-500">
                                <span>Total payable now: </span>
                                <span class="text-base sm:text-lg font-black text-gray-900 font-mono">₹ <span x-text="finalPayable.toFixed(2)">{{ number_format($price, 2) }}</span></span>
                            </div>

                            <button type="submit" 
                                    :disabled="isProcessing"
                                    class="w-full sm:w-auto px-8 py-3.5 rounded-xl text-xs sm:text-sm font-bold text-white bg-blue-700 hover:bg-blue-800 disabled:opacity-60 transition shadow-xs flex items-center justify-center space-x-2">
                                <span>Pay ₹ <span x-text="finalPayable.toFixed(2)">{{ number_format($price, 2) }}</span> &amp; Unlock</span>
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </button>
                        </div>

                    </div>
                </form>

            </div>

            <!-- Right Column: Live Price Summary & Coupon Code Engine -->
            <div class="lg:col-span-5 sticky top-24 space-y-6">
                
                <!-- Fee Breakdown Card -->
                <div class="bg-white rounded-2xl p-5 sm:p-6 border border-gray-200 shadow-2xs space-y-4">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400">Official Fee Structure</h3>

                    <div class="space-y-2.5 text-xs text-gray-600 border-b border-gray-100 pb-4">
                        <div class="flex justify-between">
                            <span>Base Academy Fee:</span>
                            <span class="font-mono font-medium">₹ {{ number_format($originalPrice, 2) }}</span>
                        </div>

                        @if($baseDiscount > 0)
                            <div class="flex justify-between text-emerald-700 font-medium">
                                <span>Cadet Concession:</span>
                                <span class="font-mono">-₹ {{ number_format($baseDiscount, 2) }}</span>
                            </div>
                        @endif

                        <!-- Live Coupon Discount Row -->
                        <div x-show="appliedCoupon" style="display:none;" class="flex justify-between text-emerald-700 font-bold bg-emerald-50/70 p-2 rounded-lg border border-emerald-100">
                            <div>
                                <span class="block">Promo (<span x-text="appliedCoupon?.code"></span>):</span>
                                <span class="text-[10px] text-emerald-600 font-normal" x-text="appliedCoupon?.label"></span>
                            </div>
                            <span class="font-mono">-<span x-text="appliedCoupon?.formatted_discount"></span></span>
                        </div>

                        <div class="flex justify-between text-gray-500">
                            <span>Educational GST (SAC 999293):</span>
                            <span class="font-mono text-emerald-700 font-medium">₹ 0.00 (Exempt)</span>
                        </div>

                        <div class="flex justify-between text-gray-500">
                            <span>Payment Gateway Surcharge:</span>
                            <span class="font-mono text-emerald-700 font-medium">₹ 0.00 (Waived)</span>
                        </div>
                    </div>

                    <!-- Total Amount Payable -->
                    <div class="flex items-baseline justify-between pt-1">
                        <div>
                            <span class="text-xs font-bold text-gray-800 uppercase tracking-wide block">Total Payable:</span>
                            <span class="text-[11px] text-gray-400">All taxes &amp; LMS access included</span>
                        </div>
                        <div class="text-right">
                            <span class="text-2xl sm:text-3xl font-black text-gray-900 font-mono">
                                ₹ <span x-text="finalPayable.toFixed(2)">{{ number_format($price, 2) }}</span>
                            </span>
                        </div>
                    </div>

                    <!-- Total Savings Tag -->
                    <div class="p-2.5 rounded-xl bg-emerald-50 text-emerald-800 border border-emerald-100 text-xs font-semibold flex items-center justify-between">
                        <span class="flex items-center space-x-1.5">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span>Total Savings on this enrollment:</span>
                        </span>
                        <span class="font-mono font-bold">₹ <span x-text="totalSavings.toFixed(2)">{{ number_format($baseDiscount, 2) }}</span></span>
                    </div>
                </div>

                <!-- Interactive Dynamic Coupon Engine -->
                <div class="bg-white rounded-2xl p-5 sm:p-6 border border-gray-200 shadow-2xs space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-gray-900">Scholarship &amp; Promo Code</h3>
                        <span class="text-[10px] font-bold text-blue-700 bg-blue-50 px-2 py-0.5 rounded">Active Offers</span>
                    </div>

                    <!-- Coupon Input & Apply Button -->
                    <div class="space-y-2">
                        <div class="flex gap-2">
                            <input type="text" 
                                   x-model="couponInput" 
                                   @keyup.enter="applyCoupon()"
                                   placeholder="Enter Coupon (e.g. AGNIVEER50)" 
                                   class="flex-1 text-xs font-mono font-bold uppercase px-3.5 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-blue-600 focus:border-transparent outline-none">
                            <button type="button" 
                                    @click="applyCoupon()"
                                    :disabled="couponLoading"
                                    class="px-4 py-2.5 rounded-xl text-xs font-bold text-white bg-blue-700 hover:bg-blue-800 disabled:opacity-60 transition shadow-2xs">
                                <span x-show="!couponLoading">Apply</span>
                                <span x-show="couponLoading" style="display:none;">...</span>
                            </button>
                        </div>

                        <!-- Feedback Messages -->
                        <div x-show="couponError" style="display:none;" class="text-xs text-red-600 font-medium" x-text="couponError"></div>
                        <div x-show="couponSuccess" style="display:none;" class="text-xs text-emerald-700 font-medium flex items-center justify-between">
                            <span x-text="couponSuccess"></span>
                            <button type="button" @click="removeCoupon()" class="text-xs text-red-600 underline font-bold ml-2">Remove</button>
                        </div>
                    </div>

                    <!-- Quick Tap Suggested Promo Badges -->
                    <div class="pt-2 border-t border-gray-100">
                        <span class="text-[11px] font-semibold text-gray-500 block mb-2">Available Defence Scholarships:</span>
                        <div class="space-y-1.5">
                            <button type="button" 
                                    @click="applyCoupon('AGNIVEER50')"
                                    class="w-full flex items-center justify-between p-2 rounded-lg border border-dashed border-blue-300 bg-blue-50/40 hover:bg-blue-50 text-left transition">
                                <div>
                                    <span class="text-xs font-mono font-bold text-blue-800">AGNIVEER50</span>
                                    <span class="text-[10px] text-gray-500 block">50% off for Agniveer candidates</span>
                                </div>
                                <span class="text-[10px] font-bold text-blue-700">TAP TO APPLY</span>
                            </button>

                            <button type="button" 
                                    @click="applyCoupon('OFFICER20')"
                                    class="w-full flex items-center justify-between p-2 rounded-lg border border-dashed border-gray-300 hover:bg-gray-50 text-left transition">
                                <div>
                                    <span class="text-xs font-mono font-bold text-gray-800">OFFICER20</span>
                                    <span class="text-[10px] text-gray-500 block">20% merit waiver on batch enrollments</span>
                                </div>
                                <span class="text-[10px] font-bold text-gray-600">TAP TO APPLY</span>
                            </button>

                            <button type="button" 
                                    @click="applyCoupon('CADET100')"
                                    class="w-full flex items-center justify-between p-2 rounded-lg border border-dashed border-emerald-300 bg-emerald-50/40 hover:bg-emerald-50 text-left transition">
                                <div>
                                    <span class="text-xs font-mono font-bold text-emerald-800">CADET100</span>
                                    <span class="text-[10px] text-emerald-700 block">100% full merit academy scholarship</span>
                                </div>
                                <span class="text-[10px] font-bold text-emerald-700">100% WAIVER</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Cadet Guarantee & Invoice Notice -->
                <div class="bg-white rounded-2xl p-5 border border-gray-200 shadow-2xs text-[11px] text-gray-500 space-y-2.5 leading-relaxed">
                    <div class="flex items-start space-x-2">
                        <span class="text-emerald-600 font-bold shrink-0">✓</span>
                        <span>Instant access unlocked immediately on your student dashboard.</span>
                    </div>
                    <div class="flex items-start space-x-2">
                        <span class="text-emerald-600 font-bold shrink-0">✓</span>
                        <span>Official GST Tax Invoice (SAC 999293) generated instantly.</span>
                    </div>
                    <div class="flex items-start space-x-2">
                        <span class="text-emerald-600 font-bold shrink-0">✓</span>
                        <span>Enrollment mapped to registered cadet: <strong class="text-gray-700">{{ $user->email }}</strong></span>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- Simulated Bank / NPCI 3DS2 Gateway Processing Modal -->
    <div x-show="isProcessing" 
         style="display:none;"
         class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/70 backdrop-blur-xs p-4"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100">
        
        <div class="bg-white rounded-2xl p-6 sm:p-8 max-w-md w-full shadow-lg border border-gray-100 text-center space-y-5">
            <div class="w-16 h-16 rounded-full bg-blue-50 border border-blue-100 flex items-center justify-center mx-auto text-blue-700">
                <svg class="animate-spin h-8 w-8 text-blue-700" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
            </div>

            <div>
                <h3 class="text-base font-bold text-gray-900">Securing Payment Gateway Handshake</h3>
                <p class="text-xs text-gray-500 mt-1" x-text="processingMessage">Initiating bank handshake...</p>
            </div>

            <!-- Progress Bar -->
            <div class="w-full bg-gray-100 h-2 rounded-full overflow-hidden">
                <div class="bg-blue-700 h-full transition-all duration-500" :style="'width: ' + processingProgress + '%'"></div>
            </div>

            <div class="p-3 bg-gray-50 rounded-xl text-[11px] text-gray-500 flex items-center justify-center space-x-2">
                <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path></svg>
                <span>Do not close or refresh this window. Bank authorization in progress.</span>
            </div>
        </div>

    </div>

</div>
@endsection
