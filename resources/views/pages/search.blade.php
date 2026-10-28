@extends('layouts.app')

@section('title', 'Search Results for "' . $query . '" — Nirmaan')

@section('content')
<div class="py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <div class="pb-4 border-b border-slate-200">
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                Search Results for <span class="text-blue-950">"{{ $query }}"</span>
            </h1>
            <p class="text-xs text-slate-500 mt-1">Found {{ $courses->count() }} courses and {{ $tests->count() }} mock tests.</p>
        </div>

        @if($courses->count() > 0)
            <div>
                <h2 class="text-base sm:text-lg font-bold text-slate-900 mb-4 tracking-tight">Course Batches</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($courses as $course)
                        <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-2xs hover:border-slate-300 transition flex flex-col justify-between">
                            <div>
                                <span class="text-[11px] font-semibold text-blue-950 bg-blue-50 px-2.5 py-0.5 rounded border border-blue-200">
                                    {{ $course->examCategory ? $course->examCategory->name : 'Defence' }}
                                </span>
                                <h3 class="text-base font-bold text-slate-900 mt-2.5 mb-1">
                                    <a href="{{ route('courses.show', $course->slug) }}" class="hover:text-blue-950 transition">
                                        {{ $course->title }}
                                    </a>
                                </h3>
                                <p class="text-xs text-slate-500 mb-4 line-clamp-2 leading-relaxed">{{ $course->subtitle }}</p>
                            </div>
                            <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                                <span class="font-bold text-slate-900 text-sm">{{ $course->formatted_price }}</span>
                                <a href="{{ route('courses.show', $course->slug) }}" class="text-xs font-semibold text-blue-950 hover:underline">
                                    View Batch →
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if($tests->count() > 0)
            <div>
                <h2 class="text-base sm:text-lg font-bold text-slate-900 mb-4 tracking-tight">Mock Tests</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($tests as $test)
                        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-2xs hover:border-slate-300 transition flex items-center justify-between">
                            <div>
                                <h3 class="text-sm font-bold text-slate-900">{{ $test->title }}</h3>
                                <p class="text-xs text-slate-500 mt-0.5 font-mono">{{ $test->duration_minutes }} Mins • {{ number_format($test->total_marks, 0) }} Marks</p>
                            </div>
                            <a href="{{ route('student.tests.instructions', $test->id) }}" class="px-4 py-1.5 rounded-lg text-xs font-semibold text-white bg-blue-950 hover:bg-blue-900 shadow-2xs transition">
                                Attempt
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if($courses->isEmpty() && $tests->isEmpty())
            <div class="bg-white rounded-xl p-12 text-center border border-slate-200 shadow-2xs max-w-md mx-auto">
                <p class="text-sm text-slate-500 mb-4">No courses or tests matched your search.</p>
                <a href="{{ route('courses.index') }}" class="inline-flex items-center px-4 py-2 rounded-lg text-xs font-semibold text-white bg-blue-950 hover:bg-blue-900 shadow-2xs transition">
                    Browse All Courses
                </a>
            </div>
        @endif

    </div>
</div>
@endsection
