@extends('layouts.admin')

@section('title', 'Orders & Financial Audits — Admin Command')
@section('header_title', 'Revenue &amp; Financial Audits')

@section('content')
<div class="space-y-6">
    
    <!-- Action Bar & Financial Summary Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        
        <!-- Total Platform Revenue -->
        <div class="bg-white rounded-2xl p-5 border border-gray-200 shadow-2xs">
            <span class="text-[11px] text-gray-500 font-bold uppercase tracking-wider block mb-1">Total Verified Revenue</span>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl font-bold text-emerald-700 font-mono">₹ {{ number_format($totalRevenue, 2) }}</span>
                <span class="text-[10px] font-bold text-emerald-800 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">GST SAC 999293</span>
            </div>
        </div>

        <!-- Paid Orders Count -->
        <div class="bg-white rounded-2xl p-5 border border-gray-200 shadow-2xs">
            <span class="text-[11px] text-gray-500 font-bold uppercase tracking-wider block mb-1">Paid Transactions</span>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl font-bold text-gray-900 font-mono">{{ number_format($paidCount ?? $orders->total()) }}</span>
                <span class="text-[10px] font-bold text-blue-700 bg-blue-50 px-2 py-0.5 rounded border border-blue-200">Settled</span>
            </div>
        </div>

        <!-- Today's Revenue -->
        <div class="bg-white rounded-2xl p-5 border border-gray-200 shadow-2xs">
            <span class="text-[11px] text-gray-500 font-bold uppercase tracking-wider block mb-1">Today's Collections</span>
            <div class="flex items-baseline justify-between">
                <span class="text-2xl font-bold text-indigo-700 font-mono">₹ {{ number_format($todayRevenue ?? 0, 2) }}</span>
                <span class="text-[10px] font-bold text-indigo-800 bg-indigo-50 px-2 py-0.5 rounded border border-indigo-200">Live Today</span>
            </div>
        </div>

    </div>

    <!-- Filter & Search Toolbar -->
    <div class="bg-white rounded-2xl p-4 border border-gray-200 shadow-2xs">
        <form action="{{ route('admin.orders.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
            
            <!-- Keyword Search -->
            <div class="relative flex-grow min-w-[240px]">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </span>
                <input type="text" name="search" placeholder="Search order number, payment reference ID, cadet name or email..." value="{{ request('search') }}" 
                       class="w-full pl-9 pr-4 py-2.5 text-xs bg-gray-50 border border-gray-300 rounded-xl outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white focus:border-transparent transition">
            </div>

            <!-- Gateway Filter -->
            <select name="gateway" onchange="this.form.submit()" class="text-xs bg-gray-50 border border-gray-300 rounded-xl px-3.5 py-2.5 outline-none focus:ring-2 focus:ring-blue-600">
                <option value="">All Payment Gateways</option>
                <option value="upi" {{ request('gateway') === 'upi' ? 'selected' : '' }}>UPI / QR Intent</option>
                <option value="card" {{ request('gateway') === 'card' ? 'selected' : '' }}>Debit / Credit Card</option>
                <option value="netbanking" {{ request('gateway') === 'netbanking' ? 'selected' : '' }}>Net Banking</option>
                <option value="emi" {{ request('gateway') === 'emi' ? 'selected' : '' }}>0% No-Cost EMI</option>
                <option value="mock" {{ request('gateway') === 'mock' ? 'selected' : '' }}>Instant Sandbox Mode</option>
            </select>

            <!-- Status Filter -->
            <select name="status" onchange="this.form.submit()" class="text-xs bg-gray-50 border border-gray-300 rounded-xl px-3.5 py-2.5 outline-none focus:ring-2 focus:ring-blue-600">
                <option value="">All Payment Statuses</option>
                <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid &amp; Verified</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending Handshake</option>
                <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed Transaction</option>
            </select>

            <button type="submit" class="px-4 py-2.5 rounded-xl text-xs font-bold bg-slate-900 hover:bg-slate-800 text-white transition shadow-2xs">
                Audit Filter
            </button>

            @if(request()->hasAny(['search', 'gateway', 'status']))
                <a href="{{ route('admin.orders.index') }}" class="text-xs font-bold text-gray-500 hover:text-red-600 transition pl-1">
                    Clear Filters ✕
                </a>
            @endif
        </form>
    </div>

    <!-- Orders Table with strict whitespace-nowrap -->
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-2xs">
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left text-xs text-gray-600">
                <thead class="bg-gray-50 text-gray-500 uppercase text-[10px] font-bold border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 whitespace-nowrap">Order Number</th>
                        <th class="px-6 py-4 whitespace-nowrap">Cadet Details</th>
                        <th class="px-6 py-4 whitespace-nowrap">Item Description</th>
                        <th class="px-6 py-4 whitespace-nowrap">Gateway Channel</th>
                        <th class="px-6 py-4 whitespace-nowrap">Amount (INR)</th>
                        <th class="px-6 py-4 whitespace-nowrap">Audit Status</th>
                        <th class="px-6 py-4 whitespace-nowrap">Timestamp</th>
                        <th class="px-6 py-4 whitespace-nowrap text-right">Invoice</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($orders as $ord)
                        <tr class="hover:bg-gray-50/70 transition">
                            <!-- Order Number -->
                            <td class="px-6 py-4 whitespace-nowrap font-mono font-bold text-gray-900">
                                <a href="{{ route('admin.orders.show', $ord->id) }}" class="hover:text-blue-700 transition">
                                    {{ $ord->order_number }}
                                </a>
                            </td>

                            <!-- Cadet Details -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center space-x-2.5">
                                    <div class="w-7 h-7 rounded-lg bg-blue-50 text-blue-800 font-bold text-xs flex items-center justify-center shrink-0 border border-blue-100">
                                        {{ strtoupper(substr($ord->user ? $ord->user->name : 'N', 0, 1)) }}
                                    </div>
                                    <div>
                                        <span class="font-bold text-gray-900 block">{{ $ord->user ? $ord->user->name : 'N/A' }}</span>
                                        <span class="text-[10px] text-gray-400 block font-mono">{{ $ord->user ? $ord->user->email : '' }}</span>
                                    </div>
                                </div>
                            </td>

                            <!-- Item Description -->
                            <td class="px-6 py-4 whitespace-nowrap text-gray-800">
                                @if($ord->course)
                                    <div class="flex items-center space-x-1.5">
                                        <span class="px-2 py-0.5 rounded text-[9px] font-bold bg-blue-50 text-blue-700 border border-blue-100">COURSE</span>
                                        <span class="font-medium text-gray-900 max-w-[200px] truncate" title="{{ $ord->course->title }}">{{ $ord->course->title }}</span>
                                    </div>
                                @elseif($ord->mockTest)
                                    <div class="flex items-center space-x-1.5">
                                        <span class="px-2 py-0.5 rounded text-[9px] font-bold bg-sky-50 text-sky-700 border border-sky-100">CBT TEST</span>
                                        <span class="font-medium text-gray-900 max-w-[200px] truncate" title="{{ $ord->mockTest->title }}">{{ $ord->mockTest->title }}</span>
                                    </div>
                                @else
                                    <span class="text-gray-500 font-medium">Subscription Access</span>
                                @endif
                            </td>

                            <!-- Gateway Channel -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="space-y-0.5">
                                    <span class="px-2.5 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider bg-gray-100 text-gray-700 border border-gray-200 inline-block">
                                        {{ $ord->payment_gateway }}
                                    </span>
                                    <span class="text-[10px] text-gray-400 block font-mono">{{ $ord->payment_id ?? 'N/A' }}</span>
                                </div>
                            </td>

                            <!-- Amount -->
                            <td class="px-6 py-4 whitespace-nowrap font-bold text-gray-900 font-mono text-sm">
                                ₹ {{ number_format($ord->amount, 2) }}
                            </td>

                            <!-- Status -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $ord->status === 'paid' ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : ($ord->status === 'pending' ? 'bg-amber-50 text-amber-800 border border-amber-200' : 'bg-red-50 text-red-800 border border-red-200') }}">
                                    ● {{ $ord->status }}
                                </span>
                            </td>

                            <!-- Timestamp -->
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-400 text-[11px]">
                                {{ $ord->paid_at ? $ord->paid_at->format('M d, Y h:i A') : $ord->created_at->format('M d, Y h:i A') }}
                            </td>

                            <!-- Invoice Action -->
                            <td class="px-6 py-4 whitespace-nowrap text-right space-x-2 font-bold">
                                <a href="{{ route('admin.orders.show', $ord->id) }}" class="px-3 py-1.5 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-700 transition">
                                    Audit Receipt
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-xs text-gray-400">
                                <svg class="w-10 h-10 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                No financial orders found matching your search.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
            <div class="p-4 border-t border-gray-200 bg-gray-50/50">
                {{ $orders->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
