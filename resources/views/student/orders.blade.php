@extends('layouts.student')

@section('title', 'Payment Receipts & Order History — Nirmaan')

@section('content')
<div class="py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900">Payment &amp; Order History</h1>
            <p class="text-xs text-gray-500 mt-1">Review all your batch enrollment receipts and transaction records.</p>
        </div>

        <div class="bg-white rounded-3xl border border-gray-100 overflow-hidden shadow-xs">
            @if($orders->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-gray-600">
                        <thead class="bg-gray-50/70 text-gray-400 uppercase text-[10px] font-bold border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-4">Order ID</th>
                                <th class="px-6 py-4">Course Enrolled</th>
                                <th class="px-6 py-4">Amount</th>
                                <th class="px-6 py-4">Gateway</th>
                                <th class="px-6 py-4">Date</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4 text-right">Receipt</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($orders as $ord)
                                <tr class="hover:bg-gray-50/50 transition">
                                    <td class="px-6 py-4 font-mono font-bold text-gray-800">{{ $ord->order_number }}</td>
                                    <td class="px-6 py-4 font-bold text-gray-900">{{ $ord->course ? $ord->course->title : 'Course' }}</td>
                                    <td class="px-6 py-4 font-black text-gray-900">₹ {{ number_format($ord->amount, 0) }}</td>
                                    <td class="px-6 py-4 uppercase font-semibold text-gray-500">{{ $ord->payment_gateway }}</td>
                                    <td class="px-6 py-4 text-gray-400">{{ $ord->paid_at ? $ord->paid_at->format('d M, Y') : $ord->created_at->format('d M, Y') }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase {{ $ord->status === 'paid' ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-amber-50 text-amber-800' }}">
                                            {{ $ord->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('student.courses.learn', $ord->course_id) }}" class="text-xs font-bold text-blue-700 hover:underline">
                                            Access Course →
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-t border-gray-100">
                    {{ $orders->links() }}
                </div>
            @else
                <div class="p-12 text-center text-xs text-gray-400 italic">
                    No transactions recorded yet.
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
