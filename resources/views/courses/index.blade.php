@extends('layouts.app')

@section('title', 'Explore Defence Preparation Courses — Nirmaan')

@section('content')
<div class="py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header & Breadcrumbs -->
        <div class="mb-8">
            <nav class="flex text-xs text-gray-500 mb-2">
                <a href="{{ route('home') }}" class="hover:text-blue-600">Home</a>
                <span class="mx-2">/</span>
                <span class="text-gray-800 font-semibold">Courses</span>
            </nav>
            <h1 class="text-3xl font-extrabold text-gray-900">Explore Defence Entrance Courses</h1>
            <p class="text-sm text-gray-600 mt-1 max-w-2xl">
                Structured batches curated by veterans and top faculty for Army, Navy, Air Force, CAPF, and Allied security examinations.
            </p>
        </div>

        <!-- Filter Bar -->
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-xs mb-8">
            <form action="{{ route('courses.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
                
                <!-- Category Select -->
                <div class="md:col-span-4">
                    <label class="block text-xs font-semibold text-gray-500 mb-1">Filter by Branch / Exam</label>
                    <select name="category" onchange="this.form.submit()" class="w-full text-xs sm:text-sm bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2.5 outline-none focus:border-blue-500">
                        <option value="">All Examinations</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->slug }}" {{ request('category') === $cat->slug ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Pricing Type -->
                <div class="md:col-span-3">
                    <label class="block text-xs font-semibold text-gray-500 mb-1">Access Type</label>
                    <select name="type" onchange="this.form.submit()" class="w-full text-xs sm:text-sm bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2.5 outline-none focus:border-blue-500">
                        <option value="">All Courses</option>
                        <option value="paid" {{ request('type') === 'paid' ? 'selected' : '' }}>Paid Premium Batches</option>
                        <option value="free" {{ request('type') === 'free' ? 'selected' : '' }}>Free Preview Batches</option>
                    </select>
                </div>

                <!-- Sort -->
                <div class="md:col-span-3">
                    <label class="block text-xs font-semibold text-gray-500 mb-1">Sort By</label>
                    <select name="sort" onchange="this.form.submit()" class="w-full text-xs sm:text-sm bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2.5 outline-none focus:border-blue-500">
                        <option value="">Recommended</option>
                        <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Newest Additions</option>
                        <option value="price_low" {{ request('sort') === 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="price_high" {{ request('sort') === 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                    </select>
                </div>

                <!-- Search & Reset -->
                <div class="md:col-span-2 flex items-end space-x-2">
                    <button type="submit" class="w-full py-2.5 text-xs font-bold text-white bg-blue-700 hover:bg-blue-800 rounded-xl transition">
                        Apply
                    </button>
                    @if(request()->hasAny(['category', 'type', 'sort', 'search']))
                        <a href="{{ route('courses.index') }}" class="px-3 py-2.5 text-xs text-gray-500 hover:text-gray-800 bg-gray-100 rounded-xl transition" title="Clear Filters">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Course Grid -->
        @if($courses->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
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
                    @endphp
                    <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-xs hover:shadow-lg transition-all flex flex-col justify-between group">
                        <div>
                            <div class="flex items-start space-x-4 mb-4">
                                <div class="w-14 h-14 rounded-full flex-shrink-0 flex items-center justify-center p-0.5 group-hover:scale-105 transition-transform duration-300">
                                    <img src="{{ asset('images/' . $badgeFile) }}" alt="{{ $course->title }}" class="w-full h-full object-contain">
                                </div>
                                <div class="flex-grow">
                                    <h3 class="text-lg font-bold text-gray-900 group-hover:text-blue-700 transition">
                                        <a href="{{ route('courses.show', $course->slug) }}">
                                            {{ $course->title }}
                                        </a>
                                    </h3>
                                    <p class="text-xs text-gray-500 font-medium mt-0.5">
                                        {{ $course->subtitle ?? 'Complete Preparation' }}
                                    </p>
                                </div>
                            </div>

                            <p class="text-xs text-gray-600 line-clamp-2 mb-4 leading-relaxed">
                                {{ $course->description }}
                            </p>

                            <div class="flex flex-wrap gap-1.5 mb-6">
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                    Live Classes
                                </span>
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-blue-50 text-blue-700 border border-blue-100">
                                    Mock Tests
                                </span>
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-purple-50 text-purple-700 border border-purple-100">
                                    Study Material
                                </span>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                            <div>
                                <span class="text-lg font-extrabold text-gray-900">{{ $course->formatted_price }}</span>
                                @if($course->original_price && $course->original_price > $course->price)
                                    <span class="text-xs text-gray-400 line-through ml-1.5">₹ {{ number_format($course->original_price, 0) }}</span>
                                @endif
                            </div>

                            <div class="flex items-center space-x-2">
                                <a href="{{ route('courses.show', $course->slug) }}" class="px-3.5 py-2 rounded-full text-xs font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 transition">
                                    Details
                                </a>
                                <a href="{{ route('student.checkout', $course->id) }}" class="inline-flex items-center px-5 py-2 rounded-full text-xs font-bold text-white bg-[#1D4ED8] hover:bg-[#1E40AF] transition shadow-xs">
                                    Enroll
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $courses->links() }}
            </div>
        @else
            <div class="bg-white rounded-2xl p-12 text-center border border-gray-100 max-w-lg mx-auto">
                <div class="w-12 h-12 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="text-base font-bold text-gray-900">No courses found</h3>
                <p class="text-xs text-gray-500 mt-1 mb-4">Try clearing your filters or search keywords to view available batches.</p>
                <a href="{{ route('courses.index') }}" class="px-5 py-2 bg-blue-700 text-white rounded-full text-xs font-semibold">
                    View All Courses
                </a>
            </div>
        @endif

    </div>
</div>
@endsection
