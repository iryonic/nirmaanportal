@extends('layouts.student')

@section('title', $lesson->title . ' — ' . $course->title)

@section('content')
<div class="py-6 sm:py-8" x-data="{ sidebarOpen: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-5">
        
        <!-- Top bar navigation -->
        <div class="flex items-center justify-between pb-3 border-b border-slate-200">
            <div class="flex items-center space-x-3">
                <a href="{{ route('student.dashboard') }}" class="p-2 rounded-lg bg-white border border-slate-200 text-slate-600 hover:text-slate-900 shadow-2xs transition-colors" title="Back to Dashboard">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <div>
                    <h1 class="text-sm sm:text-base font-bold text-slate-900 truncate max-w-xs sm:max-w-md">{{ $course->title }}</h1>
                    <span class="text-xs text-slate-500 font-medium font-mono">{{ $completedLessonsCount }}/{{ $totalLessons }} Lessons Completed ({{ $progressPercent }}%)</span>
                </div>
            </div>

            <!-- Toggle Modules Drawer on mobile -->
            <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden px-3 py-1.5 rounded-lg bg-white border border-slate-200 text-xs font-medium text-slate-700 shadow-2xs flex items-center space-x-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
                <span>Curriculum</span>
            </button>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            
            <!-- Left Player & Lesson Content -->
            <div class="lg:col-span-8 space-y-5">
                
                <!-- Video Container -->
                <div class="bg-black rounded-xl overflow-hidden aspect-video border border-slate-900 relative shadow-2xs">
                    @if($lesson->video_id)
                        <iframe src="https://www.youtube-nocookie.com/embed/{{ $lesson->video_id }}?rel=0&modestbranding=1" 
                                class="w-full h-full" 
                                frameborder="0" 
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                allowfullscreen></iframe>
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center text-slate-400 p-8 text-center">
                            <svg class="w-10 h-10 mb-2 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                            <p class="text-xs font-medium text-slate-400">Video lecture is currently being compiled for this lesson.</p>
                        </div>
                    @endif
                </div>

                <!-- Lesson Header & Controls -->
                <div class="bg-white rounded-xl p-5 sm:p-6 border border-slate-200 shadow-2xs space-y-5">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <span class="text-[11px] font-semibold uppercase tracking-wider text-blue-900 bg-blue-50 px-2 py-0.5 rounded border border-blue-200 mb-2 inline-block">
                                {{ $lesson->module->title ?? 'Module' }}
                            </span>
                            <h2 class="text-lg sm:text-xl font-bold text-slate-900 tracking-tight leading-snug">
                                {{ $lesson->title }}
                            </h2>
                            <p class="text-xs text-slate-400 mt-0.5 font-mono">Duration: {{ $lesson->duration_minutes }} Minutes</p>
                        </div>

                        <!-- Mark Complete Button -->
                        <form action="{{ route('student.lessons.toggle', $lesson->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-4 py-2 rounded-lg text-xs font-medium transition-colors flex items-center space-x-1.5 shadow-2xs {{ $isCompleted ? 'bg-emerald-50 text-emerald-800 border border-emerald-200 hover:bg-emerald-100' : 'bg-blue-950 text-white hover:bg-blue-900' }}">
                                <svg class="w-3.5 h-3.5 {{ $isCompleted ? 'text-emerald-700' : 'text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span>{{ $isCompleted ? 'Completed ✓' : 'Mark as Complete' }}</span>
                            </button>
                        </form>
                    </div>

                    <!-- Lesson Description -->
                    <div class="pt-3 border-t border-slate-100 text-xs sm:text-sm text-slate-600 leading-relaxed">
                        {{ $lesson->description ?? 'Watch the video lecture with focused attention. Make notes of core concepts and formulas for quick reference before the mock test.' }}
                    </div>

                    <!-- Attached Study Material / Notes -->
                    @if($lesson->attachment_title)
                        <div class="p-3.5 rounded-lg bg-slate-50 border border-slate-200 flex items-center justify-between">
                            <div class="flex items-center space-x-2.5">
                                <div class="w-7 h-7 rounded-md bg-rose-50 border border-rose-200 text-rose-700 flex items-center justify-center font-bold text-[10px]">
                                    PDF
                                </div>
                                <div>
                                    <h4 class="text-xs font-semibold text-slate-900">{{ $lesson->attachment_title }}</h4>
                                    <span class="text-[10px] text-slate-400">Class Handwritten Summary Notes</span>
                                </div>
                            </div>
                            <a href="{{ route('resources.index') }}" class="px-3 py-1 rounded-md text-xs font-medium text-blue-950 bg-white border border-slate-200 hover:bg-slate-50 transition-colors shadow-2xs">
                                Download
                            </a>
                        </div>
                    @endif

                    <!-- Prev / Next Navigation -->
                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                        @if($previousLesson)
                            <a href="{{ route('student.courses.learn', [$course->id, $previousLesson->id]) }}" class="inline-flex items-center space-x-1.5 text-xs font-medium text-slate-700 hover:text-blue-950 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                                <span>Previous Lesson</span>
                            </a>
                        @else
                            <span></span>
                        @endif

                        @if($nextLesson)
                            <a href="{{ route('student.courses.learn', [$course->id, $nextLesson->id]) }}" class="inline-flex items-center space-x-1.5 text-xs font-medium text-blue-950 hover:underline">
                                <span>Next Lesson</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </a>
                        @else
                            <a href="{{ route('student.tests.index') }}" class="inline-flex items-center space-x-1.5 text-xs font-medium text-emerald-700 hover:underline">
                                <span>Attempt Mock Test →</span>
                            </a>
                        @endif
                    </div>
                </div>

            </div>

            <!-- Right Sidebar: Modules and Lessons List -->
            <div :class="sidebarOpen ? 'block' : 'hidden lg:block'" class="lg:col-span-4 bg-white rounded-xl border border-slate-200 p-4 shadow-2xs max-h-[85vh] overflow-y-auto">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-3">
                    <h3 class="text-xs font-semibold uppercase tracking-wider text-slate-500">Course Syllabus</h3>
                    <span class="text-xs text-slate-400 font-mono font-medium">{{ $totalLessons }} Lessons</span>
                </div>

                <div class="space-y-3.5">
                    @foreach($course->modules as $modIndex => $module)
                        <div>
                            <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider block mb-1.5">
                                {{ $module->title }}
                            </span>
                            <div class="space-y-1">
                                @foreach($module->lessons as $l)
                                    @php
                                        $isCurrent = $l->id === $lesson->id;
                                        $completed = $l->isCompletedBy(Auth::user());
                                    @endphp
                                    <a href="{{ route('student.courses.learn', [$course->id, $l->id]) }}" class="flex items-center justify-between p-2 rounded-lg text-xs transition-colors {{ $isCurrent ? 'bg-blue-50 text-blue-950 font-semibold border border-blue-200' : 'text-slate-700 hover:bg-slate-50' }}">
                                        <div class="flex items-center space-x-2 truncate mr-2">
                                            <span class="w-3.5 h-3.5 rounded-full flex items-center justify-center flex-shrink-0 {{ $completed ? 'bg-emerald-600 text-white' : ($isCurrent ? 'bg-blue-950 text-white' : 'border border-slate-300 text-transparent') }}">
                                                <svg class="w-2 h-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                            </span>
                                            <span class="truncate">{{ $l->title }}</span>
                                        </div>
                                        <span class="text-[10px] text-slate-400 font-mono flex-shrink-0">{{ $l->duration_minutes }}m</span>
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
