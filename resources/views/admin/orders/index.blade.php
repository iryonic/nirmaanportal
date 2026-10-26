@extends('layouts.admin')

@section('title', 'Financial Orders & Payments — Admin')
@section('header_title', 'Orders & Payment Audits')

@section('content')
<div class="space-y-6">
    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-gray-900">Payment Transactions</h1>
            <p class="text-xs text-gray-500 mt-0.5">Audit gateway payment references, student course enrollments, and revenue.</p>
        </div>

        <div class="px-5 py-2.5 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold flex items-center space-x-2">
            <span>Verified Total Revenue:</span>
            <span class="text-base font-black">₹ {{ number_format($totalRevenue, 0) }}</span>
        </div>
    </div>

    <!-- Orders Filter Form -->
    <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-xs flex flex-wrap gap-4 items-center justify-between">
        <form action="{{ route('admin.orders.index') }}" method="GET" class="flex flex-wrap gap-3 items-center">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Order #, student, tx id..." class="text-xs bg-gray-50 border border-gray-200 rounded-xl px-4 py-2 w-60 outline-none focus:border-blue-500">
            <select name="status" onchange="this.form.submit()" class="text-xs bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 outline-none">
                <option value="">All Statuses</option>
                <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-xl text-xs font-bold">Filter</button>
        </form>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-3xl border border-gray-100 overflow-hidden shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-gray-600">
                <thead class="bg-gray-50/70 text-gray-400 uppercase text-[10px] font-bold border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4">Order ID</th>
                        <th class="px-6 py-4">Student</th>
                        <th class="px-6 py-4">Course Enrolled</th>
                        <th class="px-6 py-4">Amount</th>
                        <th class="px-6 py-4">Gateway</th>
                        <th class="px-6 py-4">Transaction ID</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">View</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($orders as $ord)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-6 py-4 font-mono font-bold text-gray-900">{{ $ord->order_number }}</td>
                            <td class="px-6 py-4 font-semibold text-gray-800">
                                {{ $ord->user ? $ord->user->name : 'N/A' }}
                                <span class="text-[10px] text-gray-400 font-normal block">{{ $ord->user ? $ord->user->email : '' }}</span>
                            </td>
                            <td class="px-6 py-4 text-gray-800 font-medium">{{ $ord->course ? $ord->course->title : 'N/A' }}</td>
                            <td class="px-6 py-4 font-black text-gray-900">₹ {{ number_format($ord->amount, 0) }}</td>
                            <td class="px-6 py-4 uppercase font-semibold text-gray-500">{{ $ord->payment_gateway }}</td>
                            <td class="px-6 py-4 font-mono text-[11px] text-gray-500">{{ $ord->payment_id ?? 'N/A' }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase {{ $ord->status === 'paid' ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-amber-50 text-amber-800' }}">
                                    {{ $ord->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.orders.show', $ord->id) }}" class="text-xs font-bold text-blue-700 hover:underline">
                                    Receipt →
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-xs text-gray-400">No orders recorded.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-100">
            {{ $orders->links() }}
        </div>
    </div>

</div>
@endsection
