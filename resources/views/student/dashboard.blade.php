@extends('layouts.student')

@section('title', 'Student Learning Dashboard — Nirmaan')

@section('content')
<div class="py-8 sm:py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- Welcome Banner (Linear / Blue-950 aesthetic) -->
        <div class="bg-blue-950 rounded-2xl p-6 sm:p-8 text-white border border-blue-900 relative overflow-hidden shadow-2xs">
            <div class="absolute right-0 top-0 bottom-0 w-1/3 opacity-5 flex items-center justify-center pointer-events-none">
                <img src="{{ asset('images/army-badge.svg') }}" class="h-64 w-64 object-contain">
            </div>

            <div class="relative z-10 max-w-2xl space-y-3">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-[11px] font-semibold bg-white/10 text-blue-200 border border-white/20">
                    🎖 Target: {{ $user->target_exam ?? 'Indian Armed Forces' }}
                </span>
                <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-white">
                    Jai Hind, {{ $user->name }}
                </h1>
                <p class="text-xs sm:text-sm text-slate-300 leading-relaxed font-normal">
                    Consistency is the hallmark of every soldier. Follow your daily curriculum milestones, solve topic-wise drill questions, and take timed CBT mock tests.
                </p>
                <div class="pt-2 flex flex-wrap gap-2.5">
                    <a href="{{ route('student.practice') }}" class="inline-flex items-center space-x-1.5 px-4 py-2 rounded-lg text-xs font-medium text-slate-900 bg-white hover:bg-slate-100 transition-colors shadow-2xs">
                        <svg class="w-3.5 h-3.5 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                        <span>Practice Drill Bank</span>
                    </a>
                    <a href="{{ route('student.tests.index') }}" class="inline-flex items-center space-x-1.5 px-4 py-2 rounded-lg text-xs font-medium text-white bg-blue-900 hover:bg-blue-800 border border-blue-800 transition-colors shadow-2xs">
                        <svg class="w-3.5 h-3.5 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>CBT Mock Tests</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Real Stats Row (Linear Metric Tiles) -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl p-4 sm:p-5 border border-slate-200 shadow-2xs">
                <span class="text-xs text-slate-500 font-medium block">Active Courses</span>
                <span class="text-2xl font-bold tracking-tight text-slate-900 mt-1 block font-mono">{{ $enrollments->count() }}</span>
                <span class="text-[11px] text-emerald-700 font-medium mt-1 flex items-center space-x-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-600 inline-block"></span>
                    <span>Enrolled &amp; Valid</span>
                </span>
            </div>

            <div class="bg-white rounded-xl p-4 sm:p-5 border border-slate-200 shadow-2xs">
                <span class="text-xs text-slate-500 font-medium block">Completed Lessons</span>
                <span class="text-2xl font-bold tracking-tight text-slate-900 mt-1 block font-mono">{{ count($completedLessonIds) }}</span>
                <span class="text-[11px] text-blue-700 font-medium mt-1 flex items-center space-x-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-600 inline-block"></span>
                    <span>Lectures &amp; Notes</span>
                </span>
            </div>

            <div class="bg-white rounded-xl p-4 sm:p-5 border border-slate-200 shadow-2xs">
                <span class="text-xs text-slate-500 font-medium block">Tests Attempted</span>
                <span class="text-2xl font-bold tracking-tight text-slate-900 mt-1 block font-mono">{{ $totalTestsAttempted }}</span>
                <span class="text-[11px] text-purple-700 font-medium mt-1 flex items-center space-x-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-purple-600 inline-block"></span>
                    <span>Full Mocks Evaluated</span>
                </span>
            </div>

            <div class="bg-white rounded-xl p-4 sm:p-5 border border-slate-200 shadow-2xs">
                <span class="text-xs text-slate-500 font-medium block">Average Accuracy</span>
                <span class="text-2xl font-bold tracking-tight text-slate-900 mt-1 block font-mono">{{ number_format($averageAccuracy, 1) }}%</span>
                <span class="text-[11px] text-amber-700 font-medium mt-1 flex items-center space-x-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-600 inline-block"></span>
                    <span>Target &gt; 85% for Merit</span>
                </span>
            </div>
        </div>

        <!-- Enrolled Courses Section -->
        <div class="space-y-4">
            <div class="flex items-center justify-between pb-2 border-b border-slate-200">
                <h2 class="text-sm font-semibold uppercase tracking-wider text-slate-500">My Enrolled Courses</h2>
                <a href="{{ route('courses.index') }}" class="text-xs font-medium text-blue-950 hover:underline">
                    Browse All Batches →
                </a>
            </div>

            @if($enrollments->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    @foreach($enrollments as $enrollment)
                        @php
                            $course = $enrollment->course;
                            $allLessonIds = $course->modules->flatMap->lessons->pluck('id')->toArray();
                            $courseCompletedCount = count(array_intersect($allLessonIds, $completedLessonIds));
                            $totalCourseLessons = count($allLessonIds);
                            $progressPct = $totalCourseLessons > 0 ? round(($courseCompletedCount / $totalCourseLessons) * 100) : 0;
                        @endphp
                        <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-2xs hover:border-slate-300 transition-colors flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between mb-2.5">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-blue-50 text-blue-900 border border-blue-200">
                                        {{ $course->examCategory ? $course->examCategory->name : 'Defence' }}
                                    </span>
                                    <span class="inline-flex items-center space-x-1 text-[11px] text-emerald-700 font-medium">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                                        <span>Active</span>
                                    </span>
                                </div>
                                <h3 class="text-sm font-bold text-slate-900">{{ $course->title }}</h3>
                                <p class="text-xs text-slate-500 mt-1 line-clamp-1">{{ $course->subtitle }}</p>

                                <!-- Progress bar -->
                                <div class="mt-4 space-y-1.5">
                                    <div class="flex items-center justify-between text-xs text-slate-500">
                                        <span>Progress: {{ $courseCompletedCount }}/{{ $totalCourseLessons }} lessons</span>
                                        <span class="font-bold text-slate-900 font-mono">{{ $progressPct }}%</span>
                                    </div>
                                    <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                                        <div class="bg-blue-950 h-full rounded-full transition-all duration-500" style="width: {{ $progressPct }}%"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-5 pt-3.5 border-t border-slate-100 flex items-center justify-between">
                                <span class="text-[11px] text-slate-400 font-medium">Valid until: {{ $enrollment->expires_at ? $enrollment->expires_at->format('M Y') : '6 Months' }}</span>
                                <a href="{{ route('student.courses.learn', $course->id) }}" class="inline-flex items-center space-x-1.5 px-3.5 py-1.5 rounded-lg text-xs font-medium text-white bg-blue-950 hover:bg-blue-900 transition-colors shadow-2xs">
                                    <span>Continue LMS</span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white rounded-xl p-8 text-center border border-slate-200 shadow-2xs max-w-lg mx-auto">
                    <p class="text-xs text-slate-500 mb-4">You have not enrolled in any paid course batches yet.</p>
                    <a href="{{ route('courses.index') }}" class="inline-flex items-center px-4 py-2 rounded-lg text-xs font-medium text-white bg-blue-950 hover:bg-blue-900 transition-colors shadow-2xs">
                        Explore Batches &amp; Enroll
                    </a>
                </div>
            @endif
        </div>

        <!-- Available Mock Tests & Recent Results -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            <!-- Available Tests -->
            <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-2xs space-y-4">
                <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                    <h2 class="text-xs font-semibold uppercase tracking-wider text-slate-500">Upcoming CBT Mock Tests</h2>
                    <a href="{{ route('student.tests.index') }}" class="text-xs font-medium text-blue-950 hover:underline">
                        View All
                    </a>
                </div>

                <div class="space-y-2.5">
                    @forelse($availableTests as $test)
                        <div class="p-3 rounded-lg bg-slate-50 border border-slate-200/80 flex items-center justify-between">
                            <div>
                                <h4 class="text-xs font-bold text-slate-900">{{ $test->title }}</h4>
                                <span class="text-[11px] text-slate-500 font-mono">{{ $test->duration_minutes }}m • {{ number_format($test->total_marks, 0) }} Marks • -{{ $test->negative_marking_ratio }} Negative</span>
                            </div>
                            <a href="{{ route('student.tests.instructions', $test->id) }}" class="inline-flex items-center px-3 py-1 rounded-md text-xs font-medium text-blue-950 bg-white border border-slate-200 hover:bg-slate-100 transition-colors shadow-2xs">
                                Start
                            </a>
                        </div>
                    @empty
                        <div class="text-xs text-slate-400 py-4 text-center italic">No upcoming tests at this time.</div>
                    @endforelse
                </div>
            </div>

            <!-- Recent Attempts -->
            <div class="bg-white rounded-xl p-5 border border-slate-200 shadow-2xs space-y-4">
                <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                    <h2 class="text-xs font-semibold uppercase tracking-wider text-slate-500">Recent Test Results</h2>
                    <span class="text-[11px] text-slate-400 font-medium">Evaluation History</span>
                </div>

                @if($recentAttempts->count() > 0)
                    <div class="space-y-2.5">
                        @foreach($recentAttempts as $attempt)
                            <div class="p-3 rounded-lg bg-slate-50 border border-slate-200/80 flex items-center justify-between">
                                <div>
                                    <h4 class="text-xs font-bold text-slate-900">{{ $attempt->mockTest->title }}</h4>
                                    <span class="text-[11px] text-slate-500 font-mono">
                                        Score: <strong class="text-slate-900 font-bold">{{ $attempt->score }}/{{ number_format($attempt->mockTest->total_marks, 0) }}</strong> • Accuracy: {{ $attempt->accuracy_percentage }}%
                                    </span>
                                </div>
                                <a href="{{ route('student.tests.result', $attempt->id) }}" class="text-xs font-semibold text-blue-950 hover:underline">
                                    Scorecard →
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-xs text-slate-400 italic">
                        No test attempts yet. Complete your first mock test to see your score report!
                    </div>
                @endif
            </div>

        </div>

    </div>
</div>
@endsection
