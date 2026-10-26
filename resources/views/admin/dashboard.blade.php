@extends('layouts.admin')

@section('title', 'Officer Command Dashboard — Nirmaan')
@section('header_title', 'Operational Command & National Metrics')

@section('content')
<div class="space-y-8">
    
    <!-- Hero Banner with Live Operational Status -->
    <div class="bg-gradient-to-r from-[#0B132B] via-slate-900 to-blue-950 text-white rounded-3xl p-6 sm:p-8 relative overflow-hidden shadow-xl border border-slate-800">
        <!-- Decorative Glow -->
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-blue-600/20 rounded-full blur-3xl pointer-events-none"></div>
        
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-2">
                <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-blue-500/20 border border-blue-400/30 text-blue-300 text-xs font-bold tracking-wide">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                    <span>NATIONAL DEFENCE PLATFORM CONTROL</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">
                    Welcome, Officer {{ Auth::user()->name }}
                </h1>
                <p class="text-xs sm:text-sm text-slate-300 max-w-2xl leading-relaxed">
                    Real-time oversight over defence aspirants, course curricula, question bank repository, and Computer-Based Test (CBT) engine.
                </p>
            </div>

            <div class="flex items-center space-x-3 flex-shrink-0">
                <a href="{{ route('admin.courses.create') }}" 
                   class="px-5 py-2.5 rounded-xl text-xs font-bold bg-blue-600 hover:bg-blue-500 text-white shadow-lg shadow-blue-600/30 transition transform hover:-translate-y-0.5 flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    <span>+ New Course</span>
                </a>
                <a href="{{ route('admin.tests.create') }}" 
                   class="px-5 py-2.5 rounded-xl text-xs font-bold bg-white/10 hover:bg-white/20 text-white border border-white/20 backdrop-blur-xs transition flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    <span>+ Launch CBT</span>
                </a>
            </div>
        </div>
    </div>

    <!-- KPI Metrics Grid -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
        
        <!-- Total Cadets -->
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-xs hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs text-gray-500 font-semibold">Enrolled Cadets</span>
                <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
            </div>
            <span class="text-2xl font-black text-gray-900 block">{{ number_format($totalStudents) }}</span>
            <span class="text-[10px] text-emerald-600 font-bold flex items-center mt-1">
                <span class="mr-1">●</span> Verified Aspirants
            </span>
        </div>

        <!-- Active Subscriptions -->
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-xs hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs text-gray-500 font-semibold">Active Seats</span>
                <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <span class="text-2xl font-black text-indigo-700 block">{{ number_format($totalEnrollments) }}</span>
            <span class="text-[10px] text-indigo-600 font-bold flex items-center mt-1">
                <span class="mr-1">●</span> In-Training Seats
            </span>
        </div>

        <!-- Courses -->
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-xs hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs text-gray-500 font-semibold">Course Batches</span>
                <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                </div>
            </div>
            <span class="text-2xl font-black text-gray-900 block">{{ $totalCourses }}</span>
            <span class="text-[10px] text-amber-700 font-bold flex items-center mt-1">
                <span class="mr-1">●</span> Published Batches
            </span>
        </div>

        <!-- Question Bank -->
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-xs hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs text-gray-500 font-semibold">Question Bank</span>
                <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <span class="text-2xl font-black text-gray-900 block">{{ number_format($totalQuestions) }}</span>
            <span class="text-[10px] text-emerald-700 font-bold flex items-center mt-1">
                <span class="mr-1">●</span> Verified Items
            </span>
        </div>

        <!-- CBT Mock Tests -->
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-xs hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs text-gray-500 font-semibold">CBT Mock Tests</span>
                <div class="w-8 h-8 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <span class="text-2xl font-black text-gray-900 block">{{ $totalMockTests }}</span>
            <span class="text-[10px] text-sky-700 font-bold flex items-center mt-1">
                <span class="mr-1">●</span> Live Timed CBTs
            </span>
        </div>

        <!-- Verified Revenue -->
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-xs hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs text-gray-500 font-semibold">Gross Revenue</span>
                <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <span class="text-xl sm:text-2xl font-black text-emerald-600 block">₹ {{ number_format($totalRevenue, 0) }}</span>
            <span class="text-[10px] text-emerald-700 font-bold flex items-center mt-1">
                <span class="mr-1">●</span> Verified INR
            </span>
        </div>

    </div>

    <!-- Quick Navigation Sub-Bar -->
    <div class="bg-white rounded-2xl p-3 border border-gray-100 shadow-2xs flex flex-wrap items-center justify-between gap-3 text-xs font-semibold">
        <span class="text-gray-400 text-[11px] uppercase tracking-wider font-extrabold pl-2">Quick Navigation Hub:</span>
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('admin.courses.index') }}" class="px-3 py-1.5 rounded-lg bg-gray-50 hover:bg-blue-50 hover:text-blue-700 transition flex items-center space-x-1.5">
                <span>Courses &amp; Syllabus</span>
            </a>
            <a href="{{ route('admin.questions.index') }}" class="px-3 py-1.5 rounded-lg bg-gray-50 hover:bg-blue-50 hover:text-blue-700 transition flex items-center space-x-1.5">
                <span>Question Bank</span>
            </a>
            <a href="{{ route('admin.tests.index') }}" class="px-3 py-1.5 rounded-lg bg-gray-50 hover:bg-blue-50 hover:text-blue-700 transition flex items-center space-x-1.5">
                <span>Mock Tests</span>
            </a>
            <a href="{{ route('admin.students.index') }}" class="px-3 py-1.5 rounded-lg bg-gray-50 hover:bg-blue-50 hover:text-blue-700 transition flex items-center space-x-1.5">
                <span>Registered Cadets</span>
            </a>
            <a href="{{ route('admin.orders.index') }}" class="px-3 py-1.5 rounded-lg bg-gray-50 hover:bg-blue-50 hover:text-blue-700 transition flex items-center space-x-1.5">
                <span>Orders &amp; Receipts</span>
            </a>
            <a href="{{ route('admin.content.index') }}" class="px-3 py-1.5 rounded-lg bg-gray-50 hover:bg-blue-50 hover:text-blue-700 transition flex items-center space-x-1.5">
                <span>Content &amp; Inquiries</span>
            </a>
        </div>
    </div>

    <!-- Middle Row: Orders Audit & Recent Cadets -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Recent Orders (8 cols) -->
        <div class="lg:col-span-8 bg-white rounded-3xl p-6 border border-gray-100 shadow-xs space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                <div>
                    <h3 class="text-sm font-bold text-gray-900">Recent Gateway Transactions</h3>
                    <p class="text-[11px] text-gray-500">Live order captures from student enrollments</p>
                </div>
                <a href="{{ route('admin.orders.index') }}" class="text-xs font-bold text-blue-700 hover:text-blue-800 transition flex items-center space-x-1">
                    <span>Audit All Orders</span>
                    <span>&rarr;</span>
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-gray-600">
                    <thead class="bg-gray-50/70 text-gray-400 uppercase text-[10px] font-bold border-b border-gray-100">
                        <tr>
                            <th class="px-4 py-3">Order #</th>
                            <th class="px-4 py-3">Cadet Name</th>
                            <th class="px-4 py-3">Course / Batch</th>
                            <th class="px-4 py-3">Amount</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3 text-right">Invoice</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($recentOrders as $ord)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="px-4 py-3.5 font-mono font-bold text-gray-900">{{ $ord->order_number }}</td>
                                <td class="px-4 py-3.5 font-semibold text-gray-900">
                                    <div class="flex items-center space-x-2">
                                        <div class="w-6 h-6 rounded-full bg-blue-100 text-blue-800 font-bold text-[10px] flex items-center justify-center flex-shrink-0">
                                            {{ strtoupper(substr($ord->user ? $ord->user->name : 'N', 0, 1)) }}
                                        </div>
                                        <span class="truncate max-w-[130px]">{{ $ord->user ? $ord->user->name : 'N/A' }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3.5 text-gray-700">
                                    <span class="truncate max-w-[180px] block" title="{{ $ord->course ? $ord->course->title : 'N/A' }}">
                                        {{ $ord->course ? $ord->course->title : 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3.5 font-black text-gray-900">₹ {{ number_format($ord->amount, 0) }}</td>
                                <td class="px-4 py-3.5">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase {{ $ord->status === 'paid' ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-amber-50 text-amber-800 border border-amber-200' }}">
                                        {{ $ord->status }}
                                    </span>
                                </td>
                                <td class="px-4 py-3.5 text-right">
                                    <a href="{{ route('admin.orders.show', $ord->id) }}" class="text-blue-700 font-bold hover:underline">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-xs text-gray-400 italic">No recent orders found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Cadets (4 cols) -->
        <div class="lg:col-span-4 bg-white rounded-3xl p-6 border border-gray-100 shadow-xs space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                <div>
                    <h3 class="text-sm font-bold text-gray-900">Recent Aspirants</h3>
                    <p class="text-[11px] text-gray-500">Newly registered students</p>
                </div>
                <a href="{{ route('admin.students.index') }}" class="text-xs font-bold text-blue-700 hover:text-blue-800 transition">
                    Directory &rarr;
                </a>
            </div>

            <div class="space-y-3">
                @forelse($recentStudents as $st)
                    <div class="p-3 rounded-2xl bg-gray-50/70 border border-gray-100 flex items-center justify-between hover:bg-gray-100/70 transition">
                        <div class="flex items-center space-x-3 truncate mr-2">
                            <div class="w-9 h-9 rounded-xl bg-blue-100 text-blue-800 font-bold text-xs flex items-center justify-center flex-shrink-0 shadow-2xs">
                                {{ strtoupper(substr($st->name, 0, 1)) }}
                            </div>
                            <div class="truncate">
                                <h4 class="text-xs font-bold text-gray-900 truncate">{{ $st->name }}</h4>
                                <div class="flex items-center space-x-1.5 mt-0.5">
                                    <span class="px-1.5 py-0.2 rounded text-[9px] font-bold bg-blue-50 text-blue-700 border border-blue-100 uppercase">
                                        {{ $st->target_exam ?? 'Cadet' }}
                                    </span>
                                    <span class="text-[10px] text-gray-400">&bull; {{ $st->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('admin.students.show', $st->id) }}" class="text-xs font-bold text-blue-700 hover:text-blue-900 px-2 py-1 rounded-lg bg-blue-50 hover:bg-blue-100 transition flex-shrink-0">
                            Profile
                        </a>
                    </div>
                @empty
                    <p class="py-6 text-center text-xs text-gray-400 italic">No registered students yet.</p>
                @endforelse
            </div>
        </div>

    </div>

    <!-- CBT Attempts Stream -->
    <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-xs space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-gray-100">
            <div>
                <h3 class="text-sm font-bold text-gray-900">Recent CBT Mock Test Submissions</h3>
                <p class="text-[11px] text-gray-500">Live test scorecards and performance metrics scored automatically by the engine</p>
            </div>
            <a href="{{ route('admin.tests.index') }}" class="text-xs font-bold text-blue-700 hover:underline">
                Mock Tests Hub &rarr;
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($recentAttempts as $att)
                <div class="p-4 rounded-2xl bg-gray-50/70 border border-gray-100 flex flex-col justify-between space-y-3">
                    <div class="flex items-start justify-between">
                        <div>
                            <span class="text-[10px] text-gray-400 uppercase font-bold tracking-wider block">Candidate</span>
                            <span class="text-xs font-bold text-gray-900 block truncate max-w-[180px]">{{ $att->user ? $att->user->name : 'N/A' }}</span>
                            <span class="text-[11px] text-blue-700 font-semibold truncate block mt-0.5">{{ $att->mockTest ? $att->mockTest->title : 'N/A' }}</span>
                        </div>
                        <div class="text-right">
                            <span class="text-base font-black text-gray-900 block">{{ $att->score }}</span>
                            <span class="text-[9px] text-gray-400 uppercase font-bold block">Marks Scored</span>
                        </div>
                    </div>

                    <div class="pt-2 border-t border-gray-100 flex items-center justify-between text-[11px] text-gray-500">
                        <span>Accuracy: <strong class="text-emerald-600">{{ $att->accuracy_percentage }}%</strong></span>
                        <span>{{ $att->submitted_at ? $att->submitted_at->diffForHumans() : 'Just now' }}</span>
                    </div>
                </div>
            @empty
                <div class="col-span-3 py-6 text-center text-xs text-gray-400 italic">
                    No completed CBT test attempts recorded yet.
                </div>
            @endforelse
        </div>
    </div>

</div>
@endsection
