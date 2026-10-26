@extends('layouts.admin')

@section('title', 'Order Receipt #' . $order->order_number . ' — Admin Command')
@section('header_title', 'Order Receipt #' . $order->order_number)

@section('content')
@php
    $notes = json_decode($order->notes, true);
    $isJson = json_last_error() === JSON_ERROR_NONE && is_array($notes);
    $coupon = $isJson ? ($notes['coupon'] ?? null) : null;
    $discount = $isJson ? (float) ($notes['discount'] ?? 0) : 0;
    $original = $isJson ? (float) ($notes['original_price'] ?? $order->amount) : (float) $order->amount;
    $meta = $isJson ? ($notes['meta'] ?? null) : null;
@endphp

<div class="max-w-3xl mx-auto space-y-6">
    
    <div class="flex items-center justify-between bg-white rounded-2xl p-4 sm:p-5 border border-gray-200 shadow-2xs">
        <div class="flex items-center space-x-2 text-xs font-semibold text-gray-500">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-700">HQ Dashboard</a>
            <span>&bull;</span>
            <a href="{{ route('admin.orders.index') }}" class="hover:text-blue-700">Orders</a>
            <span>&bull;</span>
            <span class="text-blue-700 font-bold truncate max-w-xs font-mono">{{ $order->order_number }}</span>
        </div>
        <div class="flex items-center space-x-2">
            <button type="button" onclick="window.print()" class="px-3 py-1.5 rounded-lg text-xs font-bold text-gray-700 bg-gray-50 hover:bg-gray-100 border border-gray-200 transition">
                Print Invoice
            </button>
            <a href="{{ route('admin.orders.index') }}" class="text-xs font-bold text-gray-600 hover:text-gray-900 transition">
                ← Back to Orders
            </a>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 sm:p-8 border border-gray-200 shadow-2xs space-y-6">
        
        <div class="flex items-center justify-between pb-6 border-b border-gray-200">
            <div>
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wide">Official GST Tax Invoice</span>
                <h1 class="text-2xl font-bold text-gray-900 font-mono">{{ $order->order_number }}</h1>
                <span class="text-xs text-gray-400">Recorded on {{ $order->created_at->format('d M, Y h:i A') }}</span>
            </div>
            <span class="px-3.5 py-1 rounded-full text-xs font-bold uppercase bg-emerald-50 text-emerald-800 border border-emerald-200">
                ● {{ $order->status }}
            </span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-xs">
            <div class="space-y-1">
                <span class="text-gray-400 font-bold uppercase block tracking-wider text-[10px]">Billed To Candidate:</span>
                <strong class="text-sm font-bold text-gray-900 block">{{ $order->user ? $order->user->name : 'N/A' }}</strong>
                <span class="text-gray-600 block font-mono">{{ $order->user ? $order->user->email : '' }}</span>
                <span class="text-gray-600 block font-mono">{{ $order->user ? $order->user->phone : '' }}</span>
            </div>

            <div class="space-y-1 sm:text-right">
                <span class="text-gray-400 font-bold uppercase block tracking-wider text-[10px]">Gateway &amp; Audit Reference:</span>
                <span class="text-gray-800 block font-semibold">Channel: <strong class="uppercase font-mono">{{ $order->payment_gateway }}</strong></span>
                <span class="text-gray-800 block font-mono">Ref ID: {{ $order->payment_id ?? 'N/A' }}</span>
                @if($meta)
                    <span class="text-gray-600 block font-mono text-[11px]">Meta: {{ $meta }}</span>
                @endif
                <span class="text-emerald-700 font-bold block">Paid: {{ $order->paid_at ? $order->paid_at->format('d M, Y h:i A') : 'Pending' }}</span>
            </div>
        </div>

        <!-- Purchased Item Card -->
        <div class="p-5 rounded-xl bg-gray-50 border border-gray-200 flex items-center justify-between text-xs">
            <div>
                <span class="text-[10px] text-gray-400 font-bold uppercase">
                    {{ $order->course ? 'Course Batch Enrollment' : ($order->mockTest ? 'CBT Mock Test Series' : 'Platform Subscription') }}
                </span>
                <h3 class="text-base font-bold text-gray-900 mt-0.5">
                    {{ $order->course ? $order->course->title : ($order->mockTest ? $order->mockTest->title : 'Academy Access') }}
                </h3>
                <span class="text-gray-500 text-[11px]">SAC Code: 999293 (Educational Services)</span>
            </div>
            <div class="text-right">
                @if($discount > 0)
                    <span class="text-xs text-gray-400 line-through block font-mono">₹ {{ number_format($original, 2) }}</span>
                @endif
                <span class="text-xl font-bold text-gray-900 font-mono">₹ {{ number_format($order->amount, 2) }}</span>
            </div>
        </div>

        @if($coupon && $discount > 0)
            <div class="p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-xs text-emerald-800 flex items-center justify-between">
                <span>Applied Scholarship / Promo Concession (<strong>{{ $coupon }}</strong>):</span>
                <span class="font-bold font-mono">-₹ {{ number_format($discount, 2) }}</span>
            </div>
        @endif

        <div class="pt-2 flex justify-between items-center text-xs">
            <a href="{{ route('student.orders.receipt', $order->id) }}" target="_blank" class="text-blue-700 font-bold hover:underline">
                View Public Candidate Invoice Receipt ↗
            </a>

            @if($order->user_id)
                <a href="{{ route('admin.students.show', $order->user_id) }}" class="px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-blue-700 hover:bg-blue-800 transition shadow-2xs">
                    View Cadet Profile →
                </a>
            @endif
        </div>

    </div>

</div>
@endsection
