@extends('layouts.app')

@section('title', $course->title . ' — Batch Details | Nirmaan')

@section('content')
<div class="py-10" x-data="{ previewVideoId: null, showModal: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Breadcrumb -->
        <nav class="flex text-xs text-gray-500 mb-6">
            <a href="{{ route('home') }}" class="hover:text-blue-600">Home</a>
            <span class="mx-2">/</span>
            <a href="{{ route('courses.index') }}" class="hover:text-blue-600">Courses</a>
            <span class="mx-2">/</span>
            <span class="text-gray-800 font-semibold truncate">{{ $course->title }}</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Left Main Column: Course Details & Curriculum -->
            <div class="lg:col-span-8 space-y-8">
                
                <!-- Main Header Card -->
                <div class="bg-white rounded-3xl p-6 sm:p-8 border border-gray-100 shadow-xs">
                    <div class="flex items-center space-x-3 mb-4">
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-800 border border-blue-100">
                            {{ $course->examCategory ? $course->examCategory->name : 'Defence Forces' }}
                        </span>
                        <span class="text-xs text-gray-400">•</span>
                        <span class="text-xs font-medium text-emerald-700 bg-emerald-50 px-2.5 py-0.5 rounded-full">
                            {{ $course->duration_text ?? '6 Months Access' }}
                        </span>
                    </div>

                    <h1 class="text-2xl sm:text-4xl font-extrabold text-gray-900 leading-tight">
                        {{ $course->title }}
                    </h1>
                    <p class="text-sm sm:text-base text-gray-600 mt-2 font-medium">
                        {{ $course->subtitle }}
                    </p>

                    <div class="mt-6 pt-6 border-t border-gray-100">
                        <h2 class="text-sm font-bold uppercase tracking-wider text-gray-400 mb-2">About this Course</h2>
                        <p class="text-sm sm:text-base text-gray-700 leading-relaxed whitespace-pre-line">
                            {{ $course->description }}
                        </p>
                    </div>

                    <!-- Key Perks Badges -->
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 pt-6 mt-6 border-t border-gray-100">
                        <div class="flex items-center space-x-2.5 text-xs text-gray-700 font-medium">
                            <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center flex-shrink-0">✓</span>
                            <span>Live &amp; Recorded Lectures</span>
                        </div>
                        <div class="flex items-center space-x-2.5 text-xs text-gray-700 font-medium">
                            <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center flex-shrink-0">✓</span>
                            <span>Full-Length Mock Tests</span>
                        </div>
                        <div class="flex items-center space-x-2.5 text-xs text-gray-700 font-medium">
                            <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center flex-shrink-0">✓</span>
                            <span>Bilingual PDF Revision Notes</span>
                        </div>
                        <div class="flex items-center space-x-2.5 text-xs text-gray-700 font-medium">
                            <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center flex-shrink-0">✓</span>
                            <span>Doubt Resolution Helpline</span>
                        </div>
                        <div class="flex items-center space-x-2.5 text-xs text-gray-700 font-medium">
                            <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center flex-shrink-0">✓</span>
                            <span>Physical &amp; Medical Tips</span>
                        </div>
                        <div class="flex items-center space-x-2.5 text-xs text-gray-700 font-medium">
                            <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center flex-shrink-0">✓</span>
                            <span>Mobile Friendly Interface</span>
                        </div>
                    </div>
                </div>

                <!-- Curriculum & Syllabus -->
                <div class="bg-white rounded-3xl p-6 sm:p-8 border border-gray-100 shadow-xs">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">Course Syllabus &amp; Modules</h2>
                            <p class="text-xs text-gray-500 mt-0.5">
                                {{ $course->modules->count() }} Modules • {{ $course->lessons->count() }} Structured Video Lessons
                            </p>
                        </div>
                    </div>

                    <div class="space-y-4" x-data="{ activeModule: 0 }">
                        @forelse($course->modules as $mIndex => $module)
                            <div class="border border-gray-200 rounded-2xl overflow-hidden">
                                <button @click="activeModule = (activeModule === {{ $mIndex }} ? null : {{ $mIndex }})" class="w-full px-5 py-4 bg-gray-50/70 hover:bg-gray-100/70 flex items-center justify-between text-left text-sm font-bold text-gray-800 transition">
                                    <div class="flex items-center space-x-3">
                                        <span class="w-6 h-6 rounded-full bg-blue-100 text-blue-800 text-xs flex items-center justify-center font-bold">
                                            {{ $mIndex + 1 }}
                                        </span>
                                        <span>{{ $module->title }}</span>
                                    </div>
                                    <div class="flex items-center space-x-2 text-xs text-gray-400">
                                        <span>{{ $module->lessons->count() }} lessons</span>
                                        <svg class="w-4 h-4 transform transition-transform" :class="activeModule === {{ $mIndex }} ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                </button>

                                <div x-show="activeModule === {{ $mIndex }}" class="divide-y divide-gray-100 bg-white">
                                    @foreach($module->lessons as $lesson)
                                        <div class="px-5 py-3.5 flex items-center justify-between text-xs sm:text-sm hover:bg-gray-50/50 transition">
                                            <div class="flex items-center space-x-3">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <span class="text-gray-800 font-medium">{{ $lesson->title }}</span>
                                            </div>

                                            <div class="flex items-center space-x-3">
                                                <span class="text-xs text-gray-400">{{ $lesson->duration_minutes }} mins</span>
                                                @if($lesson->is_preview)
                                                    <button @click="previewVideoId = '{{ $lesson->video_id }}'; showModal = true" class="text-xs font-bold text-blue-700 bg-blue-50 px-2.5 py-1 rounded-full hover:bg-blue-100 transition">
                                                        Preview
                                                    </button>
                                                @else
                                                    <span class="text-gray-300">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-gray-500 italic">Curriculum modules are currently being synchronized for this batch.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Instructor Info -->
                <div class="bg-white rounded-3xl p-6 sm:p-8 border border-gray-100 shadow-xs flex items-center space-x-5">
                    <div class="w-16 h-16 rounded-2xl bg-slate-900 text-white font-extrabold flex items-center justify-center text-xl flex-shrink-0">
                        🇮🇳
                    </div>
                    <div>
                        <span class="text-xs font-bold uppercase text-gray-400">Chief Mentor / Faculty</span>
                        <h3 class="text-base font-bold text-gray-900">{{ $course->instructor_name ?? 'Senior Military Officers & Academic Mentors' }}</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Experienced in coaching thousands of successful candidates through SSB, Medicals &amp; Written examinations.</p>
                    </div>
                </div>

            </div>

            <!-- Right Sidebar Column: Pricing & Enrollment Action -->
            <div class="lg:col-span-4 sticky top-28 space-y-6">
                
                <div class="bg-white rounded-3xl p-6 sm:p-8 border border-gray-100 shadow-lg">
                    <!-- Price header -->
                    <div class="mb-6">
                        <span class="text-xs text-gray-500 font-semibold block mb-1">Batch Fee (One-Time)</span>
                        <div class="flex items-baseline space-x-2">
                            <span class="text-3xl font-black text-gray-900">{{ $course->formatted_price }}</span>
                            @if($course->original_price && $course->original_price > $course->price)
                                <span class="text-sm text-gray-400 line-through">₹ {{ number_format($course->original_price, 0) }}</span>
                                <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full">
                                    {{ $course->discount_percentage }}% OFF
                                </span>
                            @endif
                        </div>
                        <p class="text-[11px] text-gray-400 mt-1">Inclusive of all taxes &amp; digital materials</p>
                    </div>

                    <!-- Action Button -->
                    @if($isEnrolled)
                        <div class="space-y-3">
                            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold p-3 rounded-2xl flex items-center space-x-2">
                                <svg class="w-4 h-4 flex-shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span>You are enrolled in this batch!</span>
                            </div>
                            <a href="{{ route('student.courses.learn', $course->id) }}" class="w-full flex items-center justify-center py-3.5 px-6 rounded-full text-sm font-bold text-white bg-emerald-600 hover:bg-emerald-700 shadow-md transition">
                                Continue Learning →
                            </a>
                        </div>
                    @else
                        <a href="{{ route('student.checkout', $course->id) }}" class="w-full flex items-center justify-center py-3.5 px-6 rounded-full text-sm font-bold text-white bg-[#1D4ED8] hover:bg-[#1E40AF] shadow-md shadow-blue-500/20 transition-all transform hover:-translate-y-0.5">
                            Enroll Now &amp; Start Studying
                        </a>
                    @endif

                    <!-- Guarantees -->
                    <div class="mt-6 pt-6 border-t border-gray-100 space-y-2.5 text-xs text-gray-500">
                        <div class="flex items-center space-x-2">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                            <span>Instant Unlimited Access</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            <span>Secure Encrypted Payment (UPI, Cards)</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            <span>Dedicated Student Academic Support</span>
                        </div>
                    </div>
                </div>

                <!-- Need assistance card -->
                <div class="bg-gray-50 rounded-2xl p-5 border border-gray-200/60 text-xs text-gray-600 space-y-2">
                    <span class="font-bold text-gray-900 block">Have questions about this course?</span>
                    <p>Our academic counsellors can help you verify eligibility, age criteria, and trade requirements.</p>
                    <a href="{{ route('contact') }}" class="inline-block text-blue-700 font-bold hover:underline pt-1">
                        Contact Counsellor →
                    </a>
                </div>

            </div>

        </div>

    </div>

    <!-- Free Video Preview Modal -->
    <div x-show="showModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto bg-black/80 flex items-center justify-center p-4"
         style="display:none;">
        <div @click.away="showModal = false; previewVideoId = null" class="bg-black rounded-3xl overflow-hidden max-w-3xl w-full shadow-2xl relative">
            <button @click="showModal = false; previewVideoId = null" class="absolute top-3 right-3 text-white/70 hover:text-white bg-black/50 p-2 rounded-full z-10">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            <div class="aspect-video w-full">
                <template x-if="previewVideoId">
                    <iframe :src="'https://www.youtube-nocookie.com/embed/' + previewVideoId + '?autoplay=1'" class="w-full h-full" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </template>
            </div>
        </div>
    </div>
</div>
@endsection
