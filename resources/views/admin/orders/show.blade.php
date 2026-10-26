@extends('layouts.admin')

@section('title', 'Order Receipt #' . $order->order_number . ' — Admin')
@section('header_title', 'Order Receipt #' . $order->order_number)

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    
    <div>
        <a href="{{ route('admin.orders.index') }}" class="text-xs font-bold text-blue-700 hover:underline">
            ← Back to Orders
        </a>
    </div>

    <div class="bg-white rounded-3xl p-6 sm:p-10 border border-gray-100 shadow-xl space-y-6">
        
        <div class="flex items-center justify-between pb-6 border-b border-gray-100">
            <div>
                <span class="text-xs font-bold text-gray-400 uppercase">Official Receipt</span>
                <h1 class="text-2xl font-black text-gray-900 font-mono">{{ $order->order_number }}</h1>
                <span class="text-xs text-gray-400">Recorded on {{ $order->created_at->format('d M, Y h:i A') }}</span>
            </div>
            <span class="px-4 py-1.5 rounded-full text-xs font-extrabold uppercase bg-emerald-50 text-emerald-800 border border-emerald-200">
                {{ $order->status }}
            </span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-xs">
            <div class="space-y-1">
                <span class="text-gray-400 font-semibold uppercase block">Billed To Cadet:</span>
                <strong class="text-sm font-bold text-gray-900 block">{{ $order->user ? $order->user->name : 'N/A' }}</strong>
                <span class="text-gray-600 block">{{ $order->user ? $order->user->email : '' }}</span>
                <span class="text-gray-600 block">{{ $order->user ? $order->user->phone : '' }}</span>
            </div>

            <div class="space-y-1 sm:text-right">
                <span class="text-gray-400 font-semibold uppercase block">Payment Gateway Details:</span>
                <span class="text-gray-800 block font-semibold">Gateway: <strong class="uppercase">{{ $order->payment_gateway }}</strong></span>
                <span class="text-gray-800 block font-mono">Ref ID: {{ $order->payment_id ?? 'N/A' }}</span>
                <span class="text-emerald-700 font-bold block">Paid: {{ $order->paid_at ? $order->paid_at->format('d M, Y h:i A') : '-' }}</span>
            </div>
        </div>

        <!-- Course Summary -->
        <div class="p-5 rounded-2xl bg-gray-50 border border-gray-100 flex items-center justify-between text-xs">
            <div>
                <span class="text-[10px] text-gray-400 font-bold uppercase">Enrolled Course</span>
                <h3 class="text-base font-bold text-gray-900">{{ $order->course ? $order->course->title : 'N/A' }}</h3>
                <span class="text-gray-500">{{ $order->course ? $order->course->subtitle : '' }}</span>
            </div>
            <span class="text-xl font-black text-gray-900">₹ {{ number_format($order->amount, 0) }}</span>
        </div>

        <div class="pt-4 flex justify-end">
            <a href="{{ route('admin.students.show', $order->user_id) }}" class="px-6 py-2.5 rounded-full text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 transition">
                View Student Profile →
            </a>
        </div>

    </div>

</div>
@endsection
