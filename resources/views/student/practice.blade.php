@extends('layouts.student')

@section('title', 'Practice Question Bank — Nirmaan')

@section('content')
<div class="py-8 sm:py-10">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-slate-200">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900">Practice Question Bank</h1>
                <p class="text-xs text-slate-500 mt-0.5">Topic-wise interactive drills with instant evaluation feedback and step-by-step solutions.</p>
            </div>
            <span class="inline-flex items-center px-3 py-1 rounded-md text-xs font-semibold text-blue-900 bg-blue-50 border border-blue-200 self-start sm:self-auto">
                {{ $totalAvailable }}+ Curated Questions Available
            </span>
        </div>

        <!-- Subject Chips Selector (Linear style) -->
        <div class="flex flex-wrap gap-2">
            @foreach($subjects as $sub)
                <a href="{{ route('student.practice', ['subject' => $sub, 'difficulty' => request('difficulty')]) }}" 
                   class="px-3.5 py-1.5 rounded-lg text-xs font-medium transition-colors shadow-2xs {{ $selectedSubject === $sub ? 'bg-blue-950 text-white' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50' }}">
                    {{ $sub }}
                </a>
            @endforeach
        </div>

        <!-- Difficulty Filter -->
        <div class="flex items-center space-x-2 text-xs">
            <span class="text-slate-400 font-medium">Difficulty:</span>
            <a href="{{ route('student.practice', ['subject' => $selectedSubject]) }}" 
               class="px-2.5 py-1 rounded-md transition-colors {{ !request('difficulty') ? 'bg-slate-900 text-white font-medium shadow-2xs' : 'text-slate-600 hover:bg-slate-200' }}">
                All
            </a>
            @foreach($difficulties as $dKey => $dVal)
                <a href="{{ route('student.practice', ['subject' => $selectedSubject, 'difficulty' => $dKey]) }}" 
                   class="px-2.5 py-1 rounded-md transition-colors {{ request('difficulty') === $dKey ? 'bg-blue-950 text-white font-medium shadow-2xs' : 'text-slate-600 hover:bg-slate-200' }}">
                    {{ $dVal }}
                </a>
            @endforeach
        </div>

        <!-- Questions List -->
        <div class="space-y-5">
            @forelse($questions as $qIndex => $q)
                <div x-data="{ 
                        selectedOption: null, 
                        submitted: false, 
                        isCorrect: false, 
                        correctOption: '', 
                        explanation: '',
                        loading: false,
                        checkAnswer() {
                            if (!this.selectedOption) return;
                            this.loading = true;
                            fetch('{{ route('student.practice.check') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    question_id: {{ $q->id }},
                                    selected_option: this.selectedOption
                                })
                            })
                            .then(r => r.json())
                            .then(data => {
                                this.submitted = true;
                                this.isCorrect = data.is_correct;
                                this.correctOption = data.correct_option;
                                this.explanation = data.explanation;
                                this.loading = false;
                            });
                        }
                    }" 
                    class="bg-white rounded-xl p-5 sm:p-6 border border-slate-200 shadow-2xs space-y-4">
                    
                    <!-- Meta info -->
                    <div class="flex items-center justify-between text-xs">
                        <div class="flex items-center space-x-2">
                            <span class="font-bold text-slate-400">Q{{ $questions->firstItem() + $qIndex }}.</span>
                            <span class="font-semibold text-blue-900 bg-blue-50 px-2 py-0.5 rounded border border-blue-200">{{ $q->subject }}</span>
                            @if($q->topic)
                                <span class="text-slate-400">• {{ $q->topic }}</span>
                            @endif
                        </div>
                        <span class="px-2 py-0.5 rounded text-[10px] font-semibold uppercase {{ $q->difficulty === 'easy' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($q->difficulty === 'medium' ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-rose-50 text-rose-700 border border-rose-200') }}">
                            {{ $q->difficulty }}
                        </span>
                    </div>

                    <!-- Question Text -->
                    <p class="text-sm sm:text-base font-semibold text-slate-900 leading-relaxed">
                        {{ $q->question_text }}
                    </p>

                    <!-- Options -->
                    <div class="space-y-2.5">
                        @foreach(['A' => $q->option_a, 'B' => $q->option_b, 'C' => $q->option_c, 'D' => $q->option_d] as $optKey => $optVal)
                            <button type="button"
                                    @click="if(!submitted) selectedOption = '{{ $optKey }}'"
                                    :class="{
                                        'border-blue-950 bg-blue-50/60 text-blue-950 font-semibold shadow-2xs': selectedOption === '{{ $optKey }}' && !submitted,
                                        'border-emerald-600 bg-emerald-50 text-emerald-950 font-semibold': submitted && correctOption === '{{ $optKey }}',
                                        'border-rose-500 bg-rose-50 text-rose-950': submitted && selectedOption === '{{ $optKey }}' && !isCorrect,
                                        'border-slate-200 bg-white hover:bg-slate-50 text-slate-800': selectedOption !== '{{ $optKey }}' && (!submitted || correctOption !== '{{ $optKey }}')
                                    }"
                                    class="w-full p-3 rounded-lg border text-left flex items-center space-x-3 text-xs sm:text-sm transition-colors shadow-2xs">
                                <span class="w-6 h-6 rounded-md bg-slate-100 border border-slate-200 flex items-center justify-center font-bold text-xs flex-shrink-0 text-slate-700">
                                    {{ $optKey }}
                                </span>
                                <span>{{ $optVal }}</span>
                            </button>
                        @endforeach
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-between pt-2">
                        <template x-if="!submitted">
                            <button type="button" 
                                    @click="checkAnswer()"
                                    :disabled="!selectedOption || loading"
                                    class="px-4 py-2 rounded-lg text-xs font-medium text-white bg-blue-950 hover:bg-blue-900 disabled:opacity-40 transition-colors shadow-2xs">
                                <span x-show="!loading">Check Answer</span>
                                <span x-show="loading">Evaluating...</span>
                            </button>
                        </template>

                        <template x-if="submitted">
                            <span :class="isCorrect ? 'text-emerald-700 bg-emerald-50 border border-emerald-200' : 'text-rose-700 bg-rose-50 border border-rose-200'" class="px-3 py-1 rounded-md text-xs font-semibold">
                                <span x-text="isCorrect ? '✓ Correct Answer!' : '✗ Incorrect. Correct is: Option ' + correctOption"></span>
                            </span>
                        </template>
                    </div>

                    <!-- Instant Explanation Panel -->
                    <div x-show="submitted" x-collapse class="mt-4 pt-3 border-t border-slate-100" style="display:none;">
                        <span class="text-[11px] font-semibold uppercase tracking-wider text-slate-400 block mb-1">Detailed Solution / Explanation</span>
                        <p class="text-xs sm:text-sm text-slate-700 leading-relaxed bg-slate-50 p-3.5 rounded-lg border border-slate-200" x-text="explanation || 'No additional explanation notes provided.'"></p>
                    </div>

                </div>
            @empty
                <div class="bg-white rounded-xl p-12 text-center border border-slate-200 shadow-2xs">
                    <p class="text-sm text-slate-500">No questions found matching your filter criteria.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $questions->links() }}
        </div>

    </div>
</div>
@endsection
