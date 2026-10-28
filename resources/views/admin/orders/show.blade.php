@extends('layouts.admin')

@section('title', 'Order Receipt #' . $order->order_number . ' — Admin Command')
@section('header_title', 'Order View')

@section('content')
@php
    $notes = json_decode($order->notes, true);
    $isJson = json_last_error() === JSON_ERROR_NONE && is_array($notes);
    $coupon = $isJson ? ($notes['coupon'] ?? null) : null;
    $discount = $isJson ? (float) ($notes['discount'] ?? 0) : 0;
    $original = $isJson ? (float) ($notes['original_price'] ?? $order->amount) : (float) $order->amount;
    $meta = $isJson ? ($notes['meta'] ?? null) : null;
@endphp

<div class="max-w-2xl mx-auto space-y-5">
    
    <!-- Top Action Breadcrumb Bar -->
    <div class="flex items-center justify-between bg-white rounded-xl p-3.5 sm:p-4 border border-slate-200/80 shadow-2xs">
        <div class="flex items-center space-x-1.5 text-xs text-slate-500 font-medium">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-950">Dashboard</a>
            <span>/</span>
            <a href="{{ route('admin.orders.index') }}" class="hover:text-blue-950">Orders</a>
            <span>/</span>
            <span class="text-slate-900 font-semibold truncate max-w-xs font-mono">{{ $order->order_number }}</span>
        </div>
        <div class="flex items-center space-x-2">
            <button type="button" onclick="window.print()" class="px-2.5 py-1 rounded-md text-xs font-medium text-slate-700 bg-white hover:bg-slate-50 border border-slate-200 transition-colors shadow-2xs">
                Print Invoice
            </button>
            <a href="{{ route('admin.orders.index') }}" class="text-xs font-semibold text-slate-600 hover:text-slate-900 transition-colors">
                &larr; Back
            </a>
        </div>
    </div>

    <!-- Receipt Card -->
    <div class="bg-white rounded-xl p-6 sm:p-8 border border-slate-200/80 shadow-2xs space-y-6">
        
        <div class="flex items-center justify-between pb-5 border-b border-slate-200/80">
            <div>
                <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider block">Official GST Tax Receipt</span>
                <h1 class="text-xl font-bold text-slate-900 font-mono mt-0.5">{{ $order->order_number }}</h1>
                <span class="text-xs text-slate-400">{{ $order->created_at->format('d M, Y h:i A') }}</span>
            </div>
            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-md text-xs font-medium uppercase {{ $order->status === 'paid' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200/60' : 'bg-amber-50 text-amber-700 border border-amber-200/60' }}">
                <span class="w-1.5 h-1.5 rounded-full {{ $order->status === 'paid' ? 'bg-emerald-500' : 'bg-amber-500' }}"></span>
                <span>{{ $order->status }}</span>
            </span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
            <div class="space-y-1">
                <span class="text-slate-400 font-semibold uppercase tracking-wider text-[10px] block">Candidate:</span>
                <strong class="text-sm font-semibold text-slate-900 block">{{ $order->user ? $order->user->name : 'N/A' }}</strong>
                <span class="text-slate-600 block font-mono">{{ $order->user ? $order->user->email : '' }}</span>
                <span class="text-slate-600 block font-mono">{{ $order->user ? $order->user->phone : '' }}</span>
            </div>

            <div class="space-y-1 sm:text-right">
                <span class="text-slate-400 font-semibold uppercase tracking-wider text-[10px] block">Gateway Reference:</span>
                <span class="text-slate-800 block">Channel: <strong class="uppercase font-mono font-semibold">{{ $order->payment_gateway }}</strong>
                    @if($order->gateway_mode)
                        <span class="text-[10px] px-1.5 py-0.5 rounded font-mono font-bold uppercase {{ $order->gateway_mode === 'live' ? 'bg-amber-100 text-amber-800' : 'bg-blue-100 text-blue-800' }}">{{ $order->gateway_mode }}</span>
                    @endif
                </span>
                @if($order->razorpay_order_id)
                    <span class="text-slate-700 block font-mono text-[11px]">Razorpay Order: <strong class="text-blue-950">{{ $order->razorpay_order_id }}</strong></span>
                @endif
                <span class="text-slate-800 block font-mono">Payment ID: {{ $order->payment_id ?? 'N/A' }}</span>
                @if($order->payment_signature)
                    <span class="text-emerald-700 block font-mono text-[10px]">HMAC Signature: Verified ✓</span>
                @endif
                @if($meta)
                    <span class="text-slate-500 block font-mono text-[11px]">Meta: {{ $meta }}</span>
                @endif
                <span class="text-emerald-700 font-medium block">Paid: {{ $order->paid_at ? $order->paid_at->format('d M, Y h:i A') : 'Pending' }}</span>
            </div>
        </div>

        <!-- Purchased Item Card -->
        <div class="p-4 rounded-lg bg-slate-50 border border-slate-200/80 flex items-center justify-between text-xs">
            <div>
                <span class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider">
                    {{ $order->course ? 'Course Batch Enrollment' : ($order->mockTest ? 'CBT Mock Test Series' : 'Platform Subscription') }}
                </span>
                <h3 class="text-sm font-semibold text-slate-900 mt-0.5">
                    {{ $order->course ? $order->course->title : ($order->mockTest ? $order->mockTest->title : 'Academy Access') }}
                </h3>
                <span class="text-slate-500 text-[11px]">SAC Code: 999293 (Educational Services)</span>
            </div>
            <div class="text-right">
                @if($discount > 0)
                    <span class="text-xs text-slate-400 line-through block font-mono">₹ {{ number_format($original, 2) }}</span>
                @endif
                <span class="text-lg font-bold text-slate-900 font-mono">₹ {{ number_format($order->amount, 2) }}</span>
            </div>
        </div>

        @if($coupon && $discount > 0)
            <div class="p-3 rounded-lg bg-emerald-50/60 border border-emerald-200 text-xs text-emerald-800 flex items-center justify-between">
                <span>Applied Promo Concession (<strong>{{ $coupon }}</strong>):</span>
                <span class="font-semibold font-mono">-₹ {{ number_format($discount, 2) }}</span>
            </div>
        @endif

        <div class="pt-3 flex flex-wrap justify-between items-center gap-3 text-xs border-t border-slate-100">
            <a href="{{ route('student.orders.receipt', $order->id) }}" target="_blank" class="text-blue-950 font-medium hover:underline">
                Public Candidate Invoice Receipt ↗
            </a>

            @if($order->user_id)
                <a href="{{ route('admin.students.show', $order->user_id) }}" class="px-4 py-2 rounded-lg text-xs font-semibold text-white bg-blue-950 hover:bg-blue-900 transition-colors shadow-2xs">
                    View Cadet Profile &rarr;
                </a>
            @endif
        </div>

    </div>

</div>
@endsection
