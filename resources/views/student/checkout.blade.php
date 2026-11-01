@extends('layouts.student')

@section('title', 'Secure Checkout — ' . $item->title)

@section('content')
@php
    $formAction = $type === 'course' 
        ? route('student.checkout.pay', $item->id) 
        : route('student.checkout.test.pay', $item->id);
    $price = (float) ($item->price > 0 ? $item->price : 499.00);
    $originalPrice = (float) ($item->original_price ?? ($price * 1.4));
    $baseDiscount = max(0, $originalPrice - $price);
    
    $defaultConfig = \App\Models\SiteSetting::getPaymentConfig();
    $paymentConfig = array_replace_recursive($defaultConfig, $paymentConfig ?? []);

    $razorpayJson = json_encode($razorpayConfig ?? [
        'enabled' => $paymentConfig['razorpay']['enabled'] ?? true,
        'key_id' => $paymentConfig['razorpay']['key_id'] ?? '',
        'merchant_name' => $paymentConfig['razorpay']['merchant_name'] ?? 'Nirmaan Academy',
        'theme_color' => $paymentConfig['razorpay']['theme_color'] ?? '#172554',
        'mode' => $paymentConfig['razorpay']['mode'] ?? 'test',
        'has_keys' => !empty($paymentConfig['razorpay']['key_id']),
    ]);
    $paymentConfigJson = json_encode($paymentConfig);
    $defaultMethod = 'razorpay';
@endphp

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script>
function checkoutEngine() {
    return {
        razorpayConfig: {!! $razorpayJson !!},
        paymentConfig: {!! $paymentConfigJson !!},
        method: 'razorpay',
        basePrice: {{ $price }},
        originalPrice: {{ $originalPrice }},
        baseDiscount: {{ $baseDiscount }},
        
        // Razorpay state
        razorpayPaymentId: '',
        razorpayOrderId: '',
        razorpaySignature: '',
        
        // Coupon state
        couponInput: '',
        couponLoading: false,
        couponError: '',
        couponSuccess: '',
        appliedCoupon: null,
        
        // Processing modal state
        isProcessing: false,
        processingMessage: 'Connecting to Razorpay secure checkout...',
        
        // Computed
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
        
        applyCoupon(directCode = null) {
            let code = directCode || this.couponInput;
            code = (code || '').trim().toUpperCase();
            
            if (!code) {
                this.couponError = 'Please enter a coupon code.';
                this.couponSuccess = '';
                return;
            }
            
            this.couponLoading = true;
            this.couponError = '';
            this.couponSuccess = '';
            
            fetch('{{ route('student.checkout.validate-coupon', [], false) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    code: code,
                    base_price: this.basePrice,
                    item_id: {{ $item->id }},
                    type: '{{ $type }}'
                })
            })
            .then(async res => {
                const isJson = (res.headers.get('content-type') || '').includes('application/json');
                const data = isJson ? await res.json() : {};
                return { status: res.status, body: data };
            })
            .then(({ status, body }) => {
                this.couponLoading = false;
                if (status === 200 && body.valid) {
                    this.appliedCoupon = body;
                    this.couponInput = body.code;
                    this.couponSuccess = body.message;
                    this.couponError = '';
                } else {
                    this.couponError = body.message || 'Invalid promo code.';
                    this.couponSuccess = '';
                }
            })
            .catch(err => {
                this.couponLoading = false;
                this.couponError = 'Could not validate coupon. Please try again.';
            });
        },
        
        removeCoupon() {
            this.appliedCoupon = null;
            this.couponInput = '';
            this.couponSuccess = '';
            this.couponError = '';
        },
        
        submitPayment() {
            this.isProcessing = true;
            this.processingMessage = 'Opening secure Razorpay gateway...';

            fetch('{{ route('student.checkout.create-razorpay-order', [], false) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    type: '{{ $type }}',
                    item_type: '{{ $type }}',
                    item_id: {{ $item->id }},
                    amount: this.finalPayable,
                    coupon_code: this.appliedCoupon ? this.appliedCoupon.code : null
                })
            })
            .then(async res => {
                const isJson = (res.headers.get('content-type') || '').includes('application/json');
                const data = isJson ? await res.json() : null;
                if (!res.ok) {
                    const errMsg = (data && (data.message || data.error)) || ('Server returned HTTP ' + res.status);
                    throw new Error(errMsg);
                }
                return data;
            })
            .then(data => {
                if (!data || !data.success) {
                    this.isProcessing = false;
                    window.appAlert({
                        title: 'Payment Gateway Error',
                        message: 'Could not initialize gateway: ' + (data?.message || 'Unknown error'),
                        tone: 'danger'
                    });
                    return;
                }

                this.razorpayOrderId = data.order_id;

                if (this.razorpayConfig.has_keys && !data.simulation && !data.is_simulated) {
                    this.isProcessing = false;

                    const rzp = new Razorpay({
                        key: this.razorpayConfig.key_id,
                        amount: data.amount,
                        currency: data.currency || 'INR',
                        name: this.razorpayConfig.merchant_name || 'Nirmaan Defence Academy',
                        description: '{{ addslashes($item->title) }}',
                        order_id: data.order_id,
                        prefill: {
                            name: '{{ addslashes($user->name) }}',
                            email: '{{ $user->email }}',
                            contact: '{{ $user->phone ?? "" }}'
                        },
                        theme: {
                            color: this.razorpayConfig.theme_color || '#172554'
                        },
                        handler: (response) => {
                            this.isProcessing = true;
                            this.processingMessage = 'Payment successful! Unlocking your access...';
                            
                            this.razorpayPaymentId = response.razorpay_payment_id;
                            this.razorpaySignature = response.razorpay_signature;

                            setTimeout(() => {
                                document.getElementById('checkout-form').submit();
                            }, 500);
                        },
                        modal: {
                            ondismiss: () => {
                                this.isProcessing = false;
                            }
                        }
                    });

                    rzp.on('payment.failed', (resp) => {
                        this.isProcessing = false;
                        window.appAlert({
                            title: 'Payment Unsuccessful',
                            message: 'Payment could not be completed: ' + (resp.error?.description || 'Gateway error'),
                            tone: 'danger'
                        });
                    });

                    rzp.open();
                } else {
                    // Sandbox / Test Fallback
                    this.processingMessage = 'Confirming enrollment in sandbox test mode...';
                    setTimeout(() => {
                        this.razorpayPaymentId = 'pay_sim_' + Math.random().toString(36).substring(2, 14);
                        this.razorpaySignature = 'sig_sim_' + Math.random().toString(36).substring(2, 20);
                        setTimeout(() => {
                            document.getElementById('checkout-form').submit();
                        }, 400);
                    }, 800);
                }
            })
            .catch(err => {
                this.isProcessing = false;
                window.appAlert({
                    title: 'Checkout Error',
                    message: err.message,
                    tone: 'danger'
                });
            });
        }
    };
}
</script>

