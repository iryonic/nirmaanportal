@extends('layouts.student')

@section('title', 'Payment Receipts & Order History — Nirmaan')

@section('content')
<div class="py-8 sm:py-10">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-slate-200">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900">Payment &amp; Order History</h1>
                <p class="text-xs text-slate-500 mt-0.5">Official GST tax invoices, course enrollments, and test series transaction records.</p>
            </div>
            <div>
                <a href="{{ route('courses.index') }}" class="inline-flex items-center space-x-1.5 px-3.5 py-2 rounded-lg text-xs font-medium text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 transition-colors shadow-2xs">
                    <span>Explore New Batches</span>
                </a>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-2xs">
            @if($orders->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-600">
                        <thead class="bg-slate-50 text-slate-500 uppercase text-[10px] font-semibold border-b border-slate-200 tracking-wider">
                            <tr>
                                <th class="px-5 py-3">Order ID</th>
                                <th class="px-5 py-3">Item Description</th>
                                <th class="px-5 py-3">Category</th>
                                <th class="px-5 py-3">Amount</th>
                                <th class="px-5 py-3">Gateway</th>
                                <th class="px-5 py-3">Date</th>
                                <th class="px-5 py-3">Status</th>
                                <th class="px-5 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($orders as $ord)
                                <tr class="hover:bg-slate-50/70 transition-colors">
                                    <td class="px-5 py-3.5 font-mono font-semibold text-slate-900">
                                        <a href="{{ route('student.orders.receipt', $ord->id) }}" class="text-blue-950 hover:underline">
                                            {{ $ord->order_number }}
                                        </a>
                                    </td>
                                    <td class="px-5 py-3.5 font-medium text-slate-900 max-w-xs truncate">
                                        @if($ord->course)
                                            {{ $ord->course->title }}
                                        @elseif($ord->mockTest)
                                            {{ $ord->mockTest->title }}
                                        @else
                                            General Subscription
                                        @endif
                                    </td>
                                    <td class="px-5 py-3.5">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-semibold {{ $ord->course_id ? 'bg-blue-50 text-blue-900 border border-blue-200' : 'bg-purple-50 text-purple-900 border border-purple-200' }}">
                                            {{ $ord->course_id ? 'Course Batch' : 'CBT Test Series' }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3.5 font-bold text-slate-900 font-mono">
                                        ₹{{ number_format($ord->amount, 2) }}
                                    </td>
                                    <td class="px-5 py-3.5 uppercase font-medium text-slate-500 text-[11px]">
                                        {{ $ord->payment_gateway }}
                                    </td>
                                    <td class="px-5 py-3.5 text-slate-500 text-[11px] whitespace-nowrap">
                                        {{ $ord->paid_at ? $ord->paid_at->format('d M, Y') : $ord->created_at->format('d M, Y') }}
                                    </td>
                                    <td class="px-5 py-3.5 whitespace-nowrap">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-semibold uppercase {{ $ord->status === 'paid' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                                            {{ $ord->status }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3.5 text-right space-x-1.5 whitespace-nowrap">
                                        <a href="{{ route('student.orders.receipt', $ord->id) }}" class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 transition-colors shadow-2xs">
                                            Receipt
                                        </a>
                                        @if($ord->course_id)
                                            <a href="{{ route('student.courses.learn', $ord->course_id) }}" class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium text-white bg-blue-950 hover:bg-blue-900 transition-colors shadow-2xs">
                                                LMS →
                                            </a>
                                        @elseif($ord->mock_test_id)
                                            <a href="{{ route('student.tests.instructions', $ord->mock_test_id) }}" class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium text-white bg-blue-950 hover:bg-blue-900 transition-colors shadow-2xs">
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
                    <div class="p-3.5 border-t border-slate-100">
                        {{ $orders->links() }}
                    </div>
                @endif
            @else
                <div class="p-12 text-center space-y-2">
                    <p class="text-sm font-semibold text-slate-700">No payment receipts found</p>
                    <p class="text-xs text-slate-400">Transactions and batch enrollments will appear here once completed.</p>
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
