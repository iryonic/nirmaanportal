@extends('layouts.student')

@section('title', 'Practice Question Bank — Nirmaan')

@section('content')
<div class="py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-extrabold text-gray-900">Practice Question Bank</h1>
                <p class="text-xs text-gray-500 mt-1">Topic-wise interactive drills with instant explanation feedback.</p>
            </div>
            <span class="text-xs font-bold text-blue-700 bg-blue-50 px-3.5 py-1.5 rounded-full border border-blue-200 self-start sm:self-auto">
                {{ $totalAvailable }}+ Curated Questions Available
            </span>
        </div>

        <!-- Subject Chips Selector -->
        <div class="flex flex-wrap gap-2">
            @foreach($subjects as $sub)
                <a href="{{ route('student.practice', ['subject' => $sub, 'difficulty' => request('difficulty')]) }}" class="px-4 py-2 rounded-full text-xs font-bold transition {{ $selectedSubject === $sub ? 'bg-blue-700 text-white shadow-sm' : 'bg-white text-gray-700 border border-gray-200 hover:bg-gray-50' }}">
                    {{ $sub }}
                </a>
            @endforeach
        </div>

        <!-- Difficulty Filter -->
        <div class="flex items-center space-x-2 text-xs">
            <span class="text-gray-400 font-medium">Difficulty:</span>
            <a href="{{ route('student.practice', ['subject' => $selectedSubject]) }}" class="px-3 py-1 rounded-full {{ !request('difficulty') ? 'bg-gray-800 text-white font-bold' : 'text-gray-600 hover:bg-gray-200' }}">
                All
            </a>
            @foreach($difficulties as $dKey => $dVal)
                <a href="{{ route('student.practice', ['subject' => $selectedSubject, 'difficulty' => $dKey]) }}" class="px-3 py-1 rounded-full {{ request('difficulty') === $dKey ? 'bg-blue-700 text-white font-bold' : 'text-gray-600 hover:bg-gray-200' }}">
                    {{ $dVal }}
                </a>
            @endforeach
        </div>

        <!-- Questions List -->
        <div class="space-y-6">
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
                    class="bg-white rounded-3xl p-6 sm:p-8 border border-gray-100 shadow-xs">
                    
                    <!-- Meta info -->
                    <div class="flex items-center justify-between mb-4 text-xs">
                        <div class="flex items-center space-x-2">
                            <span class="font-bold text-gray-400">Q{{ $questions->firstItem() + $qIndex }}.</span>
                            <span class="font-semibold text-blue-700 bg-blue-50 px-2 py-0.5 rounded-md">{{ $q->subject }}</span>
                            @if($q->topic)
                                <span class="text-gray-400">• {{ $q->topic }}</span>
                            @endif
                        </div>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $q->difficulty === 'easy' ? 'bg-emerald-50 text-emerald-700' : ($q->difficulty === 'medium' ? 'bg-amber-50 text-amber-700' : 'bg-red-50 text-red-700') }}">
                            {{ $q->difficulty }}
                        </span>
                    </div>

                    <!-- Question Text -->
                    <p class="text-base sm:text-lg font-bold text-gray-900 mb-6 leading-relaxed">
                        {{ $q->question_text }}
                    </p>

                    <!-- Options -->
                    <div class="space-y-3 mb-6">
                        @foreach(['A' => $q->option_a, 'B' => $q->option_b, 'C' => $q->option_c, 'D' => $q->option_d] as $optKey => $optVal)
                            <button type="button"
                                    @click="if(!submitted) selectedOption = '{{ $optKey }}'"
                                    :class="{
                                        'border-blue-600 bg-blue-50 text-blue-900 font-bold': selectedOption === '{{ $optKey }}' && !submitted,
                                        'border-emerald-500 bg-emerald-50 text-emerald-900 font-bold': submitted && correctOption === '{{ $optKey }}',
                                        'border-red-500 bg-red-50 text-red-900': submitted && selectedOption === '{{ $optKey }}' && !isCorrect,
                                        'border-gray-200 bg-gray-50/50 hover:bg-gray-100 text-gray-800': selectedOption !== '{{ $optKey }}' && (!submitted || correctOption !== '{{ $optKey }}')
                                    }"
                                    class="w-full p-4 rounded-2xl border text-left flex items-center space-x-3 text-xs sm:text-sm transition">
                                <span class="w-7 h-7 rounded-full bg-white border border-gray-300 flex items-center justify-center font-bold text-xs flex-shrink-0">
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
                                    class="px-6 py-2.5 rounded-full text-xs font-bold text-white bg-blue-700 hover:bg-blue-800 disabled:opacity-40 transition shadow-xs">
                                <span x-show="!loading">Check Answer</span>
                                <span x-show="loading">Evaluating...</span>
                            </button>
                        </template>

                        <template x-if="submitted">
                            <span :class="isCorrect ? 'text-emerald-700 bg-emerald-50' : 'text-red-700 bg-red-50'" class="px-4 py-1.5 rounded-full text-xs font-extrabold">
                                <span x-text="isCorrect ? '✓ Correct Answer!' : '✗ Incorrect. Correct is: Option ' + correctOption"></span>
                            </span>
                        </template>
                    </div>

                    <!-- Instant Explanation Panel -->
                    <div x-show="submitted" x-collapse class="mt-5 pt-4 border-t border-gray-100" style="display:none;">
                        <span class="text-xs font-bold uppercase tracking-wider text-gray-400 block mb-1">Detailed Solution / Explanation</span>
                        <p class="text-xs sm:text-sm text-gray-700 leading-relaxed bg-blue-50/50 p-4 rounded-2xl border border-blue-100" x-text="explanation || 'No additional explanation notes provided.'"></p>
                    </div>

                </div>
            @empty
                <div class="bg-white rounded-3xl p-12 text-center border border-gray-100">
                    <p class="text-sm text-gray-500">No questions found matching your filter criteria.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $questions->links() }}
        </div>

    </div>
</div>
@endsection
