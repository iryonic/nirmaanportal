@extends('layouts.student')

@section('title', $lesson->title . ' — ' . $course->title)

@section('content')
<div class="py-6" x-data="{ sidebarOpen: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Top bar navigation -->
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center space-x-3">
                <a href="{{ route('student.dashboard') }}" class="p-2 rounded-xl bg-white border border-gray-200 text-gray-600 hover:text-gray-900 shadow-2xs transition" title="Back to Dashboard">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <div>
                    <h1 class="text-sm sm:text-base font-bold text-gray-900 truncate max-w-xs sm:max-w-md">{{ $course->title }}</h1>
                    <span class="text-xs text-gray-500 font-medium">{{ $completedLessonsCount }}/{{ $totalLessons }} Lessons Completed ({{ $progressPercent }}%)</span>
                </div>
            </div>

            <!-- Toggle Modules Drawer on mobile -->
            <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden px-3.5 py-1.5 rounded-xl bg-white border border-gray-200 text-xs font-bold text-gray-700 shadow-2xs flex items-center space-x-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
                <span>Curriculum</span>
            </button>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Left Player & Lesson Content -->
            <div class="lg:col-span-8 space-y-6">
                
                <!-- Video Container -->
                <div class="bg-black rounded-3xl overflow-hidden aspect-video shadow-2xl relative border-4 border-white">
                    @if($lesson->video_id)
                        <iframe src="https://www.youtube-nocookie.com/embed/{{ $lesson->video_id }}?rel=0&modestbranding=1" 
                                class="w-full h-full" 
                                frameborder="0" 
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                allowfullscreen></iframe>
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center text-gray-400 p-8 text-center">
                            <svg class="w-12 h-12 mb-3 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                            <p class="text-sm font-semibold">Video lecture is being compiled.</p>
                        </div>
                    @endif
                </div>

                <!-- Lesson Header & Controls -->
                <div class="bg-white rounded-3xl p-6 sm:p-8 border border-gray-100 shadow-xs space-y-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <span class="text-xs font-bold uppercase tracking-wider text-blue-700 bg-blue-50 px-2.5 py-0.5 rounded-full mb-2 inline-block">
                                {{ $lesson->module->title ?? 'Module' }}
                            </span>
                            <h2 class="text-xl sm:text-2xl font-extrabold text-gray-900 leading-tight">
                                {{ $lesson->title }}
                            </h2>
                            <p class="text-xs text-gray-400 mt-1">Duration: {{ $lesson->duration_minutes }} Minutes</p>
                        </div>

                        <!-- Mark Complete Button -->
                        <form action="{{ route('student.lessons.toggle', $lesson->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-5 py-2.5 rounded-full text-xs font-bold transition flex items-center space-x-2 {{ $isCompleted ? 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200' : 'bg-blue-700 text-white hover:bg-blue-800 shadow-xs' }}">
                                <svg class="w-4 h-4 {{ $isCompleted ? 'text-emerald-700' : 'text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span>{{ $isCompleted ? 'Completed ✓' : 'Mark as Complete' }}</span>
                            </button>
                        </form>
                    </div>

                    <!-- Lesson Description -->
                    <div class="pt-4 border-t border-gray-100 text-xs sm:text-sm text-gray-600 leading-relaxed">
                        {{ $lesson->description ?? 'Watch the video lecture with focused attention. Make notes of core concepts and formulas for quick reference before the mock test.' }}
                    </div>

                    <!-- Attached Study Material / Notes -->
                    @if($lesson->attachment_title)
                        <div class="p-4 rounded-2xl bg-gray-50 border border-gray-200/70 flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 rounded-lg bg-red-100 text-red-700 flex items-center justify-center font-bold text-xs">
                                    PDF
                                </div>
                                <div>
                                    <h4 class="text-xs font-bold text-gray-800">{{ $lesson->attachment_title }}</h4>
                                    <span class="text-[10px] text-gray-400">Class Handwritten Summary Notes</span>
                                </div>
                            </div>
                            <a href="{{ route('resources.index') }}" class="px-3.5 py-1.5 rounded-full text-xs font-bold text-blue-700 bg-white border border-gray-200 hover:bg-blue-50 transition">
                                Download
                            </a>
                        </div>
                    @endif

                    <!-- Prev / Next Navigation -->
                    <div class="pt-6 border-t border-gray-100 flex items-center justify-between">
                        @if($previousLesson)
                            <a href="{{ route('student.courses.learn', [$course->id, $previousLesson->id]) }}" class="inline-flex items-center space-x-2 text-xs font-bold text-gray-700 hover:text-blue-700">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                                <span>Previous Lesson</span>
                            </a>
                        @else
                            <span></span>
                        @endif

                        @if($nextLesson)
                            <a href="{{ route('student.courses.learn', [$course->id, $nextLesson->id]) }}" class="inline-flex items-center space-x-2 text-xs font-bold text-blue-700 hover:text-blue-900">
                                <span>Next Lesson</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </a>
                        @else
                            <a href="{{ route('student.tests.index') }}" class="inline-flex items-center space-x-2 text-xs font-bold text-emerald-700 hover:text-emerald-900">
                                <span>Attempt Mock Test →</span>
                            </a>
                        @endif
                    </div>
                </div>

            </div>

            <!-- Right Sidebar: Modules and Lessons List (Always visible on desktop, drawer on mobile) -->
            <div :class="sidebarOpen ? 'block' : 'hidden lg:block'" class="lg:col-span-4 bg-white rounded-3xl border border-gray-100 p-5 shadow-xs max-h-[85vh] overflow-y-auto">
                <div class="flex items-center justify-between pb-3 border-b border-gray-100 mb-4">
                    <h3 class="text-sm font-bold text-gray-900">Course Syllabus</h3>
                    <span class="text-xs text-gray-400 font-medium">{{ $totalLessons }} Lessons</span>
                </div>

                <div class="space-y-4">
                    @foreach($course->modules as $modIndex => $module)
                        <div>
                            <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block mb-2">
                                {{ $module->title }}
                            </span>
                            <div class="space-y-1">
                                @foreach($module->lessons as $l)
                                    @php
                                        $isCurrent = $l->id === $lesson->id;
                                        $completed = $l->isCompletedBy(Auth::user());
                                    @endphp
                                    <a href="{{ route('student.courses.learn', [$course->id, $l->id]) }}" class="flex items-center justify-between p-2.5 rounded-xl text-xs transition {{ $isCurrent ? 'bg-blue-50 text-blue-800 font-bold border border-blue-200' : 'text-gray-700 hover:bg-gray-50' }}">
                                        <div class="flex items-center space-x-2.5 truncate mr-2">
                                            <span class="w-4 h-4 rounded-full flex items-center justify-center flex-shrink-0 {{ $completed ? 'bg-emerald-500 text-white' : ($isCurrent ? 'bg-blue-600 text-white' : 'border border-gray-300 text-transparent') }}">
                                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                            </span>
                                            <span class="truncate">{{ $l->title }}</span>
                                        </div>
                                        <span class="text-[10px] text-gray-400 flex-shrink-0">{{ $l->duration_minutes }}m</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>

    </div>
</div>
@endsection
