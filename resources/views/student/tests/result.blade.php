@extends('layouts.student')

@section('title', 'Test Performance & Scorecard — ' . $attempt->mockTest->title)

@section('content')
<div class="py-8 sm:py-10">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        
        <!-- Scorecard Header Card (Linear / Blue-950 style) -->
        <div class="bg-white rounded-xl p-6 sm:p-8 border border-slate-200 shadow-2xs text-center space-y-5">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded text-[10px] font-semibold uppercase tracking-wider bg-blue-50 text-blue-900 border border-blue-200">
                EXAMINATION SCORECARD
            </span>

            <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">
                {{ $attempt->mockTest->title }}
            </h1>

            <!-- Score Display Box (Blue-950 solid box) -->
            <div class="inline-flex flex-col items-center justify-center p-5 rounded-xl bg-blue-950 text-white min-w-[180px] shadow-2xs border border-blue-900">
                <span class="text-3xl sm:text-4xl font-bold font-mono tracking-tight">{{ $attempt->score }}</span>
                <span class="text-[11px] font-medium text-blue-200 uppercase tracking-wider mt-1">Out of {{ number_format($attempt->mockTest->total_marks, 0) }} Marks</span>
            </div>

            <!-- Performance Metrics Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 max-w-2xl mx-auto pt-1">
                <div class="p-3.5 rounded-lg bg-emerald-50 border border-emerald-200 text-center">
                    <span class="text-[10px] text-emerald-800 font-semibold uppercase tracking-wider block">Correct</span>
                    <span class="text-xl font-bold text-emerald-700 mt-0.5 block font-mono">{{ $attempt->correct_count }}</span>
                </div>

                <div class="p-3.5 rounded-lg bg-rose-50 border border-rose-200 text-center">
                    <span class="text-[10px] text-rose-800 font-semibold uppercase tracking-wider block">Incorrect</span>
                    <span class="text-xl font-bold text-rose-600 mt-0.5 block font-mono">{{ $attempt->incorrect_count }}</span>
                </div>

                <div class="p-3.5 rounded-lg bg-slate-50 border border-slate-200 text-center">
                    <span class="text-[10px] text-slate-600 font-semibold uppercase tracking-wider block">Unanswered</span>
                    <span class="text-xl font-bold text-slate-700 mt-0.5 block font-mono">{{ $attempt->unanswered_count }}</span>
                </div>

                <div class="p-3.5 rounded-lg bg-blue-50 border border-blue-200 text-center">
                    <span class="text-[10px] text-blue-900 font-semibold uppercase tracking-wider block">Accuracy</span>
                    <span class="text-xl font-bold text-blue-800 mt-0.5 block font-mono">{{ $attempt->accuracy_percentage }}%</span>
                </div>
            </div>

            <!-- Time Taken & Submission Meta -->
            <p class="text-xs text-slate-500 font-mono">
                Duration Utilized: <strong class="text-slate-800 font-bold">{{ gmdate('i', $attempt->time_taken_seconds) }}m {{ gmdate('s', $attempt->time_taken_seconds) }}s</strong> • Submitted: {{ $attempt->submitted_at ? $attempt->submitted_at->format('d M, Y h:i A') : 'Completed' }}
            </p>

            <div class="pt-2 flex flex-wrap justify-center gap-2.5">
                <a href="{{ route('student.tests.index') }}" class="inline-flex items-center px-4 py-2 rounded-lg text-xs font-medium text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 transition-colors shadow-2xs">
                    ← All Mock Tests
                </a>
                <a href="{{ route('student.tests.instructions', $attempt->mock_test_id) }}" class="inline-flex items-center px-4 py-2 rounded-lg text-xs font-medium text-white bg-blue-950 hover:bg-blue-900 transition-colors shadow-2xs">
                    Retake Examination
                </a>
            </div>
        </div>

        <!-- Question-by-Question Solution Review -->
        <div class="space-y-4">
            <div class="flex items-center justify-between pb-2 border-b border-slate-200">
                <h2 class="text-xs font-semibold uppercase tracking-wider text-slate-500">Step-by-Step Question Review &amp; Explanations</h2>
                <span class="text-xs text-slate-400 font-mono">{{ $attempt->answers->count() }} Questions Evaluated</span>
            </div>

            @foreach($attempt->answers as $index => $ans)
                @php
                    $q = $ans->question;
                    $isCorrect = $ans->is_correct;
                    $hasAnswered = !empty($ans->selected_option);
                @endphp
                <div class="bg-white rounded-xl p-5 sm:p-6 border border-slate-200 shadow-2xs space-y-3.5">
                    
                    <div class="flex items-center justify-between text-xs">
                        <div class="flex items-center space-x-2">
                            <span class="font-bold text-slate-400">Q{{ $index + 1 }}.</span>
                            <span class="font-semibold text-blue-900 bg-blue-50 px-2 py-0.5 rounded border border-blue-200">{{ $q->subject }}</span>
                            @if($q->topic)
                                <span class="text-slate-400">•</span>
                                <span class="text-slate-500">{{ $q->topic }}</span>
                            @endif
                        </div>

                        <div>
                            @if(!$hasAnswered)
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                    Not Attempted (0 Marks)
                                </span>
                            @elseif($isCorrect)
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    ✓ Correct (+{{ $q->marks }} Marks)
                                </span>
                            @else
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                                    ✗ Incorrect (-{{ $q->negative_marks }} Marks)
                                </span>
                            @endif
                        </div>
                    </div>

                    <p class="text-sm sm:text-base font-semibold text-slate-900 leading-relaxed">
                        {{ $q->question_text }}
                    </p>

                    <!-- Options Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
                        @foreach(['A', 'B', 'C', 'D'] as $opt)
                            @php
                                $optKey = 'option_' . strtolower($opt);
                                $isThisCorrect = ($q->correct_option === $opt);
                                $isUserChosen = ($ans->selected_option === $opt);
                                
                                $cardClass = 'border-slate-200 bg-white text-slate-700';
                                if ($isThisCorrect) {
                                    $cardClass = 'border-emerald-600 bg-emerald-50 text-emerald-950 font-semibold';
                                } elseif ($isUserChosen && !$isThisCorrect) {
                                    $cardClass = 'border-rose-500 bg-rose-50 text-rose-950 font-semibold';
                                }
                            @endphp
                            <div class="p-2.5 rounded-lg border flex items-center space-x-2.5 shadow-2xs {{ $cardClass }}">
                                <span class="w-5 h-5 rounded flex items-center justify-center font-bold text-xs flex-shrink-0 {{ $isThisCorrect ? 'bg-emerald-600 text-white' : ($isUserChosen ? 'bg-rose-600 text-white' : 'bg-slate-100 text-slate-700') }}">
                                    {{ $opt }}
                                </span>
                                <span class="flex-grow">{{ $q->$optKey }}</span>
                                @if($isThisCorrect)
                                    <span class="text-[10px] font-semibold text-emerald-700 uppercase">Correct</span>
                                @elseif($isUserChosen)
                                    <span class="text-[10px] font-semibold text-rose-600 uppercase">Your Answer</span>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <!-- Detailed Explanation -->
                    @if($q->explanation)
                        <div class="p-3.5 rounded-lg bg-slate-50 border border-slate-200 text-xs text-slate-700 space-y-1">
                            <span class="font-semibold text-slate-900 block text-[11px] uppercase tracking-wider">Expert Solution &amp; Explanation:</span>
                            <p class="leading-relaxed">{{ $q->explanation }}</p>
                        </div>
                    @endif

                </div>
            @endforeach
        </div>

    </div>
</div>
@endsection
