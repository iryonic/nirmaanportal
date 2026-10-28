@extends('layouts.student')

@section('title', 'Exam Instructions — ' . $test->title)

@section('content')
<div class="py-8 sm:py-10">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="bg-white rounded-xl p-6 sm:p-8 border border-slate-200 shadow-2xs space-y-6">
            
            <!-- Header -->
            <div class="border-b border-slate-200 pb-5">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[11px] font-semibold text-blue-900 bg-blue-50 px-2.5 py-0.5 rounded border border-blue-200">
                        {{ $test->examCategory ? $test->examCategory->name : 'Defence Exam' }}
                    </span>
                    <span class="text-[11px] font-semibold px-2.5 py-0.5 rounded {{ $test->is_free ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-blue-50 text-blue-900 border border-blue-200' }}">
                        {{ $test->is_free ? 'Free Test' : 'Premium Series' }}
                    </span>
                </div>
                <h1 class="text-xl sm:text-2xl font-bold text-slate-900 leading-tight tracking-tight">
                    {{ $test->title }}
                </h1>
                <p class="text-xs text-slate-500 mt-1">Please review all rules and marking instructions thoroughly prior to initiating your examination session.</p>
            </div>

            <!-- Key Parameters Grid (Linear Hierarchy) -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                <div class="p-3.5 rounded-lg bg-slate-50 border border-slate-200 text-center">
                    <span class="text-[10px] text-slate-400 block font-medium uppercase tracking-wider">Total Questions</span>
                    <span class="text-lg font-bold text-slate-900 mt-0.5 block font-mono">{{ $totalQuestions }}</span>
                </div>
                <div class="p-3.5 rounded-lg bg-slate-50 border border-slate-200 text-center">
                    <span class="text-[10px] text-slate-400 block font-medium uppercase tracking-wider">Duration</span>
                    <span class="text-lg font-bold text-slate-900 mt-0.5 block font-mono">{{ $test->duration_minutes }}m</span>
                </div>
                <div class="p-3.5 rounded-lg bg-slate-50 border border-slate-200 text-center">
                    <span class="text-[10px] text-slate-400 block font-medium uppercase tracking-wider">Total Marks</span>
                    <span class="text-lg font-bold text-slate-900 mt-0.5 block font-mono">{{ number_format($test->total_marks, 0) }}</span>
                </div>
                <div class="p-3.5 rounded-lg bg-slate-50 border border-slate-200 text-center">
                    <span class="text-[10px] text-slate-400 block font-medium uppercase tracking-wider">Negative Mark</span>
                    <span class="text-lg font-bold text-rose-600 mt-0.5 block font-mono">-{{ $test->negative_marking_ratio }}</span>
                </div>
            </div>

            <!-- Rules & Guidelines -->
            <div class="space-y-2.5 text-xs text-slate-700 leading-relaxed bg-slate-50 p-4 rounded-lg border border-slate-200">
                <h3 class="font-semibold text-slate-900 text-xs uppercase tracking-wider">Examination Rules &amp; CBT Navigation:</h3>
                <ul class="list-disc pl-4 space-y-1.5 text-xs text-slate-600">
                    <li>The clock is synchronized with the evaluation server. The timer displays remaining time in minutes and seconds.</li>
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
                        <div class="bg-amber-50 border border-amber-200 p-3.5 rounded-lg text-xs text-amber-800 mb-3 flex items-center justify-between">
                            <span>You have an ongoing attempt in progress for this test.</span>
                            <span class="font-semibold uppercase tracking-wider text-[10px]">Session Resumable</span>
                        </div>
                        <button type="submit" class="w-full py-2.5 rounded-lg text-xs sm:text-sm font-medium text-white bg-amber-600 hover:bg-amber-700 transition-colors shadow-2xs">
                            Resume Test Session →
                        </button>
                    @else
                        <button type="submit" class="w-full py-2.5 rounded-lg text-xs sm:text-sm font-medium text-white bg-blue-950 hover:bg-blue-900 transition-colors shadow-2xs">
                            I Have Read All Instructions — Begin Test →
                        </button>
                    @endif
                </form>
            @else
                <div class="pt-2 space-y-3">
                    <div class="bg-blue-50 border border-blue-200 p-3.5 rounded-lg text-xs text-blue-900">
                        This full-length mock test is a premium examination series. Unlock access to attempt this test and receive detailed performance analysis.
                    </div>
                    <a href="{{ route('student.checkout.test', $test->id) }}" class="w-full py-2.5 rounded-lg text-xs sm:text-sm font-medium text-white bg-blue-950 hover:bg-blue-900 transition-colors flex items-center justify-center space-x-2 shadow-2xs">
                        <span>Unlock Premium Test Series — ₹{{ number_format($test->price > 0 ? $test->price : 499, 2) }}</span>
                    </a>
                </div>
            @endif

        </div>

    </div>
</div>
@endsection
