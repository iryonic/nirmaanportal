@extends('layouts.student')

@section('title', 'Student Learning Dashboard — Nirmaan')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- Welcome Banner -->
        <div class="bg-slate-900 rounded-2xl p-6 sm:p-8 text-white border border-slate-800 relative overflow-hidden">
            <div class="absolute right-0 top-0 bottom-0 w-1/3 opacity-10 flex items-center justify-center pointer-events-none">
                <img src="{{ asset('images/army-badge.svg') }}" class="h-64 w-64 object-contain">
            </div>

            <div class="relative z-10 max-w-2xl space-y-3">
                <span class="px-3 py-1 rounded-md text-xs font-bold bg-white/10 text-blue-200 border border-white/20 inline-block">
                    🎖 Target: {{ $user->target_exam ?? 'Indian Armed Forces' }}
                </span>
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">
                    Jai Hind, {{ $user->name }}!
                </h1>
                <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">
                    Consistency is the hallmark of every soldier. Stick to your daily study timetable and attempt weekly timed mock tests to build speed and accuracy.
                </p>
                <div class="pt-2 flex flex-wrap gap-3">
                    <a href="{{ route('student.practice') }}" class="px-5 py-2.5 rounded-xl text-xs font-bold text-slate-900 bg-white hover:bg-slate-100 transition">
                        Practice Questions
                    </a>
                    <a href="{{ route('student.tests.index') }}" class="px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-blue-700 hover:bg-blue-800 transition">
                        Mock Tests
                    </a>
                </div>
            </div>
        </div>

        <!-- Real Stats Row -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-2xs">
                <span class="text-xs text-gray-500 font-medium block">Active Courses</span>
                <span class="text-2xl font-black text-gray-900 mt-1 block">{{ $enrollments->count() }}</span>
                <span class="text-[10px] text-emerald-600 font-semibold mt-1 block">● Enrolled &amp; Valid</span>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-2xs">
                <span class="text-xs text-gray-500 font-medium block">Completed Lessons</span>
                <span class="text-2xl font-black text-gray-900 mt-1 block">{{ count($completedLessonIds) }}</span>
                <span class="text-[10px] text-blue-600 font-semibold mt-1 block">Video &amp; Notes Finished</span>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-2xs">
                <span class="text-xs text-gray-500 font-medium block">Tests Attempted</span>
                <span class="text-2xl font-black text-gray-900 mt-1 block">{{ $totalTestsAttempted }}</span>
                <span class="text-[10px] text-purple-600 font-semibold mt-1 block">Full Length Mocks</span>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-2xs">
                <span class="text-xs text-gray-500 font-medium block">Average Accuracy</span>
                <span class="text-2xl font-black text-gray-900 mt-1 block">{{ number_format($averageAccuracy, 1) }}%</span>
                <span class="text-[10px] text-amber-600 font-semibold mt-1 block">Target &gt; 85% for Merit</span>
            </div>
        </div>

        <!-- Enrolled Courses Section -->
        <div>
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-gray-900">My Enrolled Courses</h2>
                <a href="{{ route('courses.index') }}" class="text-xs font-semibold text-blue-700 hover:underline">
                    Browse More Batches →
                </a>
            </div>

            @if($enrollments->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($enrollments as $enrollment)
                        @php
                            $course = $enrollment->course;
                            $allLessonIds = $course->modules->flatMap->lessons->pluck('id')->toArray();
                            $courseCompletedCount = count(array_intersect($allLessonIds, $completedLessonIds));
                            $totalCourseLessons = count($allLessonIds);
                            $progressPct = $totalCourseLessons > 0 ? round(($courseCompletedCount / $totalCourseLessons) * 100) : 0;
                        @endphp
                        <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-xs hover:shadow-md transition flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between mb-3">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-100">
                                        {{ $course->examCategory ? $course->examCategory->name : 'Defence' }}
                                    </span>
                                    <span class="text-[11px] text-emerald-600 font-semibold">Active Enrollment</span>
                                </div>
                                <h3 class="text-base font-bold text-gray-900">{{ $course->title }}</h3>
                                <p class="text-xs text-gray-500 mt-0.5">{{ $course->subtitle }}</p>

                                <!-- Progress bar -->
                                <div class="mt-4 space-y-1.5">
                                    <div class="flex items-center justify-between text-xs">
                                        <span class="text-gray-500">Progress: {{ $courseCompletedCount }}/{{ $totalCourseLessons }} lessons</span>
                                        <span class="font-bold text-gray-800">{{ $progressPct }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-100 h-2 rounded-full overflow-hidden">
                                        <div class="bg-blue-600 h-full rounded-full transition-all duration-500" style="width: {{ $progressPct }}%"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6 pt-4 border-t border-gray-100 flex items-center justify-between">
                                <span class="text-[11px] text-gray-400">Valid until: {{ $enrollment->expires_at ? $enrollment->expires_at->format('M Y') : '6 Months' }}</span>
                                <a href="{{ route('student.courses.learn', $course->id) }}" class="px-5 py-2 rounded-full text-xs font-bold text-white bg-blue-700 hover:bg-blue-800 transition shadow-xs">
                                    Continue Learning →
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white rounded-3xl p-8 text-center border border-gray-100 max-w-lg mx-auto">
                    <p class="text-xs text-gray-500 mb-4">You have not enrolled in any paid course yet.</p>
                    <a href="{{ route('courses.index') }}" class="px-6 py-2.5 rounded-full text-xs font-bold text-white bg-blue-700">
                        Explore Batches &amp; Enroll
                    </a>
                </div>
            @endif
        </div>

        <!-- Available Mock Tests & Recent Results -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            <!-- Available Tests -->
            <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-xs">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-base font-bold text-gray-900">Upcoming Mock Tests</h2>
                    <a href="{{ route('student.tests.index') }}" class="text-xs font-semibold text-blue-700 hover:underline">
                        View All
                    </a>
                </div>

                <div class="space-y-3">
                    @foreach($availableTests as $test)
                        <div class="p-3.5 rounded-2xl bg-gray-50/70 border border-gray-100 flex items-center justify-between">
                            <div>
                                <h4 class="text-xs font-bold text-gray-900">{{ $test->title }}</h4>
                                <span class="text-[11px] text-gray-500">{{ $test->duration_minutes }} Mins • {{ number_format($test->total_marks, 0) }} Marks • -{{ $test->negative_marking_ratio }} Negative</span>
                            </div>
                            <a href="{{ route('student.tests.instructions', $test->id) }}" class="px-3.5 py-1.5 rounded-full text-xs font-bold text-blue-700 bg-white border border-gray-200 hover:bg-blue-50 transition shadow-2xs">
                                Start
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Recent Attempts -->
            <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-xs">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-base font-bold text-gray-900">Recent Test Results</h2>
                    <span class="text-xs text-gray-400">Latest Attempts</span>
                </div>

                @if($recentAttempts->count() > 0)
                    <div class="space-y-3">
                        @foreach($recentAttempts as $attempt)
                            <div class="p-3.5 rounded-2xl bg-gray-50/70 border border-gray-100 flex items-center justify-between">
                                <div>
                                    <h4 class="text-xs font-bold text-gray-900">{{ $attempt->mockTest->title }}</h4>
                                    <span class="text-[11px] text-gray-500">
                                        Score: <strong class="text-emerald-700">{{ $attempt->score }}/{{ number_format($attempt->mockTest->total_marks, 0) }}</strong> • Accuracy: {{ $attempt->accuracy_percentage }}%
                                    </span>
                                </div>
                                <a href="{{ route('student.tests.result', $attempt->id) }}" class="text-xs font-bold text-blue-700 hover:underline">
                                    Review →
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-xs text-gray-400 italic">
                        No test attempts yet. Complete your first mock test to see your score report!
                    </div>
                @endif
            </div>

        </div>

    </div>
</div>
@endsection
