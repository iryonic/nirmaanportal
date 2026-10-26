@extends('layouts.student')

@section('title', 'Test Performance & Scorecard — ' . $attempt->mockTest->title)

@section('content')
<div class="py-10">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- Scorecard Header Card -->
        <div class="bg-white rounded-2xl p-6 sm:p-10 border border-gray-200 shadow-2xs text-center space-y-6">
            <span class="px-3 py-1 rounded-md text-xs font-bold uppercase tracking-wider bg-blue-50 text-blue-700 border border-blue-100 inline-block">
                EXAMINATION SCORECARD
            </span>

            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900">
                {{ $attempt->mockTest->title }}
            </h1>

            <!-- Score Display Box (Strictly Solid, No Gradients) -->
            <div class="inline-flex flex-col items-center justify-center p-6 rounded-2xl bg-slate-900 text-white min-w-[200px]">
                <span class="text-4xl sm:text-5xl font-black font-mono tracking-tight">{{ $attempt->score }}</span>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-1">Out of {{ number_format($attempt->mockTest->total_marks, 0) }} Marks</span>
            </div>

            <!-- Performance Metrics Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 max-w-2xl mx-auto pt-2">
                <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-center">
                    <span class="text-[10px] text-emerald-800 font-bold uppercase tracking-wider block">Correct</span>
                    <span class="text-2xl font-black text-emerald-700 mt-1 block">{{ $attempt->correct_count }}</span>
                </div>

                <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-center">
                    <span class="text-[10px] text-red-800 font-bold uppercase tracking-wider block">Incorrect</span>
                    <span class="text-2xl font-black text-red-600 mt-1 block">{{ $attempt->incorrect_count }}</span>
                </div>

                <div class="p-4 rounded-xl bg-gray-50 border border-gray-200 text-center">
                    <span class="text-[10px] text-gray-600 font-bold uppercase tracking-wider block">Unanswered</span>
                    <span class="text-2xl font-black text-gray-700 mt-1 block">{{ $attempt->unanswered_count }}</span>
                </div>

                <div class="p-4 rounded-xl bg-blue-50 border border-blue-200 text-center">
                    <span class="text-[10px] text-blue-800 font-bold uppercase tracking-wider block">Accuracy</span>
                    <span class="text-2xl font-black text-blue-700 mt-1 block">{{ $attempt->accuracy_percentage }}%</span>
                </div>
            </div>

            <!-- Time Taken & Submission Meta -->
            <p class="text-xs text-gray-500">
                Duration Utilized: <strong class="text-gray-800">{{ gmdate('i', $attempt->time_taken_seconds) }}m {{ gmdate('s', $attempt->time_taken_seconds) }}s</strong> • Submitted: {{ $attempt->submitted_at ? $attempt->submitted_at->format('d M, Y h:i A') : 'Completed' }}
            </p>

            <div class="pt-4 flex flex-wrap justify-center gap-3">
                <a href="{{ route('student.tests.index') }}" class="px-5 py-2.5 rounded-xl text-xs font-bold text-gray-700 bg-gray-100 hover:bg-gray-200 transition">
                    ← All Mock Tests
                </a>
                <a href="{{ route('student.tests.instructions', $attempt->mock_test_id) }}" class="px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-blue-700 hover:bg-blue-800 transition">
                    Retake Examination
                </a>
            </div>
        </div>

        <!-- Question-by-Question Solution Review -->
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-bold text-gray-900">Step-by-Step Question Review &amp; Explanations</h2>
                <span class="text-xs text-gray-500 font-medium">{{ $attempt->answers->count() }} Questions Evaluated</span>
            </div>

            @foreach($attempt->answers as $index => $ans)
                @php
                    $q = $ans->question;
                    $isCorrect = $ans->is_correct;
                    $hasAnswered = !empty($ans->selected_option);
                @endphp
                <div class="bg-white rounded-2xl p-6 sm:p-7 border border-gray-200 shadow-2xs space-y-4">
                    
                    <div class="flex items-center justify-between text-xs">
                        <div class="flex items-center space-x-2">
                            <span class="font-bold text-gray-400">Q{{ $index + 1 }}.</span>
                            <span class="font-semibold text-blue-700 bg-blue-50 px-2 py-0.5 rounded-md border border-blue-100">{{ $q->subject }}</span>
                            @if($q->topic)
                                <span class="text-gray-400">•</span>
                                <span class="text-gray-500">{{ $q->topic }}</span>
                            @endif
                        </div>

                        <div>
                            @if(!$hasAnswered)
                                <span class="px-2.5 py-1 rounded-md text-[10px] font-bold bg-gray-100 text-gray-700 border border-gray-200">
                                    Not Attempted (0 Marks)
                                </span>
                            @elseif($isCorrect)
                                <span class="px-2.5 py-1 rounded-md text-[10px] font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                    ✓ Correct (+{{ $q->marks }} Marks)
                                </span>
                            @else
                                <span class="px-2.5 py-1 rounded-md text-[10px] font-bold bg-red-50 text-red-800 border border-red-200">
                                    ✗ Incorrect (-{{ $q->negative_marks }} Marks)
                                </span>
                            @endif
                        </div>
                    </div>

                    <p class="text-sm sm:text-base font-bold text-gray-900 leading-relaxed">
                        {{ $q->question_text }}
                    </p>

                    <!-- Options Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
                        @foreach(['A', 'B', 'C', 'D'] as $opt)
                            @php
                                $optKey = 'option_' . strtolower($opt);
                                $isThisCorrect = ($q->correct_option === $opt);
                                $isUserChosen = ($ans->selected_option === $opt);
                                
                                $cardClass = 'border-gray-200 bg-white text-gray-700';
                                if ($isThisCorrect) {
                                    $cardClass = 'border-emerald-500 bg-emerald-50 text-emerald-950 font-bold';
                                } elseif ($isUserChosen && !$isThisCorrect) {
                                    $cardClass = 'border-red-500 bg-red-50 text-red-950 font-bold';
                                }
                            @endphp
                            <div class="p-3 rounded-xl border flex items-center space-x-2.5 {{ $cardClass }}">
                                <span class="w-6 h-6 rounded flex items-center justify-center font-bold text-xs flex-shrink-0 {{ $isThisCorrect ? 'bg-emerald-600 text-white' : ($isUserChosen ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-700') }}">
                                    {{ $opt }}
                                </span>
                                <span class="flex-grow">{{ $q->$optKey }}</span>
                                @if($isThisCorrect)
                                    <span class="text-[10px] font-bold text-emerald-700 uppercase">Correct</span>
                                @elseif($isUserChosen)
                                    <span class="text-[10px] font-bold text-red-600 uppercase">Your Answer</span>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <!-- Detailed Explanation -->
                    @if($q->explanation)
                        <div class="p-4 rounded-xl bg-blue-50/60 border border-blue-200 text-xs text-gray-800 space-y-1">
                            <span class="font-bold text-blue-900 block text-[11px] uppercase tracking-wider">Expert Solution &amp; Explanation:</span>
                            <p class="leading-relaxed">{{ $q->explanation }}</p>
                        </div>
                    @endif

                </div>
            @endforeach
        </div>

    </div>
</div>
@endsection
