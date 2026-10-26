@extends('layouts.admin')

@section('title', 'Orders & Financial Audits — Admin')
@section('header_title', 'Revenue & Financial Audits')

@section('content')
<div class="space-y-6">
    
    <!-- Action Bar & Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white rounded-3xl p-6 border border-gray-100 shadow-xs">
        <div>
            <div class="flex items-center space-x-2 text-xs text-gray-400 font-semibold mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-700">HQ Dashboard</a>
                <span>&bull;</span>
                <span class="text-blue-700">Finance &amp; Gateway Orders</span>
            </div>
            <h1 class="text-xl font-black text-gray-900 tracking-tight">Orders &amp; Financial Audits</h1>
            <p class="text-xs text-gray-500 mt-0.5">Audit gateway transactions, course payment invoices, and verified platform revenue.</p>
        </div>
        <div class="flex items-center space-x-3">
            <div class="p-3 bg-emerald-50 rounded-2xl border border-emerald-200 text-right">
                <span class="text-[10px] text-emerald-800 font-bold uppercase tracking-wider block">Verified Platform Revenue</span>
                <span class="text-xl font-black text-emerald-700">₹ {{ number_format($totalRevenue, 0) }}</span>
            </div>
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-2xs">
        <form action="{{ route('admin.orders.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
            
            <!-- Keyword Search -->
            <div class="relative flex-grow min-w-[240px]">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </span>
                <input type="text" name="search" placeholder="Search order number, payment ID, or cadet name..." value="{{ request('search') }}" 
                       class="w-full pl-9 pr-4 py-2 text-xs bg-gray-50 border border-gray-200 rounded-xl outline-none focus:border-blue-500 focus:bg-white transition">
            </div>

            <!-- Status Filter -->
            <select name="status" onchange="this.form.submit()" class="text-xs bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2 outline-none focus:border-blue-500">
                <option value="">All Payment Statuses</option>
                <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid &amp; Verified</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
            </select>

            <button type="submit" class="px-4 py-2 rounded-xl text-xs font-bold bg-slate-900 hover:bg-slate-800 text-white transition">
                Filter
            </button>

            @if(request()->hasAny(['search', 'status']))
                <a href="{{ route('admin.orders.index') }}" class="text-xs text-gray-500 hover:text-red-600 transition pl-1">
                    Clear Filters ✕
                </a>
            @endif
        </form>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-3xl border border-gray-100 overflow-hidden shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-gray-600">
                <thead class="bg-gray-50/70 text-gray-400 uppercase text-[10px] font-bold border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4">Order Number</th>
                        <th class="px-6 py-4">Cadet Details</th>
                        <th class="px-6 py-4">Course Enrolled</th>
                        <th class="px-6 py-4">Gateway Reference</th>
                        <th class="px-6 py-4">Amount</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($orders as $ord)
                        <tr class="hover:bg-gray-50/60 transition">
                            <td class="px-6 py-4 font-mono font-bold text-gray-900">
                                <a href="{{ route('admin.orders.show', $ord->id) }}" class="hover:text-blue-700 transition">
                                    {{ $ord->order_number }}
                                </a>
                                <span class="text-[10px] text-gray-400 block font-normal">{{ $ord->created_at->format('M d, Y h:i A') }}</span>
                            </td>
                            <td class="px-6 py-4 font-semibold text-gray-900">
                                <div class="flex items-center space-x-2">
                                    <div class="w-7 h-7 rounded-full bg-blue-100 text-blue-800 font-bold text-xs flex items-center justify-center flex-shrink-0">
                                        {{ strtoupper(substr($ord->user ? $ord->user->name : 'N', 0, 1)) }}
                                    </div>
                                    <div>
                                        <span>{{ $ord->user ? $ord->user->name : 'N/A' }}</span>
                                        <span class="text-[10px] text-gray-400 block">{{ $ord->user ? $ord->user->email : '' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-800">
                                <span class="font-medium max-w-[200px] truncate block" title="{{ $ord->course ? $ord->course->title : 'N/A' }}">
                                    {{ $ord->course ? $ord->course->title : 'N/A' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 font-mono text-gray-500 text-[11px]">
                                {{ $ord->payment_id ?? 'TEST_MODE' }}
                            </td>
                            <td class="px-6 py-4 font-black text-gray-900 text-sm">
                                ₹ {{ number_format($ord->amount, 0) }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase {{ $ord->status === 'paid' ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-amber-50 text-amber-800 border border-amber-200' }}">
                                    {{ $ord->status === 'paid' ? '● Paid' : '● ' . $ord->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right space-x-2 font-bold">
                                <a href="{{ route('admin.orders.show', $ord->id) }}" class="px-3 py-1.5 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-700 transition">
                                    View Receipt
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-xs text-gray-400">
                                <svg class="w-10 h-10 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                No financial orders found matching your search.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
            <div class="p-4 border-t border-gray-100 bg-gray-50/50">
                {{ $orders->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
