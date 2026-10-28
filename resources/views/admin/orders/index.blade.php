@extends('layouts.admin')

@section('title', 'Orders & Financial Audits — Admin Command')
@section('header_title', 'Orders')

@section('content')
<div class="space-y-5">
    
    <!-- Action Bar & Financial Summary Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4">
        
        <!-- Total Platform Revenue -->
        <div class="bg-white rounded-xl p-4 sm:p-5 border border-slate-200/80 shadow-2xs hover:border-slate-300 transition-colors">
            <span class="text-[11px] text-slate-500 font-semibold uppercase tracking-wider block mb-1">Total Verified Revenue</span>
            <div class="flex items-baseline justify-between">
                <span class="text-xl sm:text-2xl font-bold text-slate-900 font-mono tracking-tight">₹ {{ number_format($totalRevenue, 2) }}</span>
                <span class="text-[10px] font-medium text-slate-600 bg-slate-100 px-2 py-0.5 rounded border border-slate-200">GST SAC 999293</span>
            </div>
        </div>

        <!-- Paid Orders Count -->
        <div class="bg-white rounded-xl p-4 sm:p-5 border border-slate-200/80 shadow-2xs hover:border-slate-300 transition-colors">
            <span class="text-[11px] text-slate-500 font-semibold uppercase tracking-wider block mb-1">Paid Transactions</span>
            <div class="flex items-baseline justify-between">
                <span class="text-xl sm:text-2xl font-bold text-slate-900 font-mono tracking-tight">{{ number_format($paidCount ?? $orders->total()) }}</span>
                <span class="text-[10px] font-medium text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200/60">Settled</span>
            </div>
        </div>

        <!-- Today's Revenue -->
        <div class="bg-white rounded-xl p-4 sm:p-5 border border-slate-200/80 shadow-2xs hover:border-slate-300 transition-colors">
            <span class="text-[11px] text-slate-500 font-semibold uppercase tracking-wider block mb-1">Today's Collections</span>
            <div class="flex items-baseline justify-between">
                <span class="text-xl sm:text-2xl font-bold text-slate-900 font-mono tracking-tight">₹ {{ number_format($todayRevenue ?? 0, 2) }}</span>
                <span class="text-[10px] font-medium text-blue-950 bg-blue-50 px-2 py-0.5 rounded border border-blue-200/60">Today</span>
            </div>
        </div>

    </div>

    <!-- Filter & Search Toolbar -->
    <div class="bg-white rounded-xl p-3.5 border border-slate-200/80 shadow-2xs">
        <form action="{{ route('admin.orders.index') }}" method="GET" class="flex flex-wrap items-center gap-2.5">
            
            <!-- Keyword Search -->
            <div class="relative flex-grow min-w-[220px]">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </span>
                <input type="text" name="search" placeholder="Search order number, payment ref, cadet name or email..." value="{{ request('search') }}" 
                       class="w-full pl-8 pr-3 py-1.5 text-xs bg-slate-50/70 border border-slate-200 rounded-lg outline-none focus:border-blue-950 focus:bg-white focus:ring-1 focus:ring-blue-950/20 transition-colors">
            </div>

            <!-- Gateway Filter -->
            <select name="gateway" onchange="this.form.submit()" class="text-xs bg-slate-50/70 border border-slate-200 rounded-lg px-3 py-1.5 outline-none focus:border-blue-950 focus:bg-white">
                <option value="">All Gateways</option>
                <option value="upi" {{ request('gateway') === 'upi' ? 'selected' : '' }}>UPI / QR Intent</option>
                <option value="card" {{ request('gateway') === 'card' ? 'selected' : '' }}>Card</option>
                <option value="netbanking" {{ request('gateway') === 'netbanking' ? 'selected' : '' }}>Net Banking</option>
                <option value="emi" {{ request('gateway') === 'emi' ? 'selected' : '' }}>No-Cost EMI</option>
                <option value="mock" {{ request('gateway') === 'mock' ? 'selected' : '' }}>Sandbox Mode</option>
            </select>

            <!-- Status Filter -->
            <select name="status" onchange="this.form.submit()" class="text-xs bg-slate-50/70 border border-slate-200 rounded-lg px-3 py-1.5 outline-none focus:border-blue-950 focus:bg-white">
                <option value="">All Statuses</option>
                <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid &amp; Verified</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
            </select>

            <button type="submit" class="px-3.5 py-1.5 rounded-lg text-xs font-semibold bg-slate-900 hover:bg-slate-800 text-white transition-colors shadow-2xs">
                Audit Filter
            </button>

            @if(request()->hasAny(['search', 'gateway', 'status']))
                <a href="{{ route('admin.orders.index') }}" class="text-xs font-semibold text-slate-500 hover:text-red-600 transition-colors pl-1">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-xl border border-slate-200/80 overflow-hidden shadow-2xs">
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50/75 text-slate-500 uppercase text-[10px] font-semibold tracking-wider border-b border-slate-200/80">
                    <tr>
                        <th class="px-4 py-2.5 whitespace-nowrap">Order Number</th>
                        <th class="px-4 py-2.5 whitespace-nowrap">Cadet Details</th>
                        <th class="px-4 py-2.5 whitespace-nowrap">Item Description</th>
                        <th class="px-4 py-2.5 whitespace-nowrap">Gateway</th>
                        <th class="px-4 py-2.5 whitespace-nowrap">Amount (INR)</th>
                        <th class="px-4 py-2.5 whitespace-nowrap">Audit Status</th>
                        <th class="px-4 py-2.5 whitespace-nowrap">Timestamp</th>
                        <th class="px-4 py-2.5 whitespace-nowrap text-right">Invoice</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($orders as $ord)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <!-- Order Number -->
                            <td class="px-4 py-3 whitespace-nowrap font-mono font-medium text-slate-900">
                                <a href="{{ route('admin.orders.show', $ord->id) }}" class="hover:text-blue-950 transition-colors">
                                    {{ $ord->order_number }}
                                </a>
                            </td>

                            <!-- Cadet Details -->
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="flex items-center space-x-2">
                                    <div class="w-6 h-6 rounded-md bg-slate-100 text-slate-700 font-semibold text-[11px] flex items-center justify-center shrink-0 border border-slate-200">
                                        {{ strtoupper(substr($ord->user ? $ord->user->name : 'N', 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <span class="font-medium text-slate-900 block truncate">{{ $ord->user ? $ord->user->name : 'N/A' }}</span>
                                        <span class="text-[10px] text-slate-400 block font-mono truncate">{{ $ord->user ? $ord->user->email : '' }}</span>
                                    </div>
                                </div>
                            </td>

                            <!-- Item Description -->
                            <td class="px-4 py-3 whitespace-nowrap text-slate-800">
                                @if($ord->course)
                                    <div class="flex items-center space-x-1.5">
                                        <span class="px-1.5 py-0.2 rounded text-[9px] font-semibold bg-blue-50 text-blue-950 border border-blue-200/60 uppercase">Course</span>
                                        <span class="font-medium text-slate-900 max-w-[180px] truncate" title="{{ $ord->course->title }}">{{ $ord->course->title }}</span>
                                    </div>
                                @elseif($ord->mockTest)
                                    <div class="flex items-center space-x-1.5">
                                        <span class="px-1.5 py-0.2 rounded text-[9px] font-semibold bg-sky-50 text-sky-900 border border-sky-200/60 uppercase">Test</span>
                                        <span class="font-medium text-slate-900 max-w-[180px] truncate" title="{{ $ord->mockTest->title }}">{{ $ord->mockTest->title }}</span>
                                    </div>
                                @else
                                    <span class="text-slate-500 font-medium">Subscription Access</span>
                                @endif
                            </td>

                            <!-- Gateway Channel -->
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="flex items-center space-x-1.5">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-medium uppercase font-mono {{ $ord->payment_gateway === 'razorpay' ? 'bg-blue-50 text-blue-900 border border-blue-200 font-bold' : 'bg-slate-100 text-slate-700 border border-slate-200' }}">
                                        {{ $ord->payment_gateway }}
                                    </span>
                                    @if($ord->gateway_mode)
                                        <span class="text-[9px] px-1 py-0.2 rounded font-mono font-bold uppercase {{ $ord->gateway_mode === 'live' ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-600' }}">
                                            {{ $ord->gateway_mode }}
                                        </span>
                                    @endif
                                </div>
                                @if($ord->razorpay_order_id)
                                    <span class="text-[9px] text-slate-400 font-mono block truncate max-w-[130px] mt-0.5" title="{{ $ord->razorpay_order_id }}">{{ $ord->razorpay_order_id }}</span>
                                @endif
                            </td>

                            <!-- Amount -->
                            <td class="px-4 py-3 whitespace-nowrap font-mono font-semibold text-slate-900">
                                ₹ {{ number_format($ord->amount, 2) }}
                            </td>

                            <!-- Audit Status -->
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md text-[10px] font-medium uppercase {{ $ord->status === 'paid' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200/60' : 'bg-amber-50 text-amber-700 border border-amber-200/60' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $ord->status === 'paid' ? 'bg-emerald-500' : 'bg-amber-500' }}"></span>
                                    <span>{{ $ord->status }}</span>
                                </span>
                            </td>

                            <!-- Timestamp -->
                            <td class="px-4 py-3 whitespace-nowrap text-slate-400 font-medium text-[11px]">
                                {{ $ord->created_at->format('M d, Y h:i A') }}
                            </td>

                            <!-- Invoice -->
                            <td class="px-4 py-3 whitespace-nowrap text-right">
                                <a href="{{ route('admin.orders.show', $ord->id) }}" class="px-2.5 py-1 rounded-md text-xs font-medium text-slate-700 hover:text-blue-950 bg-white hover:bg-slate-50 border border-slate-200 transition-colors inline-block shadow-2xs">
                                    View Receipt
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-xs text-slate-400">No transactions recorded.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
            <div class="p-3 border-t border-slate-200/80 bg-slate-50/50">
                {{ $orders->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
