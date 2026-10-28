@extends('layouts.app')

@section('title', 'Explore Defence Preparation Courses & Batches — Nirmaan')

@section('content')
<div class="py-6 sm:py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6 sm:space-y-8">
        
        <!-- Header & Breadcrumbs -->
        <div class="space-y-3">
            <nav class="flex items-center space-x-2 text-xs text-slate-500 font-normal">
                <a href="{{ route('home') }}" class="hover:text-blue-950 transition">Home</a>
                <span class="text-slate-300 font-light">&bull;</span>
                <span class="text-slate-800 font-medium">Courses</span>
            </nav>

            <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-4">
                <div class="space-y-1.5 max-w-3xl">
                    <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight leading-tight">
                        Defence Entrance Courses &amp; Batches
                    </h1>
                </div>

                <!-- Metrics Pill Group -->
                <div class="flex items-center space-x-2 sm:space-x-3 text-xs text-slate-600 flex-shrink-0">
                    <div class="bg-white border border-slate-200 rounded-lg px-3 py-1.5 shadow-2xs">
                        <span class="font-semibold text-slate-900">{{ $totalPublishedCount }}</span>
                        <span class="font-normal text-slate-500 ml-1">Batches</span>
                    </div>
                    <div class="bg-white border border-slate-200 rounded-lg px-3 py-1.5 shadow-2xs hidden sm:block">
                        <span class="font-semibold text-emerald-700">100%</span>
                        <span class="font-normal text-slate-500 ml-1">Syllabus Aligned</span>
                    </div>
                    <div class="bg-white border border-slate-200 rounded-lg px-3 py-1.5 shadow-2xs hidden md:block">
                        <span class="font-semibold text-blue-950">Bilingual</span>
                        <span class="font-normal text-slate-500 ml-1">English &amp; Hindi</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter & Search Section -->
        <div class="bg-white rounded-xl border border-slate-200 p-4 sm:p-5 shadow-2xs space-y-4">
            
            <!-- Category Pills Bar -->
            <div class="space-y-1.5">
                <div class="flex items-center justify-between text-xs text-slate-500 font-medium pb-1">
                    <span>Target Examination Branch</span>
                    @if(request('category'))
                        <a href="{{ route('courses.index', request()->except('category', 'page')) }}" class="text-blue-950 hover:underline text-[11px] font-normal">
                            Show All Branches
                        </a>
                    @endif
                </div>

                <div class="flex items-center space-x-2 overflow-x-auto pb-1.5 pt-0.5 no-scrollbar">
                    <!-- All Exams Tab -->
                    <a href="{{ route('courses.index', request()->except('category', 'page')) }}"
                       class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold whitespace-nowrap transition-all flex-shrink-0 border {{ !request('category') ? 'bg-blue-950 text-white border-blue-950 shadow-2xs' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-slate-100 hover:text-slate-900' }}">
                        <span>All Examinations</span>
                        <span class="text-[10px] px-1.5 py-0.2 rounded-full {{ !request('category') ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-700' }}">
                            {{ $totalPublishedCount }}
                        </span>
                    </a>

                    <!-- Category Items -->
                    @foreach($categories as $cat)
                        @php
                            $isSelected = (request('category') === $cat->slug);
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
                        <a href="{{ route('courses.index', array_merge(request()->except('page'), ['category' => $cat->slug])) }}"
                           class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold whitespace-nowrap transition-all flex-shrink-0 border {{ $isSelected ? 'bg-blue-950 text-white border-blue-950 shadow-2xs' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-slate-100 hover:text-slate-900' }}">
                            <img src="{{ asset('images/' . $badgeFile) }}" alt="{{ $cat->name }}" class="w-4 h-4 object-contain flex-shrink-0">
                            <span>{{ $cat->name }}</span>
                            @if(isset($cat->courses_count))
                                <span class="text-[10px] px-1.5 py-0.2 rounded-full {{ $isSelected ? 'bg-white/20 text-white' : 'bg-slate-200 text-slate-700' }}">
                                    {{ $cat->courses_count }}
                                </span>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Search, Pricing Type & Sort Toolbar -->
            <form action="{{ route('courses.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center pt-2 border-t border-slate-100">
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
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Search course title, exam, or trade..." 
                           class="w-full pl-9 pr-9 py-2 text-xs bg-white border border-slate-200 rounded-lg outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950 transition text-slate-900 placeholder:text-slate-400 font-medium">
                    @if(request('search'))
                        <a href="{{ route('courses.index', request()->except('search', 'page')) }}" 
                           class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600" 
                           title="Clear search">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </a>
                    @endif
                </div>

                <!-- Access Filter (Free vs Paid) -->
                <div class="sm:col-span-3">
                    <select name="type" onchange="this.form.submit()" class="w-full text-xs bg-white border border-slate-200 rounded-lg px-3 py-2 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950 transition text-slate-700 font-medium">
                        <option value="">All Access Types</option>
                        <option value="paid" {{ request('type') === 'paid' ? 'selected' : '' }}>Paid Premium Batches</option>
                        <option value="free" {{ request('type') === 'free' ? 'selected' : '' }}>Free Preview Batches</option>
                    </select>
                </div>

                <!-- Sort Order -->
                <div class="sm:col-span-3">
                    <select name="sort" onchange="this.form.submit()" class="w-full text-xs bg-white border border-slate-200 rounded-lg px-3 py-2 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950 transition text-slate-700 font-medium">
                        <option value="">Sort: Recommended</option>
                        <option value="lessons" {{ request('sort') === 'lessons' ? 'selected' : '' }}>Sort: Most Video Lessons</option>
                        <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Sort: Newest Additions</option>
                        <option value="price_low" {{ request('sort') === 'price_low' ? 'selected' : '' }}>Sort: Price: Low to High</option>
                        <option value="price_high" {{ request('sort') === 'price_high' ? 'selected' : '' }}>Sort: Price: High to Low</option>
                    </select>
                </div>

                <!-- Action button -->
                <div class="sm:col-span-1">
                    <button type="submit" class="w-full py-2 text-xs font-semibold text-white bg-blue-950 hover:bg-blue-900 rounded-lg transition text-center shadow-2xs">
                        Filter
                    </button>
                </div>
            </form>

            <!-- Active Filters Strip -->
            @if(request()->hasAny(['category', 'type', 'sort', 'search']))
                <div class="flex flex-wrap items-center gap-2 pt-2 border-t border-slate-100 text-xs">
                    <span class="text-slate-400 font-normal text-[11px]">Active Filters:</span>

                    @if(request('category'))
                        <span class="inline-flex items-center space-x-1 px-2.5 py-0.5 rounded-md text-[11px] font-medium bg-blue-50 text-blue-950 border border-blue-200">
                            <span>Branch: {{ $categories->firstWhere('slug', request('category'))->name ?? request('category') }}</span>
                            <a href="{{ route('courses.index', request()->except('category', 'page')) }}" class="hover:text-blue-950 font-bold">&times;</a>
                        </span>
                    @endif

                    @if(request('type'))
                        <span class="inline-flex items-center space-x-1 px-2.5 py-0.5 rounded-md text-[11px] font-medium bg-slate-100 text-slate-800 border border-slate-200">
                            <span>Type: {{ request('type') === 'free' ? 'Free Batches' : 'Paid Batches' }}</span>
                            <a href="{{ route('courses.index', request()->except('type', 'page')) }}" class="hover:text-slate-950 font-bold">&times;</a>
                        </span>
                    @endif

                    @if(request('search'))
                        <span class="inline-flex items-center space-x-1 px-2.5 py-0.5 rounded-md text-[11px] font-medium bg-amber-50 text-amber-900 border border-amber-200">
                            <span>Search: &ldquo;{{ request('search') }}&rdquo;</span>
                            <a href="{{ route('courses.index', request()->except('search', 'page')) }}" class="hover:text-amber-950 font-bold">&times;</a>
                        </span>
                    @endif

                    @if(request('sort'))
                        <span class="inline-flex items-center space-x-1 px-2.5 py-0.5 rounded-md text-[11px] font-medium bg-slate-100 text-slate-800 border border-slate-200">
                            <span>Sorted</span>
                            <a href="{{ route('courses.index', request()->except('sort', 'page')) }}" class="hover:text-slate-950 font-bold">&times;</a>
                        </span>
                    @endif

                    <a href="{{ route('courses.index') }}" class="text-[11px] font-medium text-rose-600 hover:text-rose-700 hover:underline ml-1">
                        Clear all
                    </a>
                </div>
            @endif

        </div>

        <!-- Course Cards Grid Section -->
        <div>
            <div class="flex items-center justify-between pb-3 border-b border-slate-200 mb-6">
                <div>
                    <h2 class="text-base sm:text-lg font-semibold text-slate-900 tracking-tight">
                        Available Course Batches
                    </h2>
                    <p class="text-xs font-normal text-slate-500 mt-0.5">
                        Showing {{ $courses->total() }} total {{ Str::plural('batch', $courses->total()) }} matching your selection
                    </p>
                </div>

                <div class="text-xs text-slate-500 font-normal">
                    Page {{ $courses->currentPage() }} of {{ max(1, $courses->lastPage()) }}
                </div>
            </div>

            @if($courses->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 sm:gap-6">
                    @foreach($courses as $course)
                        @php
                            $code = strtolower($course->examCategory ? $course->examCategory->code : 'army');
                            $badgeFile = match($code) {
                                'army' => 'army-badge.svg',
                                'navy' => 'navy-badge.svg',
                                'airforce' => 'airforce-badge.svg',
                                'bsf' => 'bsf-badge.svg',
                                'cisf' => 'cisf-badge.svg',
                                'ssb' => 'ssb-badge.svg',
                                default => 'army-badge.svg',
                            };
                            $isEnrolled = in_array($course->id, $enrolledCourseIds);
                            $lessonsCount = $course->lessons_count ?? 0;
                            $firstLesson = $course->modules->flatMap->lessons->first();
                            $coverImg = $course->thumbnail 
                                ? asset($course->thumbnail) 
                                : ($firstLesson?->video_id 
                                    ? "https://img.youtube.com/vi/{$firstLesson->video_id}/mqdefault.jpg" 
                                    : null);
                        @endphp

                        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden flex flex-col justify-between hover:border-slate-300 shadow-2xs transition duration-200 group">
                            
                            <!-- Card Visual Banner Cover -->
                            <div class="aspect-video w-full relative bg-slate-950 overflow-hidden border-b border-slate-100 flex-shrink-0">
                                @if($coverImg)
                                    <img src="{{ $coverImg }}" 
                                         alt="{{ $course->title }}" 
                                         loading="lazy" 
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                    <div class="absolute inset-0 bg-slate-900/20"></div>
                                @else
                                    <div class="w-full h-full flex items-center justify-center relative">
                                        <div class="w-14 h-14 rounded-full bg-white/10 p-2 border border-white/15 flex items-center justify-center group-hover:scale-110 transition-transform">
                                            <img src="{{ asset('images/' . $badgeFile) }}" alt="{{ $course->title }}" class="w-full h-full object-contain">
                                        </div>
                                    </div>
                                @endif

                                <!-- Top Badges Overlay -->
                                <div class="absolute top-2.5 left-2.5 right-2.5 flex items-center justify-between gap-2 pointer-events-none">
                                    <!-- Branch Tag -->
                                    <span class="inline-flex items-center space-x-1.5 px-2.5 py-1 rounded-md text-[11px] font-medium bg-white/95 text-slate-900 border border-white/40 shadow-2xs backdrop-blur-xs">
                                        <img src="{{ asset('images/' . $badgeFile) }}" alt="" class="w-3.5 h-3.5 object-contain flex-shrink-0">
                                        <span class="truncate max-w-[130px]">{{ $course->examCategory ? $course->examCategory->name : 'Defence' }}</span>
                                    </span>

                                    <!-- Status Badge -->
                                    <div>
                                        @if($isEnrolled)
                                            <span class="inline-flex items-center space-x-1 px-2.5 py-1 rounded-md text-[10px] font-semibold bg-emerald-600 text-white shadow-2xs">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                                <span>Enrolled</span>
                                            </span>
                                        @elseif($course->is_free)
                                            <span class="px-2.5 py-1 rounded-md text-[10px] font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200 shadow-2xs">
                                                Free Access
                                            </span>
                                        @else
                                            <span class="px-2 py-0.5 rounded-md text-[10px] font-medium bg-slate-900/80 text-slate-200 border border-white/20 backdrop-blur-xs">
                                                2026 Batch
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Bottom Badges Overlay -->
                                <div class="absolute bottom-2.5 left-2.5 right-2.5 flex items-center justify-between gap-2 pointer-events-none">
                                    <span class="inline-flex items-center space-x-1 px-2 py-0.5 rounded-md text-[10px] font-medium bg-slate-900/80 text-amber-300 border border-white/10 backdrop-blur-xs">
                                        <svg class="w-3 h-3 fill-current text-amber-400" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        <span>4.9</span>
                                        <span class="text-white/60 font-light">&bull;</span>
                                        <span class="text-white/80 font-normal">Syllabus</span>
                                    </span>

                                    @if($lessonsCount > 0)
                                        <span class="inline-flex items-center space-x-1 px-2 py-0.5 rounded-md text-[10px] font-semibold bg-blue-950 text-white shadow-2xs">
                                            <svg class="w-2.5 h-2.5 fill-current" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                            <span>{{ $lessonsCount }} Lectures</span>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Card Body -->
                            <div class="p-5 flex-grow space-y-3">
                                
                                <!-- Title & Subtitle -->
                                <div class="space-y-1">
                                    <h3 class="text-sm sm:text-base font-bold text-slate-900 group-hover:text-blue-950 transition leading-snug line-clamp-2">
                                        <a href="{{ route('courses.show', $course->slug) }}">
                                            {{ $course->title }}
                                        </a>
                                    </h3>
                                    @if($course->subtitle)
                                        <p class="text-xs text-slate-500 font-normal line-clamp-1">
                                            {{ $course->subtitle }}
                                        </p>
                                    @endif
                                </div>

                                <!-- Course Description -->
                                <p class="text-xs text-slate-600 font-normal line-clamp-2 leading-relaxed">
                                    {{ $course->description }}
                                </p>

                                <!-- Feature Highlights Chips -->
                                <div class="flex flex-wrap items-center gap-1.5 pt-1">
                                    @if($lessonsCount > 0)
                                        <a href="{{ route('courses.watch', $course->slug) }}" 
                                           class="inline-flex items-center space-x-1 px-2.5 py-1 rounded-md text-[11px] font-semibold bg-blue-50 text-blue-950 border border-blue-200 hover:bg-blue-100 transition" 
                                           title="Watch video lectures">
                                            <svg class="w-3 h-3 fill-current text-blue-950" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                            <span>Watch Episodes</span>
                                        </a>
                                    @endif

                                    <span class="px-2 py-1 rounded-md text-[11px] font-medium bg-slate-50 text-slate-600 border border-slate-200">
                                        CBT Tests
                                    </span>
                                    <span class="px-2 py-1 rounded-md text-[11px] font-medium bg-slate-50 text-slate-600 border border-slate-200">
                                        PDF Notes
                                    </span>
                                </div>

                            </div>

                            <!-- Bottom Action & Pricing Row -->
                            <div class="px-5 py-4 border-t border-slate-100 flex items-center justify-between gap-2.5 bg-slate-50/50">
                                
                                <!-- Pricing -->
                                <div class="min-w-0 flex-shrink-0">
                                    <div class="flex items-baseline space-x-1.5 whitespace-nowrap">
                                        <span class="text-base sm:text-lg font-bold text-slate-900 whitespace-nowrap">
                                            {{ $course->formatted_price }}
                                        </span>
                                        @if($course->original_price && $course->original_price > $course->price)
                                            <span class="text-xs text-slate-400 line-through font-normal whitespace-nowrap">
                                                ₹ {{ number_format($course->original_price, 0) }}
                                            </span>
                                            <span class="text-[10px] font-medium text-emerald-700 bg-emerald-50 px-1 py-0.2 rounded border border-emerald-200 whitespace-nowrap">
                                                {{ $course->discount_percentage }}% OFF
                                            </span>
                                        @endif
                                    </div>
                                    <span class="text-[10px] text-slate-400 font-normal block">
                                        Full Batch Access
                                    </span>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex items-center space-x-1.5 flex-shrink-0">
                                    @if($isEnrolled)
                                        <a href="{{ route('courses.watch', $course->slug) }}" 
                                           class="inline-flex items-center space-x-1 px-3.5 py-1.5 rounded-lg text-xs font-semibold text-white bg-emerald-700 hover:bg-emerald-800 transition shadow-2xs">
                                            <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                            <span>Continue</span>
                                        </a>
                                    @else
                                        <a href="{{ route('courses.show', $course->slug) }}" 
                                           class="px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 transition shadow-2xs">
                                            Details
                                        </a>

                                        <a href="{{ route('student.checkout', $course->id) }}" 
                                           class="inline-flex items-center px-3.5 py-1.5 rounded-lg text-xs font-semibold text-white bg-blue-950 hover:bg-blue-900 transition shadow-2xs">
                                            Enroll
                                        </a>
                                    @endif
                                </div>

                            </div>

                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8 pt-4 border-t border-slate-200">
                    {{ $courses->links() }}
                </div>
            @else
                <!-- Empty State -->
                <div class="bg-white rounded-xl p-10 sm:p-14 text-center border border-slate-200 max-w-lg mx-auto shadow-2xs space-y-3">
                    <div class="w-12 h-12 rounded-lg bg-blue-50 text-blue-950 flex items-center justify-center mx-auto border border-blue-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </div>
                    <div class="space-y-1">
                        <h3 class="text-base font-semibold text-slate-900">No course batches found</h3>
                        <p class="text-xs text-slate-500 font-normal">
                            We couldn't find any batches matching your current filter criteria.
                        </p>
                    </div>
                    <div class="pt-2">
                        <a href="{{ route('courses.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-950 hover:bg-blue-900 text-white rounded-lg text-xs font-semibold transition shadow-2xs">
                            View All Courses
                        </a>
                    </div>
                </div>
            @endif

        </div>

    </div>
</div>
@endsection
