@extends('layouts.student')

@section('title', 'CBT Mock Tests Series — Nirmaan')

@section('content')
<div class="py-6 sm:py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6 sm:space-y-8">
        
        <!-- Header & Breadcrumbs (Sleek Typographic Hierarchy) -->
        <div class="space-y-3">
            <nav class="flex items-center space-x-2 text-xs text-gray-500 font-normal">
                <a href="{{ route('home') }}" class="hover:text-blue-700 transition">Home</a>
                <span class="text-gray-300 font-light">&bull;</span>
                <a href="{{ route('student.dashboard') }}" class="hover:text-blue-700 transition">Student Portal</a>
                <span class="text-gray-300 font-light">&bull;</span>
                <span class="text-gray-800 font-medium">CBT Mock Tests</span>
            </nav>

            <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-4">
                <div class="space-y-1.5 max-w-3xl">
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 tracking-tight leading-tight">
                        Official Pattern CBT Mock Tests
                    </h1>
                    <p class="text-xs sm:text-sm text-gray-600 font-normal leading-relaxed">
                        Computer-based practice examinations built to the exact exam blueprints, time limits, section cut-offs, and negative marking rules of Indian Armed Forces.
                    </p>
                </div>

                <!-- Metrics Pill Group -->
                <div class="flex flex-wrap items-center gap-2 text-xs text-gray-600 flex-shrink-0">
                    <div class="bg-white border border-gray-200/80 rounded-xl px-3 py-1.5 shadow-2xs">
                        <span class="font-semibold text-gray-900">{{ $totalPublishedTestsCount }}</span>
                        <span class="font-normal text-gray-500 ml-1">Mock Tests</span>
                    </div>
                    <div class="bg-white border border-gray-200/80 rounded-xl px-3 py-1.5 shadow-2xs">
                        <span class="font-semibold text-blue-700">{{ $userCompletedCount }}</span>
                        <span class="font-normal text-gray-500 ml-1">Attempted</span>
                    </div>
                    <div class="bg-white border border-gray-200/80 rounded-xl px-3 py-1.5 shadow-2xs hidden sm:block">
                        <span class="font-semibold text-emerald-700">{{ $userAvgAccuracy }}%</span>
                        <span class="font-normal text-gray-500 ml-1">Avg Accuracy</span>
                    </div>
                    <div class="bg-white border border-gray-200/80 rounded-xl px-3 py-1.5 shadow-2xs hidden md:block">
                        <span class="font-semibold text-amber-700">{{ $userBestScore }}</span>
                        <span class="font-normal text-gray-500 ml-1">Best Score</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter & Search Section -->
        <div class="bg-white rounded-2xl border border-gray-200/80 p-4 sm:p-5 shadow-2xs space-y-4">
            
            <!-- Category Pills Bar (Horizontal Scrollable on Mobile) -->
            <div class="space-y-1.5">
                <div class="flex items-center justify-between text-xs text-gray-500 font-medium pb-1">
                    <span>Target Examination Branch</span>
                    @if(request('category'))
                        <a href="{{ route('student.tests.index', request()->except('category', 'page')) }}" class="text-blue-700 hover:underline text-[11px] font-normal">
                            Show All Branches
                        </a>
                    @endif
                </div>

                <div class="flex items-center space-x-2 overflow-x-auto pb-1.5 pt-0.5 no-scrollbar">
                    <!-- All Exams Tab -->
                    <a href="{{ route('student.tests.index', request()->except('category', 'page')) }}"
                       class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-lg text-xs font-medium whitespace-nowrap transition-colors flex-shrink-0 border {{ !request('category') ? 'bg-blue-950 text-white border-blue-950' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-slate-100 hover:text-slate-900' }}">
                        <span>All Examinations</span>
                        <span class="text-[10px] px-1.5 py-0.2 rounded-full {{ !request('category') ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-700' }}">
                            {{ $totalPublishedTestsCount }}
                        </span>
                    </a>

                    <!-- Category Items -->
                    @foreach($categories as $cat)
                        @php
                            $isSelected = (request('category') == $cat->id || request('category') === $cat->slug);
                            $badgeFile = match(strtolower($cat->code)) {
                                'army' => 'army-badge.svg',
                                'navy' => 'navy-badge.svg',
                                'airforce' => 'airforce-badge.svg',
                                'bsf' => 'bsf-badge.svg',
                                'cisf' => 'cisf-badge.svg',
                                'ssb' => 'ssb-badge.svg',
                                default => 'army-badge.svg',
                            };
                        @endphp
                        <a href="{{ route('student.tests.index', array_merge(request()->except('page'), ['category' => $cat->slug])) }}"
                           class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-lg text-xs font-medium whitespace-nowrap transition-colors flex-shrink-0 border {{ $isSelected ? 'bg-blue-950 text-white border-blue-950 shadow-2xs' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-slate-100 hover:text-slate-900' }}">
                            <img src="{{ asset('images/' . $badgeFile) }}" alt="{{ $cat->name }}" class="w-4 h-4 object-contain flex-shrink-0">
                            <span>{{ $cat->name }}</span>
                            @if(isset($cat->mock_tests_count))
                                <span class="text-[10px] px-1.5 py-0.2 rounded-full {{ $isSelected ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-700' }}">
                                    {{ $cat->mock_tests_count }}
                                </span>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Search, Pricing Type & Sort Toolbar -->
            <form action="{{ route('student.tests.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center pt-2 border-t border-slate-100">
                <!-- Keep category if set -->
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif

                <!-- Search Input with Clear Button -->
                <div class="sm:col-span-5 relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </span>
                    <input type="text" 
                           name="q" 
                           value="{{ request('q') }}" 
                           placeholder="Search test name, subject, or trade..." 
                           class="w-full pl-9 pr-9 py-2 text-xs bg-slate-50 border border-slate-200 rounded-lg outline-none focus:bg-white focus:border-blue-950 focus:ring-1 focus:ring-blue-950 transition-colors text-slate-900 placeholder-slate-400 font-normal">
                    @if(request('q'))
                        <a href="{{ route('student.tests.index', request()->except('q', 'page')) }}" 
                           class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600" 
                           title="Clear search">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </a>
                    @endif
                </div>

                <!-- Access Filter (Free vs Paid) -->
                <div class="sm:col-span-3">
                    <select name="access" onchange="this.form.submit()" class="w-full text-xs bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 outline-none focus:bg-white focus:border-blue-950 transition-colors text-slate-700 font-medium">
                        <option value="">All Access Types</option>
                        <option value="free" {{ request('access') === 'free' ? 'selected' : '' }}>Free Practice Tests</option>
                        <option value="paid" {{ request('access') === 'paid' ? 'selected' : '' }}>Premium Mock Tests</option>
                    </select>
                </div>

                <!-- Sort Order -->
                <div class="sm:col-span-3">
                    <select name="sort" onchange="this.form.submit()" class="w-full text-xs bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 outline-none focus:bg-white focus:border-blue-950 transition-colors text-slate-700 font-medium">
                        <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Sort: Newest Tests</option>
                        <option value="duration_asc" {{ request('sort') === 'duration_asc' ? 'selected' : '' }}>Sort: Duration (Short-Long)</option>
                        <option value="marks_desc" {{ request('sort') === 'marks_desc' ? 'selected' : '' }}>Sort: Max Marks (High-Low)</option>
                    </select>
                </div>

                <!-- Action button -->
                <div class="sm:col-span-1">
                    <button type="submit" class="w-full py-2 text-xs font-medium text-white bg-blue-950 hover:bg-blue-900 rounded-lg transition-colors text-center shadow-2xs">
                        Filter
                    </button>
                </div>
            </form>

            <!-- Active Filters Strip -->
            @if(request()->hasAny(['category', 'access', 'sort', 'q']))
                <div class="flex flex-wrap items-center gap-2 pt-2 border-t border-gray-100 text-xs">
                    <span class="text-gray-400 font-normal text-[11px]">Active Filters:</span>

                    @if(request('category'))
                        <span class="inline-flex items-center space-x-1 px-2.5 py-0.5 rounded-lg text-[11px] font-medium bg-blue-50 text-blue-800 border border-blue-200/70">
                            <span>Branch: {{ $categories->firstWhere('slug', request('category'))->name ?? $categories->firstWhere('id', request('category'))->name ?? request('category') }}</span>
                            <a href="{{ route('student.tests.index', request()->except('category', 'page')) }}" class="hover:text-blue-950 font-bold">&times;</a>
                        </span>
                    @endif

                    @if(request('access'))
                        <span class="inline-flex items-center space-x-1 px-2.5 py-0.5 rounded-lg text-[11px] font-medium bg-gray-100 text-gray-800 border border-gray-200">
                            <span>Access: {{ request('access') === 'free' ? 'Free Tests' : 'Premium Tests' }}</span>
                            <a href="{{ route('student.tests.index', request()->except('access', 'page')) }}" class="hover:text-gray-950 font-bold">&times;</a>
                        </span>
                    @endif

                    @if(request('q'))
                        <span class="inline-flex items-center space-x-1 px-2.5 py-0.5 rounded-lg text-[11px] font-medium bg-amber-50 text-amber-900 border border-amber-200">
                            <span>Search: &ldquo;{{ request('q') }}&rdquo;</span>
                            <a href="{{ route('student.tests.index', request()->except('q', 'page')) }}" class="hover:text-amber-950 font-bold">&times;</a>
                        </span>
                    @endif

                    <a href="{{ route('student.tests.index') }}" class="text-[11px] font-medium text-red-600 hover:text-red-700 hover:underline ml-1">
                        Clear all
                    </a>
                </div>
            @endif

        </div>

        <!-- Available Tests Section -->
        <div>
            <div class="flex items-center justify-between pb-3 border-b border-gray-200/80 mb-6">
                <div>
                    <h2 class="text-base sm:text-lg font-semibold text-gray-900 tracking-tight">
                        Standard CBT Mock Series
                    </h2>
                    <p class="text-xs font-normal text-gray-500 mt-0.5">
                        Showing {{ $tests->total() }} total {{ Str::plural('test', $tests->total()) }} available for attempt
                    </p>
                </div>

                <div class="text-xs text-gray-500 font-normal">
                    Page {{ $tests->currentPage() }} of {{ max(1, $tests->lastPage()) }}
                </div>
            </div>

            @if($tests->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 sm:gap-6">
                    @foreach($tests as $test)
                        @php
                            $code = strtolower($test->examCategory ? $test->examCategory->code : 'army');
                            $badgeFile = match($code) {
                                'army' => 'army-badge.svg',
                                'navy' => 'navy-badge.svg',
                                'airforce' => 'airforce-badge.svg',
                                'bsf' => 'bsf-badge.svg',
                                'cisf' => 'cisf-badge.svg',
                                'ssb' => 'ssb-badge.svg',
                                default => 'army-badge.svg',
                            };
                            $inProgress = $test->in_progress_attempt ?? null;
                            $completed = $test->completed_attempt ?? null;
                            $hasAccess = $test->has_access ?? $test->is_free;
                            $price = (float) ($test->price > 0 ? $test->price : 499.00);
                        @endphp

                        <div class="bg-white rounded-2xl border border-gray-200/90 p-5 flex flex-col justify-between hover:border-gray-300 hover:shadow-md transition-all group">
                            
                            <!-- Card Top -->
                            <div class="space-y-3.5">
                                
                                <!-- Card Header: Badge + Category + Status Badge -->
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex items-center space-x-3 min-w-0">
                                        <div class="w-11 h-11 rounded-xl bg-gray-50 border border-gray-200/70 p-1 flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition-transform">
                                            <img src="{{ asset('images/' . $badgeFile) }}" alt="{{ $test->title }}" class="w-full h-full object-contain">
                                        </div>
                                        <div class="min-w-0">
                                            <span class="text-[11px] font-medium text-blue-700 bg-blue-50 px-2 py-0.5 rounded-md border border-blue-200/60 inline-block truncate max-w-[170px]">
                                                {{ $test->examCategory ? $test->examCategory->name : 'Defence Exam' }}
                                            </span>
                                            <span class="text-[10px] text-gray-400 font-normal block mt-0.5">
                                                CBT Pattern Series
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Status Pill -->
                                    <div>
                                        @if($inProgress)
                                            <span class="inline-flex items-center space-x-1 px-2.5 py-1 rounded-md text-[10px] font-medium bg-amber-50 text-amber-800 border border-amber-200/80">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-600 animate-pulse"></span>
                                                <span>Resumable</span>
                                            </span>
                                        @elseif($completed)
                                            <span class="inline-flex items-center space-x-1 px-2.5 py-1 rounded-md text-[10px] font-medium bg-emerald-50 text-emerald-800 border border-emerald-200/80">
                                                <svg class="w-3 h-3 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                                <span>Score: {{ $completed->score }}</span>
                                            </span>
                                        @elseif($test->is_free)
                                            <span class="px-2.5 py-1 rounded-md text-[10px] font-medium bg-emerald-50 text-emerald-800 border border-emerald-200/80">
                                                Free Access
                                            </span>
                                        @elseif($hasAccess)
                                            <span class="px-2.5 py-1 rounded-md text-[10px] font-medium bg-emerald-50 text-emerald-800 border border-emerald-200/80">
                                                Unlocked ✓
                                            </span>
                                        @else
                                            <span class="px-2.5 py-1 rounded-md text-[10px] font-medium bg-gray-100 text-gray-700 border border-gray-200">
                                                ₹ {{ number_format($price, 0) }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Test Title & Description -->
                                <div class="space-y-1">
                                    <h3 class="text-sm sm:text-base font-bold text-gray-900 group-hover:text-blue-700 transition leading-snug line-clamp-2">
                                        <a href="{{ route('student.tests.instructions', $test->id) }}">
                                            {{ $test->title }}
                                        </a>
                                    </h3>
                                    <p class="text-xs text-gray-500 font-normal line-clamp-2 leading-relaxed">
                                        {{ $test->description }}
                                    </p>
                                </div>

                                <!-- Parameters Box (Clean Weight Hierarchy) -->
                                <div class="grid grid-cols-3 gap-2 py-2.5 px-3 bg-gray-50/80 rounded-xl border border-gray-200/70 text-center">
                                    <div>
                                        <span class="text-[10px] text-gray-400 block font-normal">Questions</span>
                                        <span class="text-xs font-semibold text-gray-900 mt-0.5 block">{{ $test->questions_count }}</span>
                                    </div>
                                    <div class="border-x border-gray-200/70">
                                        <span class="text-[10px] text-gray-400 block font-normal">Duration</span>
                                        <span class="text-xs font-semibold text-gray-900 mt-0.5 block">{{ $test->duration_minutes }} Mins</span>
                                    </div>
                                    <div>
                                        <span class="text-[10px] text-gray-400 block font-normal">Max Marks</span>
                                        <span class="text-xs font-semibold text-gray-900 mt-0.5 block">{{ number_format($test->total_marks, 0) }}</span>
                                    </div>
                                </div>

                            </div>

                            <!-- Footer & Action CTA -->
                            <div class="pt-3.5 mt-3.5 border-t border-gray-100 flex items-center justify-between gap-3">
                                <div class="flex items-center space-x-1.5 text-[11px] text-rose-600 font-normal">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                    <span>-{{ $test->negative_marking_ratio }} Negative Marking</span>
                                </div>

                                <div class="flex-shrink-0">
                                    @if($inProgress)
                                        <a href="{{ route('student.tests.attempt', $inProgress->id) }}" 
                                           class="inline-flex items-center space-x-1.5 px-3.5 py-1.5 rounded-lg text-xs font-medium text-white bg-amber-600 hover:bg-amber-700 transition-colors shadow-2xs">
                                            <span>Resume Attempt &rarr;</span>
                                        </a>
                                    @elseif($hasAccess)
                                        <a href="{{ route('student.tests.instructions', $test->id) }}" 
                                           class="inline-flex items-center space-x-1.5 px-3.5 py-1.5 rounded-lg text-xs font-medium text-white bg-blue-950 hover:bg-blue-900 transition-colors shadow-2xs">
                                            <span>{{ $completed ? 'Retake Exam' : 'Start Exam' }} &rarr;</span>
                                        </a>
                                    @else
                                        <a href="{{ route('student.checkout.test', $test->id) }}" 
                                           class="inline-flex items-center space-x-1.5 px-3.5 py-1.5 rounded-lg text-xs font-medium text-white bg-blue-950 hover:bg-blue-900 transition-colors shadow-2xs">
                                            <span>Unlock ₹{{ number_format($price, 0) }} &rarr;</span>
                                        </a>
                                    @endif
                                </div>
                            </div>

                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if(method_exists($tests, 'hasPages') && $tests->hasPages())
                    <div class="mt-8 pt-4 border-t border-gray-200/80">
                        {{ $tests->links() }}
                    </div>
                @endif
            @else
                <!-- Empty State (Clean & Functional) -->
                <div class="bg-white rounded-2xl p-10 sm:p-14 text-center border border-gray-200/80 max-w-lg mx-auto shadow-2xs space-y-3">
                    <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center mx-auto border border-blue-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <div class="space-y-1">
                        <h3 class="text-base font-semibold text-gray-900">No mock tests matched your filter</h3>
                        <p class="text-xs text-gray-500 font-normal">
                            Try adjusting your search keywords or clearing branch category filters.
                        </p>
                    </div>
                    <div class="pt-2">
                        <a href="{{ route('student.tests.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-700 hover:bg-blue-800 text-white rounded-xl text-xs font-semibold transition shadow-2xs">
                            View All Tests
                        </a>
                    </div>
                </div>
            @endif

        </div>

        <!-- Completed Attempts History Table (Clean & Refined) -->
        @if($attempts->count() > 0)
            <div class="pt-6 space-y-4">
                <div class="flex items-center justify-between pb-2 border-b border-gray-200/80">
                    <div>
                        <h2 class="text-base sm:text-lg font-semibold text-gray-900 tracking-tight">
                            My Recent Examination Submissions
                        </h2>
                        <p class="text-xs text-gray-500 font-normal mt-0.5">
                            Performance records, scoring breakdown, and answer key analytics
                        </p>
                    </div>
                    <span class="text-xs text-gray-500 font-normal">
                        {{ $attempts->count() }} Submissions
                    </span>
                </div>

                <div class="bg-white rounded-2xl border border-gray-200/80 overflow-hidden shadow-2xs">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs text-gray-600">
                            <thead class="bg-gray-50/80 text-gray-500 uppercase text-[10px] font-semibold border-b border-gray-200 tracking-wider">
                                <tr>
                                    <th class="px-5 py-3.5">Test Title</th>
                                    <th class="px-5 py-3.5">Final Score</th>
                                    <th class="px-5 py-3.5">Accuracy</th>
                                    <th class="px-5 py-3.5">Time Spent</th>
                                    <th class="px-5 py-3.5">Submission Date</th>
                                    <th class="px-5 py-3.5 text-right">Detailed Analysis</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($attempts as $att)
                                    <tr class="hover:bg-gray-50/60 transition">
                                        <td class="px-5 py-4">
                                            <div class="font-medium text-gray-900 text-xs sm:text-sm">
                                                {{ $att->mockTest->title }}
                                            </div>
                                            <span class="text-[11px] text-gray-400 font-normal">
                                                {{ $att->mockTest->examCategory ? $att->mockTest->examCategory->name : 'Defence Exam' }}
                                            </span>
                                        </td>
                                        <td class="px-5 py-4 font-mono font-semibold text-emerald-700">
                                            {{ $att->score }} / {{ number_format($att->mockTest->total_marks, 0) }}
                                        </td>
                                        <td class="px-5 py-4">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-medium {{ $att->accuracy_percentage >= 70 ? 'bg-emerald-50 text-emerald-800 border border-emerald-200/70' : 'bg-gray-100 text-gray-700 border border-gray-200' }}">
                                                {{ $att->accuracy_percentage }}%
                                            </span>
                                        </td>
                                        <td class="px-5 py-4 text-gray-500 font-normal">
                                            {{ gmdate('i:s', $att->time_taken_seconds) }} mins
                                        </td>
                                        <td class="px-5 py-4 text-gray-500 font-normal">
                                            {{ $att->submitted_at ? $att->submitted_at->format('d M, Y h:i A') : '-' }}
                                        </td>
                                        <td class="px-5 py-4 text-right">
                                            <a href="{{ route('student.tests.result', $att->id) }}" 
                                               class="inline-flex items-center space-x-1 px-3 py-1.5 rounded-lg text-xs font-medium text-blue-700 bg-blue-50 border border-blue-200/60 hover:bg-blue-100 transition">
                                                <span>Scorecard &amp; Solutions</span>
                                                <span>&rarr;</span>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

    </div>
</div>
@endsection
