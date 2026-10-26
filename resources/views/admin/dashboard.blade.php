@extends('layouts.admin')

@section('title', 'Officer Command Dashboard — Nirmaan Defence Platform')
@section('header_title', 'HQ Command Center')

@section('content')
<div class="space-y-8">
    
    <!-- Hero Banner with Live Operational Status -->
    <div class="bg-slate-900 text-white rounded-2xl p-6 sm:p-8 relative overflow-hidden border border-slate-800 shadow-2xs">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-2">
                <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-md bg-blue-500/20 border border-blue-400/30 text-blue-300 text-xs font-bold tracking-wide">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>NATIONAL DEFENCE ACADEMY COMMAND HQ</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-bold text-white tracking-tight">
                    Welcome, Officer {{ Auth::user()->name }}
                </h1>
                <p class="text-xs sm:text-sm text-slate-300 max-w-2xl leading-relaxed">
                    Real-time administrative command over defence aspirants, course curricula, question bank repository, financial audits, and Computer-Based Test (CBT) engine.
                </p>
            </div>

            <!-- Quick Action Buttons -->
            <div class="flex flex-wrap items-center gap-2.5 shrink-0 self-start sm:self-auto">
                <a href="{{ route('admin.courses.create') }}" 
                   class="px-4 py-2.5 rounded-xl text-xs font-bold bg-blue-700 hover:bg-blue-800 text-white transition flex items-center space-x-2 shadow-xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    <span>+ New Course</span>
                </a>
                <a href="{{ route('admin.tests.create') }}" 
                   class="px-4 py-2.5 rounded-xl text-xs font-bold bg-slate-800 hover:bg-slate-700 text-white border border-slate-700 transition flex items-center space-x-2 shadow-xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    <span>+ Launch CBT</span>
                </a>
                <a href="{{ route('admin.questions.create') }}" 
                   class="px-4 py-2.5 rounded-xl text-xs font-bold bg-slate-800 hover:bg-slate-700 text-white border border-slate-700 transition flex items-center space-x-2 shadow-xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>+ New MCQ</span>
                </a>
            </div>
        </div>
    </div>

    <!-- KPI Metrics Grid (6 Columns) -->
    <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-6 gap-4">
        
        <!-- Total Cadets -->
        <div class="bg-white rounded-2xl p-5 border border-gray-200 shadow-2xs hover:border-gray-300 transition">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs text-gray-500 font-semibold">Enrolled Cadets</span>
                <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-700 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
            </div>
            <span class="text-2xl font-bold text-gray-900 block font-mono">{{ number_format($totalStudents) }}</span>
            <span class="text-[10px] text-emerald-700 font-medium flex items-center mt-1">
                <span class="mr-1">●</span> Verified Aspirants
            </span>
        </div>

        <!-- Active Subscriptions -->
        <div class="bg-white rounded-2xl p-5 border border-gray-200 shadow-2xs hover:border-gray-300 transition">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs text-gray-500 font-semibold">Active Seats</span>
                <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-700 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <span class="text-2xl font-bold text-indigo-700 block font-mono">{{ number_format($totalEnrollments) }}</span>
            <span class="text-[10px] text-indigo-700 font-medium flex items-center mt-1">
                <span class="mr-1">●</span> Active Trainees
            </span>
        </div>

        <!-- Courses -->
        <div class="bg-white rounded-2xl p-5 border border-gray-200 shadow-2xs hover:border-gray-300 transition">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs text-gray-500 font-semibold">Course Batches</span>
                <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-700 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
            </div>
            <span class="text-2xl font-bold text-gray-900 block font-mono">{{ $totalCourses }}</span>
            <span class="text-[10px] text-amber-700 font-medium flex items-center mt-1">
                <span class="mr-1">●</span> Published Batches
            </span>
        </div>

        <!-- Question Bank -->
        <div class="bg-white rounded-2xl p-5 border border-gray-200 shadow-2xs hover:border-gray-300 transition">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs text-gray-500 font-semibold">Question Bank</span>
                <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <span class="text-2xl font-bold text-gray-900 block font-mono">{{ number_format($totalQuestions) }}</span>
            <span class="text-[10px] text-emerald-700 font-medium flex items-center mt-1">
                <span class="mr-1">●</span> Verified MCQs
            </span>
        </div>

        <!-- CBT Mock Tests -->
        <div class="bg-white rounded-2xl p-5 border border-gray-200 shadow-2xs hover:border-gray-300 transition">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs text-gray-500 font-semibold">CBT Mock Tests</span>
                <div class="w-8 h-8 rounded-lg bg-sky-50 text-sky-700 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <span class="text-2xl font-bold text-gray-900 block font-mono">{{ $totalMockTests }}</span>
            <span class="text-[10px] text-sky-700 font-medium flex items-center mt-1">
                <span class="mr-1">●</span> Online Exam Engines
            </span>
        </div>

        <!-- Verified Revenue -->
        <div class="bg-white rounded-2xl p-5 border border-gray-200 shadow-2xs hover:border-gray-300 transition">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs text-gray-500 font-semibold">Gross Revenue</span>
                <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <span class="text-xl sm:text-2xl font-bold text-emerald-700 block font-mono">₹ {{ number_format($totalRevenue, 2) }}</span>
            <span class="text-[10px] text-emerald-700 font-medium flex items-center mt-1">
                <span class="mr-1">●</span> Verified Receipts
            </span>
        </div>

    </div>

    <!-- Quick Navigation Hub -->
    <div class="bg-white rounded-2xl p-3 sm:p-4 border border-gray-200 shadow-2xs flex flex-wrap items-center justify-between gap-3 text-xs font-semibold">
        <span class="text-gray-400 text-[11px] uppercase tracking-wider font-bold pl-2">HQ Quick Links:</span>
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('admin.courses.index') }}" class="px-3 py-1.5 rounded-lg bg-gray-50 hover:bg-blue-50 hover:text-blue-700 border border-gray-200 transition">
                Courses Directory
            </a>
            <a href="{{ route('admin.questions.index') }}" class="px-3 py-1.5 rounded-lg bg-gray-50 hover:bg-blue-50 hover:text-blue-700 border border-gray-200 transition">
                Question Bank
            </a>
            <a href="{{ route('admin.tests.index') }}" class="px-3 py-1.5 rounded-lg bg-gray-50 hover:bg-blue-50 hover:text-blue-700 border border-gray-200 transition">
                Mock Tests Hub
            </a>
            <a href="{{ route('admin.students.index') }}" class="px-3 py-1.5 rounded-lg bg-gray-50 hover:bg-blue-50 hover:text-blue-700 border border-gray-200 transition">
                Cadets Roster
            </a>
            <a href="{{ route('admin.orders.index') }}" class="px-3 py-1.5 rounded-lg bg-gray-50 hover:bg-blue-50 hover:text-blue-700 border border-gray-200 transition">
                Orders &amp; Financial Audits
            </a>
            <a href="{{ route('admin.content.index') }}" class="px-3 py-1.5 rounded-lg bg-gray-50 hover:bg-blue-50 hover:text-blue-700 border border-gray-200 transition">
                Inquiries &amp; Settings
            </a>
        </div>
    </div>

    <!-- Middle Row: Orders Audit Table (with whitespace-nowrap) & Recent Cadets -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Recent Orders (8 cols) -->
        <div class="lg:col-span-8 bg-white rounded-2xl border border-gray-200 shadow-2xs overflow-hidden">
            <div class="p-5 sm:p-6 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-gray-900">Recent Gateway Transactions</h3>
                </div>
                <a href="{{ route('admin.orders.index') }}" class="text-xs font-bold text-blue-700 hover:text-blue-800 transition flex items-center space-x-1">
                    <span> View All</span>
                   
                </a>
            </div>

            <!-- Table with Strict whitespace-nowrap -->
            <div class="overflow-x-auto w-full">
                <table class="w-full text-left text-xs text-gray-600">
                    <thead class="bg-gray-50 text-gray-500 uppercase text-[10px] font-bold border-b border-gray-200">
                        <tr>
                            <th class="px-5 py-3.5 whitespace-nowrap">Order ID</th>
                            <th class="px-5 py-3.5 whitespace-nowrap">Cadet Name</th>
                            <th class="px-5 py-3.5 whitespace-nowrap">Purchased Item</th>
                            <th class="px-5 py-3.5 whitespace-nowrap">Gateway Method</th>
                            <th class="px-5 py-3.5 whitespace-nowrap">Amount</th>
                            <th class="px-5 py-3.5 whitespace-nowrap">Status</th>
                            <th class="px-5 py-3.5 whitespace-nowrap text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($recentOrders as $ord)
                            <tr class="hover:bg-gray-50/70 transition">
                                <td class="px-5 py-3.5 whitespace-nowrap font-mono font-bold text-gray-900">
                                    <a href="{{ route('admin.orders.show', $ord->id) }}" class="hover:text-blue-700 transition">
                                        {{ $ord->order_number }}
                                    </a>
                                    <span class="text-[10px] text-gray-400 block font-normal">{{ $ord->created_at->format('M d, Y h:i A') }}</span>
                                </td>
                                <td class="px-5 py-3.5 whitespace-nowrap">
                                    <div class="flex items-center space-x-2.5">
                                        <div class="w-7 h-7 rounded-lg bg-blue-50 text-blue-800 font-bold text-xs flex items-center justify-center shrink-0 border border-blue-100">
                                            {{ strtoupper(substr($ord->user ? $ord->user->name : 'N', 0, 1)) }}
                                        </div>
                                        <div>
                                            <span class="font-bold text-gray-900 block">{{ $ord->user ? $ord->user->name : 'N/A' }}</span>
                                            <span class="text-[10px] text-gray-400 block">{{ $ord->user ? $ord->user->email : '' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-3.5 whitespace-nowrap text-gray-800">
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
                                        <span class="text-gray-500 font-medium">Academy Subscription</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 whitespace-nowrap">
                                    <span class="px-2.5 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider bg-gray-100 text-gray-700 border border-gray-200">
                                        {{ $ord->payment_gateway }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 whitespace-nowrap font-bold text-gray-900 font-mono">
                                    ₹ {{ number_format($ord->amount, 2) }}
                                </td>
                                <td class="px-5 py-3.5 whitespace-nowrap">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $ord->status === 'paid' ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-amber-50 text-amber-800 border border-amber-200' }}">
                                        ● {{ $ord->status }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 whitespace-nowrap text-right">
                                    <a href="{{ route('admin.orders.show', $ord->id) }}" class="px-2.5 py-1 rounded-lg text-xs font-bold text-blue-700 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 transition inline-block">
                                        View Receipt
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-xs text-gray-400 italic">No recent transactions recorded.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Registered Cadets (4 cols) -->
        <div class="lg:col-span-4 bg-white rounded-2xl p-5 sm:p-6 border border-gray-200 shadow-2xs space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                <div>
                    <h3 class="text-sm font-bold text-gray-900">Recent Aspirants</h3>
                    <p class="text-[11px] text-gray-500">Newly registered candidates</p>
                </div>
                <a href="{{ route('admin.students.index') }}" class="text-xs font-bold text-blue-700 hover:text-blue-800 transition">
                    View 
                </a>
            </div>

            <div class="space-y-3">
                @forelse($recentStudents as $st)
                    <div class="p-3 rounded-xl bg-gray-50 border border-gray-200 flex items-center justify-between hover:bg-gray-100 transition">
                        <div class="flex items-center space-x-3 truncate mr-2">
                            <div class="w-9 h-9 rounded-xl bg-blue-100 text-blue-800 font-bold text-xs flex items-center justify-center shrink-0 border border-blue-200">
                                {{ strtoupper(substr($st->name, 0, 1)) }}
                            </div>
                            <div class="truncate">
                                <h4 class="text-xs font-bold text-gray-900 truncate">{{ $st->name }}</h4>
                                <div class="flex items-center space-x-1.5 mt-0.5">
                                    <span class="px-1.5 py-0.2 rounded text-[9px] font-bold bg-blue-50 text-blue-700 border border-blue-100 uppercase">
                                        {{ $st->target_exam ?? 'NDA / CDS' }}
                                    </span>
                                    <span class="text-[10px] text-gray-400">&bull; {{ $st->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('admin.students.show', $st->id) }}" class="text-xs font-bold text-blue-700 hover:text-blue-900 px-2.5 py-1 rounded-lg bg-blue-50 hover:bg-blue-100 transition shrink-0">
                            Profile
                        </a>
                    </div>
                @empty
                    <p class="py-6 text-center text-xs text-gray-400 italic">No registered students yet.</p>
                @endforelse
            </div>
        </div>

    </div>

    <!-- CBT Attempts Stream Table with whitespace-nowrap -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-2xs overflow-hidden">
        <div class="p-5 sm:p-6 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-bold text-gray-900">Recent Test Submissions</h3>
            </div>
            <a href="{{ route('admin.tests.index') }}" class="text-xs font-bold text-blue-700 hover:underline">
                Mock Tests 
            </a>
        </div>

        <div class="overflow-x-auto w-full">
            <table class="w-full text-left text-xs text-gray-600">
                <thead class="bg-gray-50 text-gray-500 uppercase text-[10px] font-bold border-b border-gray-200">
                    <tr>
                        <th class="px-5 py-3.5 whitespace-nowrap">Candidate</th>
                        <th class="px-5 py-3.5 whitespace-nowrap">Mock Examination</th>
                        <th class="px-5 py-3.5 whitespace-nowrap">Score Obtained</th>
                        <th class="px-5 py-3.5 whitespace-nowrap">Accuracy Rate</th>
                        <th class="px-5 py-3.5 whitespace-nowrap">Result Qualification</th>
                        <th class="px-5 py-3.5 whitespace-nowrap text-right">Submitted At</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($recentAttempts as $att)
                        <tr class="hover:bg-gray-50/70 transition">
                            <td class="px-5 py-3.5 whitespace-nowrap font-bold text-gray-900">
                                <div class="flex items-center space-x-2">
                                    <div class="w-6 h-6 rounded-full bg-blue-50 text-blue-700 font-bold text-[10px] flex items-center justify-center shrink-0 border border-blue-100">
                                        {{ strtoupper(substr($att->user ? $att->user->name : 'C', 0, 1)) }}
                                    </div>
                                    <span>{{ $att->user ? $att->user->name : 'N/A' }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-3.5 whitespace-nowrap text-gray-800 font-medium">
                                {{ $att->mockTest ? $att->mockTest->title : 'N/A' }}
                            </td>
                            <td class="px-5 py-3.5 whitespace-nowrap font-mono font-bold text-gray-900">
                                {{ $att->score }} <span class="text-gray-400 font-normal text-[11px]">/ {{ $att->mockTest ? $att->mockTest->total_marks : 0 }}</span>
                            </td>
                            <td class="px-5 py-3.5 whitespace-nowrap">
                                <span class="font-bold text-emerald-700 font-mono">{{ $att->accuracy_percentage }}%</span>
                            </td>
                            <td class="px-5 py-3.5 whitespace-nowrap">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $att->is_passed ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-red-50 text-red-800 border border-red-200' }}">
                                    {{ $att->is_passed ? '● QUALIFIED' : '● NOT QUALIFIED' }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 whitespace-nowrap text-right text-gray-400 font-medium text-[11px]">
                                {{ $att->submitted_at ? $att->submitted_at->format('d M, Y h:i A') : 'N/A' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-xs text-gray-400 italic">No completed CBT test attempts recorded yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

 

</div>
@endsection
