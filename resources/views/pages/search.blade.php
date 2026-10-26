@extends('layouts.app')

@section('title', 'Search Results for "' . $query . '" — Nirmaan')

@section('content')
<div class="py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-8">
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900">
                Search Results for <span class="text-blue-700">"{{ $query }}"</span>
            </h1>
            <p class="text-xs text-gray-500 mt-1">Found {{ $courses->count() }} courses and {{ $tests->count() }} mock tests.</p>
        </div>

        @if($courses->count() > 0)
            <div class="mb-12">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Courses</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($courses as $course)
                        <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-xs flex flex-col justify-between">
                            <div>
                                <span class="text-[11px] font-bold text-blue-700 bg-blue-50 px-2.5 py-0.5 rounded-full">
                                    {{ $course->examCategory ? $course->examCategory->name : 'Defence' }}
                                </span>
                                <h3 class="text-base font-bold text-gray-900 mt-2 mb-1">
                                    <a href="{{ route('courses.show', $course->slug) }}" class="hover:text-blue-700 transition">
                                        {{ $course->title }}
                                    </a>
                                </h3>
                                <p class="text-xs text-gray-500 mb-4">{{ $course->subtitle }}</p>
                            </div>
                            <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                                <span class="font-bold text-gray-900 text-sm">{{ $course->formatted_price }}</span>
                                <a href="{{ route('courses.show', $course->slug) }}" class="text-xs font-bold text-blue-700 hover:underline">
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
                <h2 class="text-lg font-bold text-gray-900 mb-4">Mock Tests</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($tests as $test)
                        <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-xs flex items-center justify-between">
                            <div>
                                <h3 class="text-sm font-bold text-gray-900">{{ $test->title }}</h3>
                                <p class="text-xs text-gray-500 mt-0.5">{{ $test->duration_minutes }} Mins • {{ number_format($test->total_marks, 0) }} Marks</p>
                            </div>
                            <a href="{{ route('student.tests.instructions', $test->id) }}" class="px-4 py-1.5 rounded-full text-xs font-bold text-white bg-blue-700 hover:bg-blue-800 transition">
                                Attempt
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if($courses->isEmpty() && $tests->isEmpty())
            <div class="bg-white rounded-3xl p-12 text-center border border-gray-100 max-w-md mx-auto">
                <p class="text-sm text-gray-500 mb-4">No courses or tests matched your search.</p>
                <a href="{{ route('courses.index') }}" class="px-5 py-2.5 rounded-full text-xs font-bold text-white bg-blue-700">
                    Browse All Courses
                </a>
            </div>
        @endif

    </div>
</div>
@endsection
