@extends('layouts.admin')

@section('title', 'Officer Command Dashboard — Nirmaan Defence Platform')
@section('header_title', 'Dashboard')

@section('content')
<div class="space-y-6">
    
    <!-- Linear-style Clean Command Header -->
    <div class="bg-white rounded-xl p-5 sm:p-6 border border-slate-200/80 shadow-2xs">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="space-y-1">
             
                <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">
                    Welcome back, {{ Auth::user()->name }}
                </h1>
              
            </div>

            <!-- Quick Action Buttons: Linear Clean Style -->
            <div class="flex flex-wrap items-center gap-2 shrink-0">
                <a href="{{ route('admin.courses.create') }}" 
                   class="px-3.5 py-2 rounded-lg text-xs font-semibold bg-blue-950 hover:bg-blue-900 text-white transition-colors flex items-center space-x-1.5 shadow-2xs">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    <span>New Course</span>
                </a>
                <a href="{{ route('admin.tests.create') }}" 
                   class="px-3.5 py-2 rounded-lg text-xs font-semibold bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 transition-colors flex items-center space-x-1.5 shadow-2xs">
                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    <span>Launch CBT</span>
                </a>
                <a href="{{ route('admin.questions.create') }}" 
                   class="px-3.5 py-2 rounded-lg text-xs font-semibold bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 transition-colors flex items-center space-x-1.5 shadow-2xs">
                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    <span>Add Question</span>
                </a>
            </div>
        </div>
    </div>

    <!-- KPI Metrics Grid (6 Columns) -->
    <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-6 gap-3 sm:gap-4">
        
        <!-- Total Cadets -->
        <div class="bg-white rounded-xl p-4 border border-slate-200/80 shadow-2xs hover:border-slate-300 transition-colors">
            <div class="flex items-center justify-between mb-1.5">
                <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Cadets</span>
                <div class="w-6 h-6 rounded-md bg-blue-50 text-blue-950 flex items-center justify-center">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
            </div>
            <span class="text-2xl font-bold text-slate-900 block font-mono tracking-tight">{{ number_format($totalStudents) }}</span>
            <span class="text-[10px] text-slate-500 font-medium flex items-center mt-1">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>
                <span>Verified Aspirants</span>
            </span>
        </div>

        <!-- Active Subscriptions -->
        <div class="bg-white rounded-xl p-4 border border-slate-200/80 shadow-2xs hover:border-slate-300 transition-colors">
            <div class="flex items-center justify-between mb-1.5">
                <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Active Seats</span>
                <div class="w-6 h-6 rounded-md bg-indigo-50 text-indigo-700 flex items-center justify-center">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <span class="text-2xl font-bold text-slate-900 block font-mono tracking-tight">{{ number_format($totalEnrollments) }}</span>
            <span class="text-[10px] text-slate-500 font-medium flex items-center mt-1">
                <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 mr-1.5"></span>
                <span>Active Trainees</span>
            </span>
        </div>

        <!-- Courses -->
        <div class="bg-white rounded-xl p-4 border border-slate-200/80 shadow-2xs hover:border-slate-300 transition-colors">
            <div class="flex items-center justify-between mb-1.5">
                <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Courses</span>
                <div class="w-6 h-6 rounded-md bg-amber-50 text-amber-700 flex items-center justify-center">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
            </div>
            <span class="text-2xl font-bold text-slate-900 block font-mono tracking-tight">{{ $totalCourses }}</span>
            <span class="text-[10px] text-slate-500 font-medium flex items-center mt-1">
                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5"></span>
                <span>Published Batches</span>
            </span>
        </div>

        <!-- Question Bank -->
        <div class="bg-white rounded-xl p-4 border border-slate-200/80 shadow-2xs hover:border-slate-300 transition-colors">
            <div class="flex items-center justify-between mb-1.5">
                <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">MCQ Bank</span>
                <div class="w-6 h-6 rounded-md bg-emerald-50 text-emerald-700 flex items-center justify-center">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <span class="text-2xl font-bold text-slate-900 block font-mono tracking-tight">{{ number_format($totalQuestions) }}</span>
            <span class="text-[10px] text-slate-500 font-medium flex items-center mt-1">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>
                <span>Verified MCQs</span>
            </span>
        </div>

        <!-- CBT Mock Tests -->
        <div class="bg-white rounded-xl p-4 border border-slate-200/80 shadow-2xs hover:border-slate-300 transition-colors">
            <div class="flex items-center justify-between mb-1.5">
                <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">CBT Tests</span>
                <div class="w-6 h-6 rounded-md bg-sky-50 text-sky-700 flex items-center justify-center">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <span class="text-2xl font-bold text-slate-900 block font-mono tracking-tight">{{ $totalMockTests }}</span>
            <span class="text-[10px] text-slate-500 font-medium flex items-center mt-1">
                <span class="w-1.5 h-1.5 rounded-full bg-sky-500 mr-1.5"></span>
                <span>Online Engines</span>
            </span>
        </div>

        <!-- Verified Revenue -->
        <div class="bg-white rounded-xl p-4 border border-slate-200/80 shadow-2xs hover:border-slate-300 transition-colors">
            <div class="flex items-center justify-between mb-1.5">
                <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Gross Revenue</span>
                <div class="w-6 h-6 rounded-md bg-emerald-50 text-emerald-700 flex items-center justify-center">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <span class="text-xl sm:text-2xl font-bold text-slate-900 block font-mono tracking-tight">₹ {{ number_format($totalRevenue, 2) }}</span>
            <span class="text-[10px] text-emerald-700 font-medium flex items-center mt-1">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>
                <span>Verified Receipts</span>
            </span>
        </div>

    </div>

    <!-- Quick Navigation Hub -->
    <div class="bg-white rounded-xl p-3 border border-slate-200/80 shadow-2xs flex flex-wrap items-center justify-between gap-2 text-xs">
        <span class="text-slate-400 text-[11px] uppercase tracking-wider font-semibold pl-1">Quick Navigation:</span>
        <div class="flex flex-wrap items-center gap-1.5 font-medium">
            <a href="{{ route('admin.courses.index') }}" class="px-2.5 py-1 rounded-md bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200 transition-colors">
                Courses
            </a>
            <a href="{{ route('admin.questions.index') }}" class="px-2.5 py-1 rounded-md bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200 transition-colors">
                Question Bank
            </a>
            <a href="{{ route('admin.tests.index') }}" class="px-2.5 py-1 rounded-md bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200 transition-colors">
                Mock Tests
            </a>
            <a href="{{ route('admin.students.index') }}" class="px-2.5 py-1 rounded-md bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200 transition-colors">
                Cadets
            </a>
            <a href="{{ route('admin.orders.index') }}" class="px-2.5 py-1 rounded-md bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200 transition-colors">
                Orders &amp; Audits
            </a>
            <a href="{{ route('admin.notifications.index') }}" class="px-2.5 py-1 rounded-md bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200 transition-colors">
                Notice Board
            </a>
            <a href="{{ route('admin.content.index') }}" class="px-2.5 py-1 rounded-md bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200 transition-colors">
                Content &amp; Settings
            </a>
        </div>
    </div>

    <!-- Middle Row: Orders Audit Table & Recent Aspirants -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- Recent Orders (8 cols) -->
        <div class="lg:col-span-8 bg-white rounded-xl border border-slate-200/80 shadow-2xs overflow-hidden">
            <div class="p-4 sm:p-5 border-b border-slate-200/80 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-semibold text-slate-900">Recent Gateway Transactions</h3>
                </div>
                <a href="{{ route('admin.orders.index') }}" class="text-xs font-semibold text-blue-950 hover:underline transition-colors flex items-center space-x-1">
                    <span>View All</span>
                </a>
            </div>

            <div class="overflow-x-auto w-full">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="bg-slate-50/75 text-slate-500 uppercase text-[10px] font-semibold tracking-wider border-b border-slate-200/80">
                        <tr>
                            <th class="px-4 py-2.5 whitespace-nowrap">Order ID</th>
                            <th class="px-4 py-2.5 whitespace-nowrap">Cadet Name</th>
                            <th class="px-4 py-2.5 whitespace-nowrap">Purchased Item</th>
                            <th class="px-4 py-2.5 whitespace-nowrap">Gateway</th>
                            <th class="px-4 py-2.5 whitespace-nowrap">Amount</th>
                            <th class="px-4 py-2.5 whitespace-nowrap">Status</th>
                            <th class="px-4 py-2.5 whitespace-nowrap text-right">Receipt</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($recentOrders as $ord)
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <td class="px-4 py-3 whitespace-nowrap font-mono font-medium text-slate-900">
                                    <a href="{{ route('admin.orders.show', $ord->id) }}" class="hover:text-blue-950 transition-colors">
                                        {{ $ord->order_number }}
                                    </a>
                                    <span class="text-[10px] text-slate-400 block font-normal">{{ $ord->created_at->format('M d, Y h:i A') }}</span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="flex items-center space-x-2">
                                        <div class="w-6 h-6 rounded-md bg-slate-100 text-slate-700 font-semibold text-[11px] flex items-center justify-center shrink-0 border border-slate-200">
                                            {{ strtoupper(substr($ord->user ? $ord->user->name : 'N', 0, 1)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <span class="font-medium text-slate-900 block truncate">{{ $ord->user ? $ord->user->name : 'N/A' }}</span>
                                            <span class="text-[10px] text-slate-400 block truncate">{{ $ord->user ? $ord->user->email : '' }}</span>
                                        </div>
                                    </div>
                                </td>
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
                                        <span class="text-slate-500 font-medium">Academy Subscription</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-medium uppercase font-mono bg-slate-100 text-slate-700 border border-slate-200">
                                        {{ $ord->payment_gateway }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap font-semibold text-slate-900 font-mono">
                                    ₹ {{ number_format($ord->amount, 2) }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md text-[10px] font-medium uppercase {{ $ord->status === 'paid' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200/60' : 'bg-amber-50 text-amber-700 border border-amber-200/60' }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $ord->status === 'paid' ? 'bg-emerald-500' : 'bg-amber-500' }}"></span>
                                        <span>{{ $ord->status }}</span>
                                    </span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-right">
                                    <a href="{{ route('admin.orders.show', $ord->id) }}" class="px-2.5 py-1 rounded-md text-xs font-medium text-slate-700 hover:text-blue-950 bg-white hover:bg-slate-50 border border-slate-200 transition-colors inline-block shadow-2xs">
                                        Receipt
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-xs text-slate-400 italic">No recent transactions recorded.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Aspirants (4 cols) -->
        <div class="lg:col-span-4 bg-white rounded-xl p-4 sm:p-5 border border-slate-200/80 shadow-2xs space-y-3">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div>
                    <h3 class="text-sm font-semibold text-slate-900">Recent Aspirants</h3>
                    <p class="text-[11px] text-slate-500">Newly registered candidates</p>
                </div>
                <a href="{{ route('admin.students.index') }}" class="text-xs font-semibold text-blue-950 hover:underline transition-colors">
                    View All 
                </a>
            </div>

            <div class="space-y-2">
                @forelse($recentStudents as $st)
                    <div class="p-2.5 rounded-lg bg-slate-50/70 border border-slate-200/70 flex items-center justify-between hover:bg-slate-100/70 transition-colors">
                        <div class="flex items-center space-x-2.5 truncate mr-2">
                            <div class="w-8 h-8 rounded-md bg-blue-950 text-white font-semibold text-xs flex items-center justify-center shrink-0">
                                {{ strtoupper(substr($st->name, 0, 1)) }}
                            </div>
                            <div class="truncate">
                                <h4 class="text-xs font-semibold text-slate-900 truncate">{{ $st->name }}</h4>
                                <div class="flex items-center space-x-1.5 text-[10px] text-slate-500">
                                    <span class="truncate">{{ $st->target_exam ?? 'NDA / CDS' }}</span>
                                    <span>&bull;</span>
                                    <span>{{ $st->created_at->diffForHumans(null, true) }}</span>
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('admin.students.show', $st->id) }}" class="text-xs font-medium text-slate-700 hover:text-blue-950 px-2 py-1 rounded-md bg-white border border-slate-200 transition-colors shrink-0 shadow-2xs">
                           View
                        </a>
                    </div>
                @empty
                    <p class="py-6 text-center text-xs text-slate-400 italic">No registered candidates yet.</p>
                @endforelse
            </div>
        </div>

    </div>

    <!-- CBT Attempts Stream Table -->
    <div class="bg-white rounded-xl border border-slate-200/80 shadow-2xs overflow-hidden">
        <div class="p-4 sm:p-5 border-b border-slate-200/80 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-semibold text-slate-900">Recent Test Submissions</h3>
            </div>
            <a href="{{ route('admin.tests.index') }}" class="text-xs font-semibold text-blue-950 hover:underline transition-colors flex items-center space-x-1">
                <span>View All</span>
                
            </a>
        </div>

        <div class="overflow-x-auto w-full">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50/75 text-slate-500 uppercase text-[10px] font-semibold tracking-wider border-b border-slate-200/80">
                    <tr>
                        <th class="px-4 py-2.5 whitespace-nowrap">Candidate</th>
                        <th class="px-4 py-2.5 whitespace-nowrap">Mock Examination</th>
                        <th class="px-4 py-2.5 whitespace-nowrap">Score</th>
                        <th class="px-4 py-2.5 whitespace-nowrap">Accuracy</th>
                        <th class="px-4 py-2.5 whitespace-nowrap">Qualification</th>
                        <th class="px-4 py-2.5 whitespace-nowrap text-right">Submitted At</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($recentAttempts as $att)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="px-4 py-3 whitespace-nowrap font-semibold text-slate-900">
                                <div class="flex items-center space-x-2">
                                    <div class="w-6 h-6 rounded-md bg-slate-100 text-slate-700 font-semibold text-[10px] flex items-center justify-center shrink-0 border border-slate-200">
                                        {{ strtoupper(substr($att->user ? $att->user->name : 'C', 0, 1)) }}
                                    </div>
                                    <span>{{ $att->user ? $att->user->name : 'N/A' }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-slate-800 font-medium max-w-xs truncate" title="{{ $att->mockTest ? $att->mockTest->title : 'N/A' }}">
                                {{ $att->mockTest ? $att->mockTest->title : 'N/A' }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap font-mono font-semibold text-slate-900">
                                {{ $att->score }} <span class="text-slate-400 font-normal text-[11px]">/ {{ $att->mockTest ? $att->mockTest->total_marks : 0 }}</span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="font-semibold text-emerald-700 font-mono">{{ $att->accuracy_percentage }}%</span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md text-[10px] font-medium uppercase {{ $att->is_passed ? 'bg-emerald-50 text-emerald-700 border border-emerald-200/60' : 'bg-red-50 text-red-700 border border-red-200/60' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $att->is_passed ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                                    <span>{{ $att->is_passed ? 'Qualified' : 'Not Qualified' }}</span>
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-right text-slate-400 font-medium text-[11px]">
                                {{ $att->submitted_at ? $att->submitted_at->format('d M, Y h:i A') : 'N/A' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-xs text-slate-400 italic">No completed CBT test attempts recorded yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
