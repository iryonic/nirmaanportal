@extends('layouts.student')

@section('title', 'Exam Instructions — ' . $test->title)

@section('content')
<div class="py-10">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="bg-white rounded-3xl p-6 sm:p-10 border border-gray-100 shadow-xl space-y-8">
            
            <!-- Header -->
            <div class="border-b border-gray-100 pb-6">
                <span class="text-xs font-bold uppercase tracking-wider text-blue-700 bg-blue-50 px-3 py-1 rounded-full mb-2 inline-block">
                    {{ $test->examCategory ? $test->examCategory->name : 'Defence Exam' }}
                </span>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 leading-tight">
                    {{ $test->title }}
                </h1>
                <p class="text-xs text-gray-500 mt-1">Please read the instructions carefully before launching the test engine.</p>
            </div>

            <!-- Key Parameters Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                <div class="p-3.5 rounded-2xl bg-gray-50 border border-gray-100 text-center">
                    <span class="text-[10px] text-gray-400 block font-semibold uppercase">Total Questions</span>
                    <span class="text-lg font-black text-gray-800">{{ $totalQuestions }}</span>
                </div>
                <div class="p-3.5 rounded-2xl bg-gray-50 border border-gray-100 text-center">
                    <span class="text-[10px] text-gray-400 block font-semibold uppercase">Duration</span>
                    <span class="text-lg font-black text-gray-800">{{ $test->duration_minutes }} Mins</span>
                </div>
                <div class="p-3.5 rounded-2xl bg-gray-50 border border-gray-100 text-center">
                    <span class="text-[10px] text-gray-400 block font-semibold uppercase">Total Marks</span>
                    <span class="text-lg font-black text-gray-800">{{ number_format($test->total_marks, 0) }}</span>
                </div>
                <div class="p-3.5 rounded-2xl bg-gray-50 border border-gray-100 text-center">
                    <span class="text-[10px] text-gray-400 block font-semibold uppercase">Negative Mark</span>
                    <span class="text-lg font-black text-red-600">-{{ $test->negative_marking_ratio }}</span>
                </div>
            </div>

            <!-- Rules & Guidelines -->
            <div class="space-y-3 text-xs sm:text-sm text-gray-700 leading-relaxed bg-blue-50/40 p-5 rounded-2xl border border-blue-100/60">
                <h3 class="font-bold text-gray-900">Standard CBT Rules:</h3>
                <ul class="list-disc pl-5 space-y-2">
                    <li>The clock will be set at the server. The countdown timer at the top right of the screen will display the remaining time available for you to complete the examination.</li>
                    <li>When the timer reaches zero, the examination will end by itself and your responses will automatically be submitted.</li>
                    <li>You can mark questions for review and return to them anytime before final submission.</li>
                    <li>Green color on the question palette indicates answered questions; purple indicates questions marked for review.</li>
                    <li>Do not close or refresh your browser window unnecessarily. Responses are saved on our secure servers incrementally.</li>
                </ul>
            </div>

            <!-- Start / Resume Button Form -->
            <form action="{{ route('student.tests.start', $test->id) }}" method="POST" class="pt-4">
                @csrf
                @if($existingAttempt)
                    <div class="bg-amber-50 border border-amber-200 p-4 rounded-2xl text-xs text-amber-800 mb-4 flex items-center justify-between">
                        <span>You have an ongoing attempt in progress for this test.</span>
                        <span class="font-bold">Resume Now</span>
                    </div>
                    <button type="submit" class="w-full py-4 rounded-full text-sm font-bold text-white bg-amber-600 hover:bg-amber-700 shadow-md transition">
                        Resume Test Engine →
                    </button>
                @else
                    <button type="submit" class="w-full py-4 rounded-full text-sm font-bold text-white bg-blue-700 hover:bg-blue-800 shadow-md shadow-blue-500/20 transition transform hover:-translate-y-0.5">
                        I am Ready to Begin Test →
                    </button>
                @endif
            </form>

        </div>

    </div>
</div>
@endsection
