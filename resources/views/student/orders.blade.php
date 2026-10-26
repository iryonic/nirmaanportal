@extends('layouts.student')

@section('title', 'Payment Receipts & Order History — Nirmaan')

@section('content')
<div class="py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Payment &amp; Order History</h1>
                <p class="text-xs text-gray-500 mt-1 font-medium">Official tax invoices, transaction records, and enrollment receipts.</p>
            </div>
            <div>
                <a href="{{ route('courses.index') }}" class="inline-flex items-center space-x-2 px-4 py-2 rounded-xl text-xs font-bold text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 transition shadow-2xs">
                    <span>Explore New Batches</span>
                </a>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-2xs">
            @if($orders->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-gray-600">
                        <thead class="bg-gray-50 text-gray-500 uppercase text-[10px] font-bold border-b border-gray-200 tracking-wider">
                            <tr>
                                <th class="px-6 py-3.5">Order ID</th>
                                <th class="px-6 py-3.5">Item Description</th>
                                <th class="px-6 py-3.5">Type</th>
                                <th class="px-6 py-3.5">Amount</th>
                                <th class="px-6 py-3.5">Gateway</th>
                                <th class="px-6 py-3.5">Date</th>
                                <th class="px-6 py-3.5">Status</th>
                                <th class="px-6 py-3.5 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($orders as $ord)
                                <tr class="hover:bg-gray-50/70 transition">
                                    <td class="px-6 py-4 font-mono font-bold text-gray-800">
                                        <a href="{{ route('student.orders.receipt', $ord->id) }}" class="text-blue-700 hover:underline">
                                            {{ $ord->order_number }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 font-bold text-gray-900">
                                        @if($ord->course)
                                            {{ $ord->course->title }}
                                        @elseif($ord->mockTest)
                                            {{ $ord->mockTest->title }}
                                        @else
                                            General Subscription
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-bold {{ $ord->course_id ? 'bg-blue-50 text-blue-700 border border-blue-100' : 'bg-purple-50 text-purple-700 border border-purple-100' }}">
                                            {{ $ord->course_id ? 'Course' : 'Mock Test' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 font-black text-gray-900 font-mono">
                                        ₹ {{ number_format($ord->amount, 2) }}
                                    </td>
                                    <td class="px-6 py-4 uppercase font-semibold text-gray-500">
                                        {{ $ord->payment_gateway }}
                                    </td>
                                    <td class="px-6 py-4 text-gray-500">
                                        {{ $ord->paid_at ? $ord->paid_at->format('d M, Y') : $ord->created_at->format('d M, Y') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase {{ $ord->status === 'paid' ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-amber-50 text-amber-800 border border-amber-200' }}">
                                            {{ $ord->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right space-x-2">
                                        <a href="{{ route('student.orders.receipt', $ord->id) }}" class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold text-gray-700 bg-gray-100 hover:bg-gray-200 transition">
                                            Receipt
                                        </a>
                                        @if($ord->course_id)
                                            <a href="{{ route('student.courses.learn', $ord->course_id) }}" class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold text-white bg-blue-700 hover:bg-blue-800 transition">
                                                LMS →
                                            </a>
                                        @elseif($ord->mock_test_id)
                                            <a href="{{ route('student.tests.instructions', $ord->mock_test_id) }}" class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold text-white bg-blue-700 hover:bg-blue-800 transition">
                                                CBT →
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($orders->hasPages())
                    <div class="p-4 border-t border-gray-100">
                        {{ $orders->links() }}
                    </div>
                @endif
            @else
                <div class="p-12 text-center space-y-2">
                    <p class="text-sm font-semibold text-gray-700">No payment receipts found</p>
                    <p class="text-xs text-gray-400">Transactions and batch enrollments will appear here once completed.</p>
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
