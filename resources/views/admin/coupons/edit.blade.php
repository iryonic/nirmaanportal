@extends('layouts.admin')

@section('title', 'Edit Coupon — ' . $coupon->code . ' — Admin Command')
@section('header_title', 'Edit Coupon')

@section('content')
<div class="max-w-4xl mx-auto space-y-5" x-data="{
    code: '{{ old('code', $coupon->code) }}',
    name: '{{ old('name', $coupon->name) }}',
    type: '{{ old('type', $coupon->type) }}',
    value: '{{ old('value', $coupon->value) }}',
    max_discount: '{{ old('max_discount', $coupon->max_discount) }}',
    min_order_amount: '{{ old('min_order_amount', $coupon->min_order_amount) }}',
    usage_limit: '{{ old('usage_limit', $coupon->usage_limit) }}',
    is_active: {{ old('is_active', $coupon->is_active ? '1' : '0') ? 'true' : 'false' }},

    formatCode() {
        this.code = this.code.toUpperCase().replace(/[^A-Z0-9_-]/g, '');
    },

    calculateSampleDiscount(orderAmount) {
        let v = parseFloat(this.value) || 0;
        let amt = parseFloat(orderAmount) || 0;
        let minAmt = parseFloat(this.min_order_amount) || 0;
        if (minAmt > 0 && amt < minAmt) return 0;

        if (this.type === 'fixed') {
            return Math.min(amt, v);
        } else {
            let disc = (amt * v) / 100;
            let cap = parseFloat(this.max_discount);
            if (cap && cap > 0 && disc > cap) {
                disc = cap;
            }
            return Math.min(amt, disc);
        }
    }
}">

    <!-- Page Breadcrumb & Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white rounded-xl p-4 sm:p-5 border border-slate-200/80 shadow-2xs">
        <div>
            <div class="flex items-center space-x-1.5 text-xs text-slate-500 font-medium mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-950">Dashboard</a>
                <span>/</span>
                <a href="{{ route('admin.coupons.index') }}" class="hover:text-blue-950">Coupons &amp; Concessions</a>
                <span>/</span>
                <span class="text-slate-900 font-semibold">{{ $coupon->code }}</span>
            </div>
            <h1 class="text-lg sm:text-xl font-bold text-slate-900 tracking-tight">Edit Coupon: {{ $coupon->code }}</h1>
            <p class="text-xs text-slate-500 mt-0.5">Modify discount rules, eligibility criteria, or pause redemptions.</p>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('admin.coupons.index') }}" 
               class="px-3 py-2 rounded-lg text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 transition-colors inline-flex items-center space-x-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <span>Back to Coupons</span>
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-xl p-4 text-xs text-red-700">
            <div class="font-bold mb-1 flex items-center space-x-1.5">
                <svg class="w-4 h-4 shrink-0 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <span>Please correct the errors below before continuing:</span>
            </div>
            <ul class="list-disc pl-5 space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.coupons.update', $coupon) }}" class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        @csrf
        @method('PUT')

        <!-- Main Form Settings (2 Cols) -->
        <div class="lg:col-span-2 space-y-5">
            
            <div class="bg-white rounded-xl p-5 border border-slate-200/80 shadow-2xs space-y-4">
                <h2 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-2.5 flex items-center space-x-2">
                    <svg class="w-4 h-4 text-blue-950" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                    <span>Voucher Identification</span>
                </h2>

                <!-- Coupon Code -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1" for="code">
                        Coupon Code <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="code" name="code" x-model="code" @input="formatCode" required
                           class="w-full px-3.5 py-2.5 text-sm uppercase font-mono font-bold tracking-wider bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-900 focus:bg-white text-slate-900">
                    <p class="text-[11px] text-slate-400 mt-1">Codes will automatically be uppercase. Letters, numbers, hyphens, and underscores only.</p>
                </div>

                <!-- Friendly Title / Campaign Name -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1" for="name">
                        Campaign / Scholarship Title
                    </label>
                    <input type="text" id="name" name="name" x-model="name"
                           class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-900 focus:bg-white text-slate-900">
                    <p class="text-[11px] text-slate-400 mt-1">Shown to student upon successful code application in checkout cart summary.</p>
                </div>
            </div>

            <div class="bg-white rounded-xl p-5 border border-slate-200/80 shadow-2xs space-y-4">
                <h2 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-2.5 flex items-center space-x-2">
                    <svg class="w-4 h-4 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>Discount Computation</span>
                </h2>

                <!-- Discount Type -->
                <div class="grid grid-cols-2 gap-3">
                    <label class="cursor-pointer border rounded-xl p-3 flex items-center space-x-3 transition-colors"
                           :class="type === 'percent' ? 'border-blue-900 bg-blue-50/60 ring-1 ring-blue-900' : 'border-slate-200 hover:bg-slate-50'">
                        <input type="radio" name="type" value="percent" x-model="type" class="text-blue-950 focus:ring-blue-900">
                        <div>
                            <span class="text-xs font-bold text-slate-900 block">Percentage (%)</span>
                            <span class="text-[10px] text-slate-500">Deducts a percentage from base fee</span>
                        </div>
                    </label>

                    <label class="cursor-pointer border rounded-xl p-3 flex items-center space-x-3 transition-colors"
                           :class="type === 'fixed' ? 'border-blue-900 bg-blue-50/60 ring-1 ring-blue-900' : 'border-slate-200 hover:bg-slate-50'">
                        <input type="radio" name="type" value="fixed" x-model="type" class="text-blue-950 focus:ring-blue-900">
                        <div>
                            <span class="text-xs font-bold text-slate-900 block">Fixed Flat (₹)</span>
                            <span class="text-[10px] text-slate-500">Deducts an exact rupee amount</span>
                        </div>
                    </label>
                </div>

                <!-- Value & Max Cap -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1" for="value">
                            Discount Value <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-2.5 text-xs font-bold text-slate-400" x-text="type === 'percent' ? '%' : '₹'"></span>
                            <input type="number" step="0.01" min="0.01" id="value" name="value" x-model="value" required
                                   class="w-full pl-8 pr-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-900 focus:bg-white text-slate-900 font-mono font-bold">
                        </div>
                    </div>

                    <div x-show="type === 'percent'" x-transition>
                        <label class="block text-xs font-semibold text-slate-700 mb-1" for="max_discount">
                            Maximum Cap (₹) <span class="text-slate-400 font-normal">(Optional)</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-2.5 text-xs font-bold text-slate-400">₹</span>
                            <input type="number" step="0.01" min="0" id="max_discount" name="max_discount" x-model="max_discount"
                                   placeholder="Leave empty for unlimited"
                                   class="w-full pl-8 pr-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-900 focus:bg-white text-slate-900 font-mono">
                        </div>
                    </div>
                </div>

                <!-- Min Order Amount -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1" for="min_order_amount">
                        Minimum Order Value (₹)
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-2.5 text-xs font-bold text-slate-400">₹</span>
                        <input type="number" step="0.01" min="0" id="min_order_amount" name="min_order_amount" x-model="min_order_amount"
                               class="w-full pl-8 pr-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-900 focus:bg-white text-slate-900 font-mono">
                    </div>
                </div>
            </div>

            <!-- Limits & Expiration -->
            <div class="bg-white rounded-xl p-5 border border-slate-200/80 shadow-2xs space-y-4">
                <h2 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-2.5 flex items-center space-x-2">
                    <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>Usage Limits &amp; Expiration</span>
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1" for="usage_limit">
                            Total Redemptions Cap
                        </label>
                        <input type="number" min="1" id="usage_limit" name="usage_limit" x-model="usage_limit"
                               placeholder="e.g. 50 (leave empty for unlimited)"
                               class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-900 focus:bg-white text-slate-900 font-mono">
                        <p class="text-[11px] text-slate-400 mt-1">Currently redeemed: <strong class="text-slate-700">{{ $coupon->used_count }} times</strong>.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1" for="expires_at">
                            Expiration Date &amp; Time
                        </label>
                        <input type="datetime-local" id="expires_at" name="expires_at" 
                               value="{{ old('expires_at', $coupon->expires_at ? $coupon->expires_at->format('Y-m-d\TH:i') : '') }}"
                               class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-900 focus:bg-white text-slate-900">
                    </div>
                </div>

                <!-- Active Toggle -->
                <div class="pt-2">
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" x-model="is_active" class="sr-only">
                        <div class="w-10 h-6 bg-slate-200 rounded-full transition-colors relative"
                             :class="is_active ? 'bg-emerald-600' : 'bg-slate-300'">
                            <div class="w-4 h-4 bg-white rounded-full absolute top-1 transition-transform"
                                 :class="is_active ? 'translate-x-5' : 'translate-x-1'"></div>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-slate-900 block">Active Status</span>
                            <span class="text-[11px] text-slate-500">Enable or pause redemptions for this coupon code</span>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Submit Button Bar -->
            <div class="flex items-center justify-end space-x-3">
                <a href="{{ route('admin.coupons.index') }}" 
                   class="px-4 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 rounded-lg transition-colors">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-5 py-2 text-xs font-semibold text-white bg-blue-950 hover:bg-blue-900 rounded-lg transition-colors shadow-2xs flex items-center space-x-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span>Update Coupon</span>
                </button>
            </div>
        </div>

        <!-- Sidebar Summary & Stats (1 Col) -->
        <div class="space-y-4">
            <!-- Coupon Performance -->
            <div class="bg-white rounded-xl p-5 border border-slate-200/80 shadow-2xs space-y-3">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider text-slate-500">
                    Redemption Analytics
                </h3>

                <div class="grid grid-cols-2 gap-3 pt-1">
                    <div class="bg-blue-50/60 rounded-lg p-3 border border-blue-100">
                        <span class="text-[10px] text-slate-500 font-semibold block uppercase">Total Used</span>
                        <span class="text-lg font-bold font-mono text-blue-950">{{ $coupon->used_count }}</span>
                    </div>

                    <div class="bg-slate-50 rounded-lg p-3 border border-slate-200/60">
                        <span class="text-[10px] text-slate-500 font-semibold block uppercase">Max Limit</span>
                        <span class="text-lg font-bold font-mono text-slate-800">{{ $coupon->usage_limit ?? '∞' }}</span>
                    </div>
                </div>

                <div class="text-[11px] text-slate-400 pt-2 border-t border-slate-100">
                    Created on {{ $coupon->created_at->format('M d, Y h:i A') }}
                    @if($coupon->creator)
                        by <strong class="text-slate-600">{{ $coupon->creator->name }}</strong>
                    @endif
                </div>
            </div>

            <!-- Simulated Voucher Card -->
            <div class="bg-white rounded-xl p-5 border border-slate-200/80 shadow-2xs space-y-4">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider text-slate-500">
                    Cadet Checkout Simulation
                </h3>

                <div class="border-2 border-dashed border-blue-950/30 rounded-xl p-4 bg-gradient-to-br from-blue-50/50 via-white to-amber-50/30 relative overflow-hidden">
                    <div class="flex items-center justify-between mb-2">
                        <span class="px-2.5 py-1 rounded bg-blue-950 text-white font-mono font-bold text-xs tracking-wider uppercase"
                              x-text="code || 'PROMOCODE'"></span>
                        <span class="text-[10px] font-semibold text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded-full"
                              x-show="is_active">ACTIVE</span>
                        <span class="text-[10px] font-semibold text-slate-500 bg-slate-100 px-2 py-0.5 rounded-full"
                              x-show="!is_active">INACTIVE</span>
                    </div>

                    <div class="text-sm font-bold text-slate-900 mt-2" x-text="name || 'Scholarship Concession'"></div>
                    
                    <div class="text-xs text-blue-950 font-bold mt-1">
                        <span x-show="type === 'percent'">
                            <span x-text="value || 0"></span>% OFF
                            <span x-show="max_discount" class="text-slate-500 font-normal">(Up to ₹<span x-text="max_discount"></span>)</span>
                        </span>
                        <span x-show="type === 'fixed'">
                            ₹<span x-text="value || 0"></span> Flat Discount
                        </span>
                    </div>
                </div>

                <!-- Simulated Calculations for Standard Course Fees -->
                <div class="bg-slate-50 rounded-lg p-3 text-xs space-y-2 border border-slate-200/60">
                    <div class="font-bold text-slate-700 text-[11px] uppercase tracking-wider">Simulated Deductions:</div>
                    
                    <div class="flex justify-between items-center text-slate-600">
                        <span>Course Fee ₹2,999:</span>
                        <span class="font-mono font-bold text-emerald-700">
                            -₹<span x-text="calculateSampleDiscount(2999).toFixed(2)"></span>
                        </span>
                    </div>

                    <div class="flex justify-between items-center text-slate-600">
                        <span>Course Fee ₹4,999:</span>
                        <span class="font-mono font-bold text-emerald-700">
                            -₹<span x-text="calculateSampleDiscount(4999).toFixed(2)"></span>
                        </span>
                    </div>
                </div>
            </div>
        </div>

    </form>

</div>
@endsection
