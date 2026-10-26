@extends('layouts.student')

@section('title', 'Exam Instructions — ' . $test->title)

@section('content')
<div class="py-10">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="bg-white rounded-2xl p-6 sm:p-10 border border-gray-200 shadow-2xs space-y-8">
            
            <!-- Header -->
            <div class="border-b border-gray-200/80 pb-6">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-medium text-blue-700 bg-blue-50 px-2.5 py-0.5 rounded-md border border-blue-200/60">
                        {{ $test->examCategory ? $test->examCategory->name : 'Defence Exam' }}
                    </span>
                    <span class="text-xs font-medium px-2.5 py-0.5 rounded-md {{ $test->is_free ? 'bg-emerald-50 text-emerald-800 border border-emerald-200/70' : 'bg-blue-50 text-blue-800 border border-blue-200/70' }}">
                        {{ $test->is_free ? 'Free Test' : 'Premium Series' }}
                    </span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 leading-tight tracking-tight">
                    {{ $test->title }}
                </h1>
                <p class="text-xs text-gray-500 mt-1.5 font-normal">Please review all rules and marking instructions thoroughly prior to initiating your examination session.</p>
            </div>

            <!-- Key Parameters Grid (Sleek Hierarchy) -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                <div class="p-3.5 rounded-xl bg-gray-50/80 border border-gray-200/70 text-center">
                    <span class="text-[10px] text-gray-400 block font-normal">Total Questions</span>
                    <span class="text-lg font-bold text-gray-900 mt-0.5 block">{{ $totalQuestions }}</span>
                </div>
                <div class="p-3.5 rounded-xl bg-gray-50/80 border border-gray-200/70 text-center">
                    <span class="text-[10px] text-gray-400 block font-normal">Duration</span>
                    <span class="text-lg font-bold text-gray-900 mt-0.5 block">{{ $test->duration_minutes }} Mins</span>
                </div>
                <div class="p-3.5 rounded-xl bg-gray-50/80 border border-gray-200/70 text-center">
                    <span class="text-[10px] text-gray-400 block font-normal">Total Marks</span>
                    <span class="text-lg font-bold text-gray-900 mt-0.5 block">{{ number_format($test->total_marks, 0) }}</span>
                </div>
                <div class="p-3.5 rounded-xl bg-gray-50/80 border border-gray-200/70 text-center">
                    <span class="text-[10px] text-gray-400 block font-normal">Negative Mark</span>
                    <span class="text-lg font-bold text-rose-600 mt-0.5 block">-{{ $test->negative_marking_ratio }}</span>
                </div>
            </div>

            <!-- Rules & Guidelines -->
            <div class="space-y-3 text-xs sm:text-sm text-gray-700 leading-relaxed bg-gray-50 p-5 rounded-xl border border-gray-200">
                <h3 class="font-bold text-gray-900 text-xs uppercase tracking-wider">Examination Rules &amp; CBT Navigation:</h3>
                <ul class="list-disc pl-5 space-y-2 text-xs">
                    <li>The clock is synchronized with the evaluation server. The timer at the top right displays remaining time in minutes and seconds.</li>
                    <li>When the timer expires, your test will auto-submit automatically and compute your final score.</li>
                    <li>You can mark questions for review and navigate between questions via the Question Palette at any time.</li>
                    <li>Responses are auto-saved incrementally upon selecting each option.</li>
                    <li>Ensure you do not refresh or close your browser tab during the live examination session.</li>
                </ul>
            </div>

            <!-- Start / Resume / Unlock Button Form -->
            @php
                $user = Auth::user();
                $hasAccess = $test->is_free 
                    || \App\Models\Order::where('user_id', $user->id)->where('mock_test_id', $test->id)->where('status', 'paid')->exists()
                    || ($test->exam_category_id && \App\Models\Enrollment::where('user_id', $user->id)->where('status', 'active')->whereHas('course', fn($q) => $q->where('exam_category_id', $test->exam_category_id))->exists());
            @endphp

            @if($hasAccess)
                <form action="{{ route('student.tests.start', $test->id) }}" method="POST" class="pt-2">
                    @csrf
                    @if($existingAttempt)
                        <div class="bg-amber-50 border border-amber-200 p-4 rounded-xl text-xs text-amber-800 mb-4 flex items-center justify-between">
                            <span>You have an ongoing attempt in progress for this test.</span>
                            <span class="font-bold uppercase tracking-wider text-[10px]">Session Resumable</span>
                        </div>
                        <button type="submit" class="w-full py-3.5 rounded-xl text-xs sm:text-sm font-bold text-white bg-amber-600 hover:bg-amber-700 transition">
                            Resume Test Session →
                        </button>
                    @else
                        <button type="submit" class="w-full py-3.5 rounded-xl text-xs sm:text-sm font-bold text-white bg-blue-700 hover:bg-blue-800 transition">
                            I Have Read All Instructions — Begin Test →
                        </button>
                    @endif
                </form>
            @else
                <div class="pt-2 space-y-3">
                    <div class="bg-blue-50 border border-blue-200 p-4 rounded-xl text-xs text-blue-900">
                        This full-length mock test is a premium examination series. Unlock access to attempt this test and receive detailed performance analysis.
                    </div>
                    <a href="{{ route('student.checkout.test', $test->id) }}" class="w-full py-3.5 rounded-xl text-xs sm:text-sm font-bold text-white bg-blue-700 hover:bg-blue-800 transition flex items-center justify-center space-x-2">
                        <span>Unlock Premium Test Series — ₹ {{ number_format($test->price > 0 ? $test->price : 499, 2) }}</span>
                    </a>
                </div>
            @endif

        </div>

    </div>
</div>
@endsection