<div class="py-6 sm:py-10 bg-slate-50/70 min-h-screen text-slate-900" x-data="checkoutEngine()">

    <div class="max-w-5xl mx-auto px-4 sm:px-6">
        
        <!-- Simplified Header -->
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-slate-200">
            <div>
                <div class="flex items-center space-x-1.5 text-xs text-slate-500 mb-1">
                    <a href="{{ route('courses.index') }}" class="hover:text-blue-950 font-medium">Courses</a>
                    <span>/</span>
                    <span class="text-slate-900 font-semibold">Checkout</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">Complete Your Enrollment</h1>
            </div>

            <!-- Trust Badge -->
            <div class="flex items-center space-x-2 text-xs font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200 px-3 py-1.5 rounded-lg shrink-0 self-start sm:self-auto">
                <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                <span>256-Bit SSL • Instant Access Guaranteed</span>
            </div>
        </div>

        <form action="{{ $formAction }}" method="POST" id="checkout-form" @submit.prevent="submitPayment()">
            @csrf

            <!-- Hidden Fields for Verification: Always Razorpay -->
            <input type="hidden" name="payment_method" value="razorpay">
            <input type="hidden" name="coupon_code" :value="appliedCoupon ? appliedCoupon.code : ''">
            <input type="hidden" name="payment_reference" :value="razorpayPaymentId || ('pay_rzp_' + Math.random().toString(36).substring(2, 10))">
            <input type="hidden" name="razorpay_payment_id" :value="razorpayPaymentId">
            <input type="hidden" name="razorpay_order_id" :value="razorpayOrderId">
            <input type="hidden" name="razorpay_signature" :value="razorpaySignature">
            <input type="hidden" name="method_meta" value="Razorpay Verified Gateway">

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                
                <!-- Left Column: Razorpay Official Only (7 Cols) -->
                <div class="lg:col-span-7 space-y-4">
                    
                    <!-- Cadet Identity Pill -->
                    <div class="bg-white rounded-xl p-3.5 border border-slate-200/90 shadow-2xs flex items-center justify-between text-xs">
                        <div class="flex items-center space-x-2.5">
                            <div class="w-8 h-8 rounded-full bg-blue-950 text-white font-bold flex items-center justify-center text-xs">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div>
                                <span class="font-bold text-slate-900 block">{{ $user->name }}</span>
                                <span class="text-slate-500 font-mono text-[11px]">{{ $user->email }}</span>
                            </div>
                        </div>
                        <span class="text-[11px] font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">
                            Verified Cadet
                        </span>
                    </div>

                    <!-- Razorpay Official Gateway Box (Only Allowed Method) -->
                    <div class="bg-white rounded-xl border-2 border-blue-950/20 shadow-2xs overflow-hidden">
                        
                        <div class="p-4 border-b border-slate-100 bg-blue-950/[0.03] flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                                <h2 class="text-xs font-bold uppercase tracking-wider text-slate-800">Official Payment Gateway</h2>
                            </div>
                            <span class="text-[10px] font-black uppercase tracking-wider px-2 py-0.5 rounded bg-blue-950 text-white">
                                Razorpay Verified
                            </span>
                        </div>

                        <div class="p-4 sm:p-5 space-y-4">

                            <!-- Razorpay Visual Badge Card -->
                            <div class="p-4 rounded-xl border border-blue-950/15 bg-gradient-to-br from-blue-50/50 to-white space-y-3">
                                
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-2.5">
                                        <div class="w-9 h-9 rounded-lg bg-blue-950 text-white flex items-center justify-center shadow-xs">
                                            <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                        </div>
                                        <div>
                                            <h3 class="text-sm font-bold text-slate-900">Online Payment Gateway</h3>
                                            <p class="text-xs text-slate-500">Fast, encrypted and 100% secure checkout via Razorpay</p>
                                        </div>
                                    </div>

                                    <span class="text-[10px] font-bold text-emerald-800 bg-emerald-100 px-2 py-0.5 rounded-md hidden sm:inline-block">
                                        RBI Regulated
                                    </span>
                                </div>

                                <!-- Accepted Payment Methods inside Razorpay -->
                                <div class="pt-2.5 border-t border-slate-200/80 space-y-2">
                                    <span class="text-[11px] font-semibold text-slate-600 block">Accepted payment options inside Razorpay modal:</span>
                                    
                                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 text-center text-xs">
                                        <div class="p-2 rounded-lg bg-white border border-slate-200/90 shadow-2xs">
                                            <span class="font-bold text-blue-950 block text-[11px]">Instant UPI</span>
                                            <span class="text-[10px] text-slate-500">GPay, PhonePe, Paytm</span>
                                        </div>
                                        <div class="p-2 rounded-lg bg-white border border-slate-200/90 shadow-2xs">
                                            <span class="font-bold text-blue-950 block text-[11px]">Cards</span>
                                            <span class="text-[10px] text-slate-500">RuPay, Visa, Master</span>
                                        </div>
                                        <div class="p-2 rounded-lg bg-white border border-slate-200/90 shadow-2xs">
                                            <span class="font-bold text-blue-950 block text-[11px]">Net Banking</span>
                                            <span class="text-[10px] text-slate-500">All 50+ Banks</span>
                                        </div>
                                        <div class="p-2 rounded-lg bg-white border border-slate-200/90 shadow-2xs">
                                            <span class="font-bold text-blue-950 block text-[11px]">Wallets / EMI</span>
                                            <span class="text-[10px] text-slate-500">Easy Installments</span>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <!-- Trust Bullets -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs text-slate-600 pt-1">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                    <span>Immediate automated course unlock</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                    <span>GST tax invoice &amp; receipt provided</span>
                                </div>
                            </div>

                            @if(($razorpayConfig['mode'] ?? 'test') === 'test' || !empty($paymentConfig['razorpay']['fallback_simulation']))
                                <div class="p-2.5 rounded-lg bg-amber-50 border border-amber-200 text-amber-900 text-xs flex items-center space-x-2">
                                    <span class="w-2 h-2 rounded-full bg-amber-500 shrink-0"></span>
                                    <span><strong>Sandbox Mode:</strong> Razorpay simulation enabled for safe test transactions.</span>
                                </div>
                            @endif

                        </div>

                        <!-- Big High-Converting CTA Button -->
                        <div class="p-4 bg-slate-50 border-t border-slate-200/80">
                            <button type="submit" 
                                    :disabled="isProcessing"
                                    class="w-full py-4 px-6 rounded-xl font-bold text-sm sm:text-base text-white bg-blue-950 hover:bg-blue-900 active:scale-[0.99] transition-all shadow-md hover:shadow-lg flex items-center justify-center space-x-2 disabled:opacity-60 cursor-pointer">
                                <span>🔒 Pay ₹<span x-text="finalPayable.toFixed(2)">{{ number_format($price, 2) }}</span> via Razorpay</span>
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </button>
                            <p class="text-center text-[11px] text-slate-500 mt-2">
                                Encrypted 256-Bit SSL Connection • Instant Access Unlocked
                            </p>
                        </div>
                    </div>

                </div>

                <!-- Right Column: High-Converting Order Summary & Quick Coupons (5 Cols) -->
                <div class="lg:col-span-5 space-y-4">
                    
                    <!-- Course / Test Summary Box -->
                    <div class="bg-white rounded-xl p-4 sm:p-5 border border-slate-200/90 shadow-2xs space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] font-extrabold uppercase tracking-wider text-blue-950 bg-blue-50 px-2 py-0.5 rounded border border-blue-200/60">
                                {{ $item->examCategory ? $item->examCategory->name : 'Defence Exam' }}
                            </span>
                            <span class="text-[11px] font-bold text-emerald-700">★ 4.9/5 Rating</span>
                        </div>

                        <h3 class="text-sm sm:text-base font-bold text-slate-900 leading-snug">{{ $item->title }}</h3>

                        <!-- Key Benefits List -->
                        <div class="pt-2 border-t border-slate-100 space-y-1.5 text-xs text-slate-600">
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span>Full syllabus lessons, tests &amp; downloadable notes</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span>Learn on Mobile, Tablet &amp; Laptop anytime</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span>Official GST Tax Invoice provided instantly</span>
                            </div>
                        </div>

                        <!-- Price Breakdown -->
                        <div class="pt-3 border-t border-slate-100 space-y-2 text-xs">
                            <div class="flex justify-between text-slate-600">
                                <span>Regular Price:</span>
                                <span class="font-mono line-through text-slate-400">₹{{ number_format($originalPrice, 2) }}</span>
                            </div>

                            @if($baseDiscount > 0)
                                <div class="flex justify-between text-emerald-700 font-semibold">
                                    <span>Special Discount:</span>
                                    <span class="font-mono">-₹{{ number_format($baseDiscount, 2) }}</span>
                                </div>
                            @endif

                            <!-- Applied Coupon Row -->
                            <div x-show="appliedCoupon" style="display:none;" 
                                 class="flex justify-between items-center text-emerald-700 font-bold bg-emerald-50 p-2 rounded-lg border border-emerald-200">
                                <div>
                                    <span class="block">Promo (<span x-text="appliedCoupon?.code"></span>):</span>
                                    <span class="text-[10px] font-normal text-emerald-600" x-text="appliedCoupon?.label"></span>
                                </div>
                                <div class="text-right">
                                    <span class="font-mono block">-<span x-text="appliedCoupon?.formatted_discount"></span></span>
                                    <button type="button" @click="removeCoupon()" class="text-[10px] text-red-600 underline font-semibold">Remove</button>
                                </div>
                            </div>

                      

                            <!-- Net Total -->
                            <div class="pt-2 border-t border-slate-200 flex items-baseline justify-between">
                                <span class="text-xs font-bold text-slate-900 uppercase">Total Amount:</span>
                                <span class="text-xl sm:text-2xl font-extrabold text-slate-900 font-mono">
                                    ₹<span x-text="finalPayable.toFixed(2)">{{ number_format($price, 2) }}</span>
                                </span>
                            </div>

                            <!-- Total Savings Badge -->
                            <div class="bg-emerald-50 text-emerald-800 text-[11px] font-bold p-2 rounded-lg text-center border border-emerald-200">
                                🎉 You are saving ₹<span x-text="totalSavings.toFixed(2)">{{ number_format($baseDiscount, 2) }}</span> on this enrollment!
                            </div>
                        </div>
                    </div>

                    <!-- Clean 1-Tap Coupon Box -->
                    <div class="bg-white rounded-xl p-4 sm:p-5 border border-slate-200/90 shadow-2xs space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold uppercase tracking-wide text-slate-900">Have a Promo / Scholarship Code?</span>
                        </div>

                        <div class="flex gap-2">
                            <input type="text" x-model="couponInput" @keyup.enter="applyCoupon()" placeholder="Enter Code (e.g. AGNIVEER50)"
                                   class="flex-1 text-xs font-mono uppercase font-bold px-3 py-2 rounded-lg border border-slate-300 outline-none focus:ring-1 focus:ring-blue-950">
                            <button type="button" @click="applyCoupon()" :disabled="couponLoading"
                                    class="px-4 py-2 rounded-lg text-xs font-bold text-white bg-blue-950 hover:bg-blue-900 transition-colors disabled:opacity-50">
                                <span x-show="!couponLoading">Apply</span>
                                <span x-show="couponLoading" style="display:none;">...</span>
                            </button>
                        </div>

                        <div x-show="couponError" style="display:none;" class="text-xs text-red-600 font-medium" x-text="couponError"></div>
                        <div x-show="couponSuccess" style="display:none;" class="text-xs text-emerald-700 font-medium" x-text="couponSuccess"></div>

                        <!-- 1-Tap Quick Apply Badges -->
                        <div class="pt-2 border-t border-slate-100">
                            <span class="text-[11px] text-slate-500 font-medium block mb-1.5">Available Offers:</span>
                            <div class="flex flex-wrap gap-1.5">
                                @if(isset($activeCoupons) && $activeCoupons->count() > 0)
                                    @foreach($activeCoupons as $ac)
                                        <button type="button" @click="applyCoupon('{{ $ac->code }}')"
                                                class="px-2.5 py-1 text-[11px] font-mono font-bold bg-blue-50 text-blue-950 border border-blue-200 rounded-md hover:bg-blue-100 transition-colors">
                                            {{ $ac->code }}
                                            <span class="font-sans text-[10px] text-blue-700 font-normal">({{ $ac->type === 'percent' ? number_format($ac->value, 0) . '%' : '₹' . number_format($ac->value, 0) }} off)</span>
                                        </button>
                                    @endforeach
                                @else
                                    <button type="button" @click="applyCoupon('AGNIVEER50')"
                                            class="px-2.5 py-1 text-[11px] font-mono font-bold bg-blue-50 text-blue-950 border border-blue-200 rounded-md hover:bg-blue-100 transition-colors">
                                        AGNIVEER50 <span class="font-sans text-[10px] text-blue-700 font-normal">(50% off)</span>
                                    </button>
                                    <button type="button" @click="applyCoupon('OFFICER20')"
                                            class="px-2.5 py-1 text-[11px] font-mono font-bold bg-slate-100 text-slate-800 border border-slate-200 rounded-md hover:bg-slate-200 transition-colors">
                                        OFFICER20 <span class="font-sans text-[10px] text-slate-600 font-normal">(20% off)</span>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Reassurance Card -->
                    <div class="p-3.5 bg-slate-100/80 rounded-xl text-center text-xs text-slate-500 space-y-1">
                        <div class="font-bold text-slate-700">Need Admissions Assistance?</div>
                        <div class="text-[11px]">Call our academy helpline: <a href="tel:{{ $paymentConfig['offline']['contact_phone'] ?? '+919876543210' }}" class="text-blue-950 font-bold underline">{{ $paymentConfig['offline']['contact_phone'] ?? '+91 98765 43210' }}</a></div>
                    </div>

                </div>

            </div>

        </form>

    </div>

    <!-- Simple, Clean Processing Overlay -->
    <div x-show="isProcessing" style="display:none;"
         class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4">
        <div class="bg-white rounded-2xl p-6 max-w-sm w-full text-center space-y-4 shadow-xl border border-slate-100">
            <div class="w-12 h-12 rounded-full bg-blue-50 text-blue-950 flex items-center justify-center mx-auto">
                <svg class="animate-spin h-6 w-6 text-blue-950" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
            </div>
            <div>
                <h3 class="text-sm font-bold text-slate-900">Securing Access</h3>
                <p class="text-xs text-slate-500 mt-1" x-text="processingMessage">Please do not refresh this window...</p>
            </div>
        </div>
    </div>

</div>
@endsection
