@extends('layouts.app')

@section('title', 'Nirmaan — Complete Education & Defence Exam Preparation Platform')

@section('content')
<div class="overflow-hidden">

    <!-- 1. HERO SECTION (Exact match to screenshot) -->
    <section class="relative pt-8 pb-16 lg:pt-14 lg:pb-24">
        <!-- Abstract Organic Background Blobs -->
        <div class="absolute top-0 right-0 -z-10 w-full max-w-4xl h-full pointer-events-none opacity-60">
            <svg viewBox="0 0 800 600" class="w-full h-full fill-none">
                <path d="M420,60 C580,20 720,120 760,260 C800,420 680,540 520,560 C360,580 280,480 240,360 C200,240 260,100 420,60 Z" fill="#FDEEE6" />
                <path d="M540,120 C640,90 710,180 730,280 C750,380 660,460 560,480 C460,500 400,430 380,350 C360,270 440,150 540,120 Z" fill="#E6F4EA" />
            </svg>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 items-center">
                <!-- Left Column: Copy & Actions -->
                <div class="lg:col-span-7 space-y-6">
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-[#111827] tracking-tight leading-[1.12]">
                        Build a Strong<br>
                        <span class="text-[#1E3A8A]">Future with Nirmaan</span>
                    </h1>

                    <p class="text-base sm:text-lg text-gray-600 max-w-xl leading-relaxed">
                        Nirmaan provides curated study material, mock tests and online classes for aspirants preparing for security forces entrance examinations.
                    </p>

                    <!-- Feature Badges Pill -->
                    <div class="inline-flex flex-wrap items-center gap-2 sm:gap-3 py-2 px-4 rounded-full bg-blue-50/70 border border-blue-100 text-xs sm:text-sm font-medium text-blue-900">
                        <span>Question Banks</span>
                        <span class="text-blue-300">|</span>
                        <span>Mock Tests</span>
                        <span class="text-blue-300">|</span>
                        <span>Live &amp; Recorded Classes</span>
                    </div>

                    <!-- CTAs -->
                    <div class="flex flex-wrap items-center gap-4 pt-2">
                        <a href="{{ route('courses.index') }}" class="inline-flex items-center justify-center px-8 py-3.5 text-sm sm:text-base font-semibold rounded-full text-white bg-[#1D4ED8] hover:bg-[#1E40AF] shadow-md shadow-blue-500/20 hover:shadow-lg transition-all transform hover:-translate-y-0.5">
                            Explore Courses
                        </a>

                        <a href="{{ route('courses.show', 'indian-army-agniveer-comprehensive-course') }}" class="inline-flex items-center space-x-2.5 px-5 py-3.5 text-sm font-semibold text-gray-700 hover:text-blue-700 group transition">
                            <span>View Demo Classes</span>
                            <span class="w-8 h-8 rounded-full bg-white border border-gray-200 flex items-center justify-center text-blue-700 shadow-xs group-hover:bg-blue-50 group-hover:border-blue-300 transition">
                                <svg class="w-4 h-4 transform group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                </svg>
                            </span>
                        </a>
                    </div>
                </div>

                <!-- Right Column: Parade Image with Soft Organic Masking -->
                <div class="lg:col-span-5 relative flex justify-center">
                    <div class="relative w-full max-w-md lg:max-w-none">
                        <!-- Decorative background shapes -->
                        <div class="absolute -top-6 -right-6 w-72 h-72 bg-amber-100 rounded-full mix-blend-multiply filter blur-2xl opacity-40"></div>
                        <div class="absolute -bottom-8 -left-8 w-72 h-72 bg-emerald-100 rounded-full mix-blend-multiply filter blur-2xl opacity-40"></div>
                        
                        <!-- Main Cadets Parade Image with shaped container -->
                        <div class="relative rounded-2xl overflow-hidden border border-gray-200 aspect-[4/3] bg-gray-100 shadow-2xs">
                            <img src="{{ asset('images/cadets_parade.jpg') }}" alt="Indian Armed Forces Cadets Passing Out Parade" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-black/20"></div>
                            <div class="absolute bottom-4 left-4 right-4 text-white text-xs font-medium bg-black/60 p-2.5 rounded-xl border border-white/20">
                                🇮🇳 Preparing officers &amp; soldiers with national pride and excellence
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 2. FIVE FEATURE CARDS ROW (Exact match to screenshot) -->
    <section class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3.5 sm:gap-4">
                <!-- Card 1: Question Banks -->
                <div class="bg-white/80 backdrop-blur-xs p-4 sm:p-5 rounded-2xl border border-gray-100/80 shadow-xs hover:shadow-md transition group">
                    <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-700 mb-3 group-hover:scale-105 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                    </div>
                    <h3 class="text-sm font-bold text-gray-900">Question Banks</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Topic-wise practice sets</p>
                </div>

                <!-- Card 2: Mock Tests -->
                <div class="bg-white/80 backdrop-blur-xs p-4 sm:p-5 rounded-2xl border border-gray-100/80 shadow-xs hover:shadow-md transition group">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 mb-3 group-hover:scale-105 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-sm font-bold text-gray-900">Mock Tests</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Timed &amp; exam pattern based</p>
                </div>

                <!-- Card 3: Video Classes -->
                <div class="bg-white/80 backdrop-blur-xs p-4 sm:p-5 rounded-2xl border border-gray-100/80 shadow-xs hover:shadow-md transition group">
                    <div class="w-10 h-10 rounded-xl bg-rose-50 flex items-center justify-center text-rose-600 mb-3 group-hover:scale-105 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-sm font-bold text-gray-900">Video Classes</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Live &amp; recorded sessions</p>
                </div>

                <!-- Card 4: Study Material -->
                <div class="bg-white/80 backdrop-blur-xs p-4 sm:p-5 rounded-2xl border border-gray-100/80 shadow-xs hover:shadow-md transition group">
                    <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600 mb-3 group-hover:scale-105 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <h3 class="text-sm font-bold text-gray-900">Study Material</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Notes, PDFs &amp; reference sheets</p>
                </div>

                <!-- Card 5: Student Support -->
                <div class="bg-white/80 backdrop-blur-xs p-4 sm:p-5 rounded-2xl border border-gray-100/80 shadow-xs hover:shadow-md transition col-span-2 sm:col-span-1 group">
                    <div class="w-10 h-10 rounded-xl bg-teal-50 flex items-center justify-center text-teal-600 mb-3 group-hover:scale-105 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-sm font-bold text-gray-900">Student Support</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Get help when you need it</p>
                </div>
            </div>
        </div>
    </section>

    <!-- 3. POPULAR COURSES SECTION (Exact match to 6 cards grid in screenshot) -->
    <section class="py-16" id="courses">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-10">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-blue-600 block mb-1">POPULAR COURSES</span>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900">Explore Our Courses</h2>
                    <p class="text-sm text-gray-500 mt-1 max-w-2xl">
                        Choose from a wide range of courses designed to help you crack your dream examination with confidence.
                    </p>
                </div>
                <div class="mt-4 sm:mt-0">
                    <a href="{{ route('courses.index') }}" class="inline-flex items-center text-sm font-semibold text-blue-700 hover:text-blue-900 group">
                        <span>View All Courses</span>
                        <svg class="w-4 h-4 ml-1 transform group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- 6 Course Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($featuredCourses as $course)
                    @php
                        // Map badge icon according to code or slug
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
                    <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-xs hover:shadow-lg transition-all duration-300 flex flex-col justify-between group">
                        <div>
                            <!-- Header with Circular Badge -->
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

                            <!-- Tags matching design: [Live Classes] [Mock Tests] [Study Material] -->
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

                        <!-- Price and Enroll Now Button -->
                        <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                            <div>
                                <span class="text-lg font-extrabold text-gray-900">₹ {{ number_format($course->price, 0) }}</span>
                                @if($course->original_price && $course->original_price > $course->price)
                                    <span class="text-xs text-gray-400 line-through ml-1.5">₹ {{ number_format($course->original_price, 0) }}</span>
                                @endif
                            </div>

                            <a href="{{ route('student.checkout', $course->id) }}" class="inline-flex items-center px-6 py-2 rounded-full text-xs font-bold text-white bg-[#1D4ED8] hover:bg-[#1E40AF] shadow-xs hover:shadow-sm transition-all transform hover:-translate-y-0.5">
                                Enroll Now
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- 4. OUR IMPACT SECTION (Exact match to screenshot) -->
    <section class="py-14 bg-white/70 border-y border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10">
                <span class="text-xs font-bold uppercase tracking-wider text-blue-600 block mb-1">OUR IMPACT</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900">Trusted by Thousands of Aspirants</h2>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 relative items-center">
                <!-- Stat 1 -->
                <div class="flex flex-col items-center text-center">
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-600 mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <span class="text-3xl font-extrabold text-gray-900">50,000+</span>
                    <span class="text-xs font-medium text-gray-500 mt-1">Happy Students</span>
                </div>

                <!-- Stat 2 -->
                <div class="flex flex-col items-center text-center">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-600 mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <span class="text-3xl font-extrabold text-gray-900">2,500+</span>
                    <span class="text-xs font-medium text-gray-500 mt-1">Mock Tests</span>
                </div>

                <!-- Stat 3 -->
                <div class="flex flex-col items-center text-center">
                    <div class="w-12 h-12 rounded-2xl bg-purple-50 flex items-center justify-center text-purple-600 mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <span class="text-3xl font-extrabold text-gray-900">1,000+</span>
                    <span class="text-xs font-medium text-gray-500 mt-1">Video Lectures</span>
                </div>

                <!-- Stat 4 -->
                <div class="flex flex-col items-center text-center relative">
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 flex items-center justify-center text-amber-500 mb-3">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"></path>
                        </svg>
                    </div>
                    <span class="text-3xl font-extrabold text-gray-900">4.8/5</span>
                    <span class="text-xs font-medium text-gray-500 mt-1">Average Rating</span>

                    <!-- Hand-Script Badge (from screenshot) -->
                    <div class="hidden lg:block absolute -right-20 top-2 transform rotate-6">
                        <span class="font-serif italic text-sm text-[#1E3A8A] font-bold block">
                            Your Success
                        </span>
                        <span class="font-serif italic text-base text-[#D97706] font-extrabold block -mt-1 underline decoration-wavy decoration-[#D97706]">
                            Our Mission
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 5. WHY CHOOSE NIRMAAN SECTION (Student with backpack + Checklist) -->
    <section class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                <!-- Left: Student Image -->
                <div class="lg:col-span-5 flex justify-center">
                    <div class="relative w-full max-w-sm">
                        <div class="relative rounded-2xl overflow-hidden border border-gray-200 shadow-2xs bg-white">
                            <img src="{{ asset('images/student_aspirant.jpg') }}" alt="Nirmaan Defence Aspirant Student" class="w-full h-auto object-cover">
                        </div>
                    </div>
                </div>

                <!-- Right: Content & Checklist -->
                <div class="lg:col-span-7 space-y-6">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-blue-600 block mb-1">WHY CHOOSE NIRMAAN</span>
                        <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 leading-tight">
                            Learn. Practice. Succeed.
                        </h2>
                    </div>

                    <p class="text-base text-gray-600 leading-relaxed">
                        We focus on what matters — your preparation. With expert courses, realistic tests and continuous support, we help you stay ahead and achieve your goals.
                    </p>

                    <!-- Checklist -->
                    <div class="space-y-4 pt-2">
                        <div class="flex items-start space-x-3.5">
                            <div class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span class="text-sm font-semibold text-gray-800">Exam-oriented content prepared by experts</span>
                        </div>

                        <div class="flex items-start space-x-3.5">
                            <div class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span class="text-sm font-semibold text-gray-800">Realistic mock tests with detailed analysis</span>
                        </div>

                        <div class="flex items-start space-x-3.5">
                            <div class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span class="text-sm font-semibold text-gray-800">Flexible learning — anytime, anywhere</span>
                        </div>

                        <div class="flex items-start space-x-3.5">
                            <div class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span class="text-sm font-semibold text-gray-800">Dedicated student support team</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 6. WHAT OUR STUDENTS SAY (Exact match to screenshot) -->
    <section class="py-16 bg-white/50 border-t border-gray-100" x-data="{ currentSlide: 0 }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-end justify-between mb-10">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900">What Our Students Say</h2>
                    <p class="text-sm text-gray-500 mt-1">Hear from our learners who achieved their dreams with Nirmaan.</p>
                </div>
                <div class="flex items-center space-x-2">
                    <button @click="currentSlide = Math.max(0, currentSlide - 1)" class="w-9 h-9 rounded-full border border-gray-200 flex items-center justify-center text-gray-600 hover:bg-gray-100 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </button>
                    <button @click="currentSlide = Math.min(1, currentSlide + 1)" class="w-9 h-9 rounded-full border border-gray-200 flex items-center justify-center text-gray-600 hover:bg-gray-100 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </button>
                </div>
            </div>

            <!-- Testimonials Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Testimonial 1 -->
                <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-xs flex flex-col justify-between">
                    <p class="text-sm text-gray-700 italic leading-relaxed">
                        “Nirmaan’s mock tests are exactly like the real exam. The analysis helped me improve my weak areas.”
                    </p>
                    <div class="mt-6 pt-4 border-t border-gray-100 flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-800 font-bold flex items-center justify-center text-sm">
                                RS
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-gray-900">Rohit Sharma</h4>
                                <span class="text-[11px] text-gray-500 block">Indian Army (Selected)</span>
                            </div>
                        </div>
                        <div class="flex text-amber-400 text-xs">
                            ★★★★★
                        </div>
                    </div>
                </div>

                <!-- Testimonial 2 -->
                <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-xs flex flex-col justify-between">
                    <p class="text-sm text-gray-700 italic leading-relaxed">
                        “The video classes are very clear and well structured. I could study at my own pace.”
                    </p>
                    <div class="mt-6 pt-4 border-t border-gray-100 flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-full bg-purple-100 text-purple-800 font-bold flex items-center justify-center text-sm">
                                NS
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-gray-900">Neha Singh</h4>
                                <span class="text-[11px] text-gray-500 block">Indian Air Force (Selected)</span>
                            </div>
                        </div>
                        <div class="flex text-amber-400 text-xs">
                            ★★★★★
                        </div>
                    </div>
                </div>

                <!-- Testimonial 3 -->
                <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-xs flex flex-col justify-between">
                    <p class="text-sm text-gray-700 italic leading-relaxed">
                        “Excellent study material and exact exam pattern based tests. Highly recommended!”
                    </p>
                    <div class="mt-6 pt-4 border-t border-gray-100 flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-800 font-bold flex items-center justify-center text-sm">
                                AK
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-gray-900">Aman Kumar</h4>
                                <span class="text-[11px] text-gray-500 block">BSF (Selected)</span>
                            </div>
                        </div>
                        <div class="flex text-amber-400 text-xs">
                            ★★★★★
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 7. FREQUENTLY ASKED QUESTIONS SECTION (Exact match to screenshot) -->
    <section class="py-16" x-data="{ activeFaq: null }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-end justify-between mb-8">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-blue-600 block mb-1">FAQ</span>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900">Frequently Asked Questions</h2>
                </div>
              
            </div>

            <!-- Accordion items -->
            <div class="space-y-3">
                @foreach($faqs as $index => $faq)
                    <div class="border border-gray-200 rounded-xl bg-white overflow-hidden transition shadow-2xs">
                        <button @click="activeFaq = (activeFaq === {{ $index }} ? null : {{ $index }})" class="w-full px-5 py-8 text-left flex items-center justify-between text-sm font-semibold text-gray-900 hover:text-blue-700 focus:outline-none">
                            <span>{{ $faq->question }}</span>
                            <span class="ml-4 flex-shrink-0 text-gray-400">
                                <svg x-show="activeFaq !== {{ $index }}" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                <svg x-show="activeFaq === {{ $index }}" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                            </span>
                        </button>
                        <div x-show="activeFaq === {{ $index }}" x-collapse class="px-5 pb-4 text-xs sm:text-sm text-gray-600 leading-relaxed border-t border-gray-50 pt-2" style="display:none;">
                            {{ $faq->answer }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- 8. BOTTOM CALL TO ACTION BANNER (Exact match to screenshot) -->
    <section class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="relative overflow-hidden rounded-2xl bg-slate-900 border border-slate-800 p-8 sm:p-12 flex flex-col md:flex-row items-center justify-between gap-6 shadow-2xs">
                <div class="relative z-10 space-y-2 text-center md:text-left">
                    <h3 class="text-2xl sm:text-3xl font-extrabold text-white">
                        Ready to Build Your Future?
                    </h3>
                    <p class="text-sm text-slate-300 max-w-xl">
                        Join Nirmaan today and take the first step towards your dream career in the security forces.
                    </p>
                </div>

                <div class="relative z-10 flex-shrink-0">
                    <a href="{{ route('courses.index') }}" class="inline-flex items-center px-8 py-3.5 rounded-xl text-sm font-bold text-white bg-blue-700 hover:bg-blue-800 transition">
                        <span>Explore Courses</span>
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

</div>
@endsection
