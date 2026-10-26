@extends('layouts.student')

@section('title', 'Test Performance & Scorecard — ' . $attempt->mockTest->title)

@section('content')
<div class="py-10">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- Scorecard Header Card -->
        <div class="bg-white rounded-3xl p-6 sm:p-10 border border-gray-100 shadow-xl text-center space-y-6">
            <span class="px-3.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-blue-50 text-blue-700 border border-blue-100 inline-block">
                EXAMINATION SCORECARD
            </span>

            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900">
                {{ $attempt->mockTest->title }}
            </h1>

            <!-- Big Score Ring -->
            <div class="inline-flex flex-col items-center justify-center w-40 h-40 rounded-full bg-gradient-to-tr from-blue-50 to-indigo-50 border-4 border-blue-600/30 shadow-inner">
                <span class="text-3xl sm:text-4xl font-black text-blue-900">{{ $attempt->score }}</span>
                <span class="text-xs font-bold text-gray-500 uppercase mt-0.5">Out of {{ number_format($attempt->mockTest->total_marks, 0) }}</span>
            </div>

            <!-- Performance Metrics -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 max-w-2xl mx-auto pt-2">
                <div class="p-4 rounded-2xl bg-emerald-50/70 border border-emerald-100 text-center">
                    <span class="text-[10px] text-emerald-800 font-bold uppercase block">Correct</span>
                    <span class="text-xl font-extrabold text-emerald-700 mt-0.5 block">{{ $attempt->correct_count }}</span>
                </div>

                <div class="p-4 rounded-2xl bg-red-50/70 border border-red-100 text-center">
                    <span class="text-[10px] text-red-800 font-bold uppercase block">Incorrect</span>
                    <span class="text-xl font-extrabold text-red-600 mt-0.5 block">{{ $attempt->incorrect_count }}</span>
                </div>

                <div class="p-4 rounded-2xl bg-gray-50 border border-gray-100 text-center">
                    <span class="text-[10px] text-gray-500 font-bold uppercase block">Unanswered</span>
                    <span class="text-xl font-extrabold text-gray-700 mt-0.5 block">{{ $attempt->unanswered_count }}</span>
                </div>

                <div class="p-4 rounded-2xl bg-blue-50/70 border border-blue-100 text-center">
                    <span class="text-[10px] text-blue-800 font-bold uppercase block">Accuracy</span>
                    <span class="text-xl font-extrabold text-blue-700 mt-0.5 block">{{ $attempt->accuracy_percentage }}%</span>
                </div>
            </div>

            <!-- Time Taken -->
            <p class="text-xs text-gray-500">
                Time Taken: <strong class="text-gray-800">{{ gmdate('i', $attempt->time_taken_seconds) }}m {{ gmdate('s', $attempt->time_taken_seconds) }}s</strong> • Submitted: {{ $attempt->submitted_at ? $attempt->submitted_at->format('d M, Y h:i A') : 'Completed' }}
            </p>

            <div class="pt-4 flex flex-wrap justify-center gap-3">
                <a href="{{ route('student.dashboard') }}" class="px-6 py-2.5 rounded-full text-xs font-bold text-gray-700 bg-gray-100 hover:bg-gray-200 transition">
                    ← Dashboard
                </a>
                <a href="{{ route('student.tests.instructions', $attempt->mock_test_id) }}" class="px-6 py-2.5 rounded-full text-xs font-bold text-white bg-blue-700 hover:bg-blue-800 shadow-xs transition">
                    Retake Test
                </a>
            </div>
        </div>

        <!-- Question-by-Question Solution Review -->
        <div class="space-y-6">
            <h2 class="text-xl font-bold text-gray-900">Step-by-Step Question Review &amp; Explanations</h2>

            @foreach($attempt->answers as $index => $ans)
                @php
                    $q = $ans->question;
                    $isCorrect = $ans->is_correct;
                    $hasAnswered = !empty($ans->selected_option);
                @endphp
                <div class="bg-white rounded-3xl p-6 sm:p-8 border border-gray-100 shadow-xs space-y-4">
                    
                    <div class="flex items-center justify-between text-xs">
                        <div class="flex items-center space-x-2">
                            <span class="font-bold text-gray-400">Q{{ $index + 1 }}.</span>
                            <span class="font-semibold text-blue-700 bg-blue-50 px-2 py-0.5 rounded-md">{{ $q->subject }}</span>
                        </div>

                        <div>
                            @if(!$hasAnswered)
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-gray-100 text-gray-600">
                                    Not Attempted (0 Marks)
                                </span>
                            @elseif($isCorrect)
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                    ✓ Correct (+{{ $q->marks }} Marks)
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-red-100 text-red-800">
                                    ✗ Incorrect (-{{ $q->negative_marks }} Marks)
                                </span>
                            @endif
                        </div>
                    </div>

                    <p class="text-base font-bold text-gray-900 leading-relaxed">
                        {{ $q->question_text }}
                    </p>

                    <!-- Options list -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs sm:text-sm">
                        @foreach(['A' => $q->option_a, 'B' => $q->option_b, 'C' => $q->option_c, 'D' => $q->option_d] as $opt => $val)
                            @php
                                $isThisSelected = $ans->selected_option === $opt;
                                $isThisCorrect = $q->correct_option === $opt;
                            @endphp
                            <div class="p-3.5 rounded-xl border flex items-center space-x-3 
                                {{ $isThisCorrect ? 'border-emerald-500 bg-emerald-50 text-emerald-950 font-bold' : ($isThisSelected ? 'border-red-500 bg-red-50 text-red-950 font-medium' : 'border-gray-200 bg-gray-50/50 text-gray-700') }}">
                                <span class="w-6 h-6 rounded-full flex items-center justify-center font-bold text-xs flex-shrink-0 
                                    {{ $isThisCorrect ? 'bg-emerald-600 text-white' : ($isThisSelected ? 'bg-red-600 text-white' : 'bg-white text-gray-500 border border-gray-300') }}">
                                    {{ $opt }}
                                </span>
                                <span>{{ $val }}</span>
                                @if($isThisCorrect)
                                    <span class="ml-auto text-[10px] font-extrabold text-emerald-700 uppercase">Correct</span>
                                @elseif($isThisSelected)
                                    <span class="ml-auto text-[10px] font-extrabold text-red-600 uppercase">Your Choice</span>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <!-- Explanation -->
                    @if($q->explanation)
                        <div class="mt-4 pt-4 border-t border-gray-100 text-xs text-gray-600 bg-blue-50/30 p-4 rounded-2xl border border-blue-50">
                            <strong class="text-blue-900 block mb-1">Explanation / Solution:</strong>
                            <p class="leading-relaxed">{{ $q->explanation }}</p>
                        </div>
                    @endif

                </div>
            @endforeach
        </div>

    </div>
</div>
@endsection
