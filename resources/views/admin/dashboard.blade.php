@extends('layouts.admin')

@section('title', 'Admin Command Overview — Nirmaan')
@section('header_title', 'Operational Overview & Key Metrics')

@section('content')
<div class="space-y-8">
    
    <!-- KPI Row -->
    <div class="grid grid-cols-2 lg:grid-cols-6 gap-4">
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-2xs">
            <span class="text-xs text-gray-400 font-semibold block">Total Students</span>
            <span class="text-2xl font-black text-gray-900 mt-1 block">{{ $totalStudents }}</span>
            <span class="text-[10px] text-emerald-600 font-bold">Registered Cadets</span>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-2xs">
            <span class="text-xs text-gray-400 font-semibold block">Active Enrollments</span>
            <span class="text-2xl font-black text-blue-700 mt-1 block">{{ $totalEnrollments }}</span>
            <span class="text-[10px] text-blue-600 font-bold">Active Subscriptions</span>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-2xs">
            <span class="text-xs text-gray-400 font-semibold block">Courses</span>
            <span class="text-2xl font-black text-gray-900 mt-1 block">{{ $totalCourses }}</span>
            <span class="text-[10px] text-gray-500 font-bold">Published Batches</span>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-2xs">
            <span class="text-xs text-gray-400 font-semibold block">Questions</span>
            <span class="text-2xl font-black text-gray-900 mt-1 block">{{ $totalQuestions }}</span>
            <span class="text-[10px] text-gray-500 font-bold">Question Bank</span>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-2xs">
            <span class="text-xs text-gray-400 font-semibold block">Mock Tests</span>
            <span class="text-2xl font-black text-gray-900 mt-1 block">{{ $totalMockTests }}</span>
            <span class="text-[10px] text-gray-500 font-bold">Simulated CBTs</span>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-2xs">
            <span class="text-xs text-gray-400 font-semibold block">Total Revenue</span>
            <span class="text-2xl font-black text-emerald-600 mt-1 block">₹ {{ number_format($totalRevenue, 0) }}</span>
            <span class="text-[10px] text-emerald-700 font-bold">Verified INR</span>
        </div>
    </div>

    <!-- Middle: Recent Orders & Student Signups -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Recent Orders (8 cols) -->
        <div class="lg:col-span-8 bg-white rounded-3xl p-6 border border-gray-100 shadow-xs">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-gray-900">Recent Gateway Orders</h3>
                <a href="{{ route('admin.orders.index') }}" class="text-xs font-bold text-blue-700 hover:underline">
                    View All Orders →
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-gray-600">
                    <thead class="bg-gray-50/70 text-gray-400 uppercase text-[10px] font-bold border-b border-gray-100">
                        <tr>
                            <th class="px-4 py-3">Order #</th>
                            <th class="px-4 py-3">Student</th>
                            <th class="px-4 py-3">Course</th>
                            <th class="px-4 py-3">Amount</th>
                            <th class="px-4 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($recentOrders as $ord)
                            <tr class="hover:bg-gray-50/50">
                                <td class="px-4 py-3 font-mono font-bold text-gray-800">{{ $ord->order_number }}</td>
                                <td class="px-4 py-3 font-semibold text-gray-900">{{ $ord->user ? $ord->user->name : 'N/A' }}</td>
                                <td class="px-4 py-3 text-gray-700">{{ $ord->course ? $ord->course->title : 'N/A' }}</td>
                                <td class="px-4 py-3 font-black text-gray-900">₹ {{ number_format($ord->amount, 0) }}</td>
                                <td class="px-4 py-3">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-emerald-50 text-emerald-800 border border-emerald-200">
                                        {{ $ord->status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-6 text-center text-xs text-gray-400 italic">No orders yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Students (4 cols) -->
        <div class="lg:col-span-4 bg-white rounded-3xl p-6 border border-gray-100 shadow-xs">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-gray-900">New Cadets</h3>
                <a href="{{ route('admin.students.index') }}" class="text-xs font-bold text-blue-700 hover:underline">
                    Manage
                </a>
            </div>

            <div class="space-y-3">
                @foreach($recentStudents as $st)
                    <div class="p-3 rounded-2xl bg-gray-50/80 border border-gray-100 flex items-center justify-between">
                        <div class="flex items-center space-x-3 truncate mr-2">
                            <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-800 font-bold text-xs flex items-center justify-center flex-shrink-0">
                                {{ strtoupper(substr($st->name, 0, 1)) }}
                            </div>
                            <div class="truncate">
                                <h4 class="text-xs font-bold text-gray-900 truncate">{{ $st->name }}</h4>
                                <span class="text-[10px] text-gray-400 truncate block">{{ $st->target_exam ?? $st->email }}</span>
                            </div>
                        </div>
                        <a href="{{ route('admin.students.show', $st->id) }}" class="text-[11px] font-bold text-blue-700 hover:underline flex-shrink-0">
                            View
                        </a>
                    </div>
                @endforeach
            </div>
        </div>

    </div>

    <!-- Quick Shortcuts Card -->
    <div class="bg-gradient-to-r from-slate-900 to-blue-950 text-white rounded-3xl p-6 sm:p-8 flex flex-col sm:flex-row items-center justify-between gap-6 shadow-xl">
        <div>
            <h3 class="text-xl font-bold">Quick Administrative Actions</h3>
            <p class="text-xs text-slate-300 mt-1">Publish new courses, add questions to the national repository, or launch mock tests.</p>
        </div>
        <div class="flex flex-wrap gap-3 flex-shrink-0">
            <a href="{{ route('admin.courses.create') }}" class="px-5 py-2.5 rounded-full text-xs font-bold bg-blue-600 hover:bg-blue-500 text-white shadow-sm transition">
                + New Course
            </a>
            <a href="{{ route('admin.questions.create') }}" class="px-5 py-2.5 rounded-full text-xs font-bold bg-white/10 hover:bg-white/20 text-white border border-white/20 transition">
                + Add Question
            </a>
            <a href="{{ route('admin.tests.create') }}" class="px-5 py-2.5 rounded-full text-xs font-bold bg-emerald-600 hover:bg-emerald-500 text-white shadow-sm transition">
                + Launch Mock Test
            </a>
        </div>
    </div>

</div>
@endsection
