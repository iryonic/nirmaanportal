<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $test->title }} — CBT Engine | Nirmaan</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#F8FAFC] text-gray-800 h-full flex flex-col font-sans select-none"
      x-data="testEngine({
          attemptId: {{ $attempt->id }},
          totalQuestions: {{ $test->questions->count() }},
          initialRemainingSeconds: {{ $remainingSeconds }},
          questions: {{ json_encode($test->questions->map(fn($q) => [
              'id' => $q->id,
              'subject' => $q->subject,
              'topic' => $q->topic,
              'text' => $q->question_text,
              'option_a' => $q->option_a,
              'option_b' => $q->option_b,
              'option_c' => $q->option_c,
              'option_d' => $q->option_d,
              'marks' => $q->marks,
          ])) }},
          initialAnswers: {{ json_encode($answers) }},
          initialReviews: {{ json_encode($reviewFlags) }},
          saveUrl: '{{ route('student.tests.save', $attempt->id) }}',
          submitUrl: '{{ route('student.tests.submit', $attempt->id) }}',
          csrfToken: '{{ csrf_token() }}'
      })">

    <!-- Top Examination Bar -->
    <header class="bg-white border-b border-gray-200 px-4 py-3 sticky top-0 z-30 shadow-2xs">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center space-x-3 truncate mr-4">
                <span class="w-8 h-8 rounded-xl bg-blue-700 text-white font-black text-xs flex items-center justify-center flex-shrink-0">
                    CBT
                </span>
                <div class="truncate">
                    <h1 class="text-xs sm:text-sm font-bold text-gray-900 truncate">{{ $test->title }}</h1>
                    <span class="text-[11px] text-gray-500 font-medium">Single-Correct Objective Mode</span>
                </div>
            </div>

            <!-- Live Timer Pill -->
            <div class="flex items-center space-x-3 flex-shrink-0">
                <div class="flex items-center space-x-2 px-3.5 py-1.5 rounded-full bg-slate-900 text-white text-xs font-mono font-bold tracking-wider shadow-xs">
                    <svg class="w-3.5 h-3.5 text-red-400 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span x-text="formatTime(remainingSeconds)"></span>
                </div>

                <button @click="showPaletteModal = true" class="lg:hidden p-2 rounded-xl bg-gray-100 text-gray-700 text-xs font-bold flex items-center space-x-1" title="Question Palette">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>

                <button @click="confirmSubmitModal = true" class="hidden sm:inline-flex px-4 py-1.5 rounded-full text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 transition shadow-xs">
                    Submit Exam
                </button>
            </div>
        </div>
    </header>

    <!-- Main Workspace -->
    <main class="flex-grow max-w-7xl w-full mx-auto p-4 sm:p-6 grid grid-cols-1 lg:grid-cols-12 gap-6 overflow-hidden">
        
        <!-- Left: Current Question Card -->
        <div class="lg:col-span-8 flex flex-col justify-between bg-white rounded-3xl p-6 sm:p-8 border border-gray-100 shadow-sm overflow-y-auto">
            
            <div>
                <!-- Question Header -->
                <div class="flex items-center justify-between pb-4 border-b border-gray-100 mb-6">
                    <div class="flex items-center space-x-2">
                        <span class="text-sm font-extrabold text-blue-700" x-text="'Question ' + (currentIndex + 1) + ' of ' + totalQuestions"></span>
                        <span class="text-gray-300">•</span>
                        <span class="text-xs font-semibold text-gray-600 bg-gray-100 px-2.5 py-0.5 rounded-md" x-text="currentQuestion.subject"></span>
                    </div>

                    <div class="flex items-center space-x-2 text-xs font-bold text-emerald-700">
                        <span x-text="'+' + currentQuestion.marks + ' Marks'"></span>
                        <span class="text-gray-300">•</span>
                        <span class="text-red-600">-0.25</span>
                    </div>
                </div>

                <!-- Statement -->
                <div class="text-base sm:text-lg font-bold text-gray-900 leading-relaxed mb-6" x-text="currentQuestion.text"></div>

                <!-- Answer Options -->
                <div class="space-y-3 mb-6">
                    <template x-for="opt in ['A', 'B', 'C', 'D']" :key="opt">
                        <button type="button"
                                @click="selectOption(opt)"
                                :class="answers[currentQuestion.id] === opt ? 'border-blue-600 bg-blue-50/70 text-blue-900 font-bold ring-1 ring-blue-500' : 'border-gray-200 bg-gray-50/50 hover:bg-gray-100 text-gray-800'"
                                class="w-full p-4 rounded-2xl border text-left flex items-center space-x-3 text-xs sm:text-sm transition">
                            <span :class="answers[currentQuestion.id] === opt ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 border border-gray-300'" class="w-7 h-7 rounded-full flex items-center justify-center font-bold text-xs flex-shrink-0 transition">
                                <span x-text="opt"></span>
                            </span>
                            <span x-text="currentQuestion['option_' + opt.toLowerCase()]"></span>
                        </button>
                    </template>
                </div>
            </div>

            <!-- Bottom Question Controls -->
            <div class="pt-6 border-t border-gray-100 flex flex-wrap items-center justify-between gap-3">
                <div class="flex items-center space-x-2">
                    <button type="button" @click="clearResponse()" :disabled="!answers[currentQuestion.id]" class="px-4 py-2 rounded-full text-xs font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 disabled:opacity-40 transition">
                        Clear Response
                    </button>
                    <button type="button" @click="toggleReview()" class="px-4 py-2 rounded-full text-xs font-semibold transition" :class="reviews[currentQuestion.id] ? 'bg-purple-600 text-white' : 'text-purple-700 bg-purple-50 hover:bg-purple-100'">
                        <span x-text="reviews[currentQuestion.id] ? 'Marked for Review ★' : 'Mark for Review'"></span>
                    </button>
                </div>

                <div class="flex items-center space-x-2">
                    <button type="button" @click="prevQuestion()" :disabled="currentIndex === 0" class="px-4 py-2 rounded-full text-xs font-bold text-gray-700 bg-gray-100 hover:bg-gray-200 disabled:opacity-30 transition">
                        Previous
                    </button>
                    <button type="button" @click="saveAndNext()" class="px-6 py-2 rounded-full text-xs font-bold text-white bg-blue-700 hover:bg-blue-800 shadow-xs transition">
                        <span x-text="currentIndex < totalQuestions - 1 ? 'Save & Next' : 'Save Response'"></span>
                    </button>
                </div>
            </div>

        </div>

        <!-- Right: Palette Sidebar (Desktop) -->
        <div class="hidden lg:flex lg:col-span-4 flex-col bg-white rounded-3xl p-6 border border-gray-100 shadow-sm overflow-y-auto">
            <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-4">Question Palette</h3>

            <!-- Legend -->
            <div class="grid grid-cols-2 gap-2 text-[11px] font-medium text-gray-600 mb-6 bg-gray-50 p-3 rounded-2xl border border-gray-100">
                <div class="flex items-center space-x-2">
                    <span class="w-3.5 h-3.5 rounded-md bg-emerald-500"></span>
                    <span>Answered</span>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="w-3.5 h-3.5 rounded-md bg-purple-600"></span>
                    <span>Review</span>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="w-3.5 h-3.5 rounded-md bg-gray-200"></span>
                    <span>Unanswered</span>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="w-3.5 h-3.5 rounded-md border-2 border-blue-600 bg-white"></span>
                    <span>Current</span>
                </div>
            </div>

            <!-- Numbers Grid -->
            <div class="grid grid-cols-5 gap-2.5 flex-grow overflow-y-auto pr-1">
                <template x-for="(q, idx) in questions" :key="q.id">
                    <button type="button"
                            @click="goToQuestion(idx)"
                            :class="{
                                'ring-2 ring-blue-600 ring-offset-1 font-black': currentIndex === idx,
                                'bg-purple-600 text-white font-bold': reviews[q.id],
                                'bg-emerald-500 text-white font-bold': !reviews[q.id] && answers[q.id],
                                'bg-gray-100 text-gray-700 hover:bg-gray-200': !reviews[q.id] && !answers[q.id]
                            }"
                            class="h-10 rounded-xl text-xs flex items-center justify-center transition shadow-2xs">
                        <span x-text="idx + 1"></span>
                    </button>
                </template>
            </div>

            <div class="pt-6 border-t border-gray-100 mt-6">
                <button type="button" @click="confirmSubmitModal = true" class="w-full py-3.5 rounded-full text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 shadow-md transition">
                    Submit Final Test
                </button>
            </div>
        </div>

    </main>

    <!-- Mobile Floating Submit bar -->
    <div class="sm:hidden fixed bottom-0 left-0 right-0 p-3 bg-white border-t border-gray-200 z-30 flex items-center justify-between">
        <span class="text-xs font-bold text-gray-600">
            Ans: <strong class="text-emerald-600" x-text="Object.values(answers).filter(v => v).length"></strong> / <span x-text="totalQuestions"></span>
        </span>
        <button type="button" @click="confirmSubmitModal = true" class="px-5 py-2 rounded-full text-xs font-bold text-white bg-emerald-600 shadow-xs">
            Submit Test
        </button>
    </div>

    <!-- Mobile Palette Modal / Drawer -->
    <div x-show="showPaletteModal" class="fixed inset-0 z-50 bg-black/60 flex items-end sm:items-center justify-center p-0 sm:p-4" style="display:none;">
        <div @click.away="showPaletteModal = false" class="bg-white w-full sm:max-w-md rounded-t-3xl sm:rounded-3xl p-6 max-h-[80vh] flex flex-col shadow-2xl">
            <div class="flex items-center justify-between pb-3 border-b border-gray-100 mb-4">
                <h3 class="text-sm font-bold text-gray-900">Question Palette</h3>
                <button @click="showPaletteModal = false" class="text-gray-400 hover:text-gray-700">✕</button>
            </div>
            <div class="grid grid-cols-5 gap-2.5 overflow-y-auto py-2">
                <template x-for="(q, idx) in questions" :key="q.id">
                    <button type="button"
                            @click="goToQuestion(idx); showPaletteModal = false"
                            :class="{
                                'ring-2 ring-blue-600 font-bold': currentIndex === idx,
                                'bg-purple-600 text-white font-bold': reviews[q.id],
                                'bg-emerald-500 text-white font-bold': !reviews[q.id] && answers[q.id],
                                'bg-gray-100 text-gray-700': !reviews[q.id] && !answers[q.id]
                            }"
                            class="h-10 rounded-xl text-xs flex items-center justify-center transition">
                        <span x-text="idx + 1"></span>
                    </button>
                </template>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal before Submit -->
    <div x-show="confirmSubmitModal" class="fixed inset-0 z-50 bg-black/70 flex items-center justify-center p-4" style="display:none;">
        <div @click.away="confirmSubmitModal = false" class="bg-white w-full max-w-sm rounded-3xl p-6 sm:p-8 text-center space-y-4 shadow-2xl">
            <div class="w-12 h-12 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center mx-auto text-xl">
                ✓
            </div>
            <h3 class="text-lg font-bold text-gray-900">Ready to Submit?</h3>
            <div class="bg-gray-50 rounded-2xl p-4 text-xs space-y-1.5 text-gray-600 text-left">
                <div class="flex justify-between">
                    <span>Total Questions:</span>
                    <strong x-text="totalQuestions"></strong>
                </div>
                <div class="flex justify-between text-emerald-700 font-bold">
                    <span>Answered:</span>
                    <strong x-text="Object.values(answers).filter(v => v).length"></strong>
                </div>
                <div class="flex justify-between text-purple-700 font-bold">
                    <span>Marked for Review:</span>
                    <strong x-text="Object.values(reviews).filter(v => v).length"></strong>
                </div>
                <div class="flex justify-between text-gray-400">
                    <span>Unanswered:</span>
                    <strong x-text="totalQuestions - Object.values(answers).filter(v => v).length"></strong>
                </div>
            </div>

            <form :action="submitUrl" method="POST" class="pt-2 space-y-2">
                <input type="hidden" name="_token" :value="csrfToken">
                <button type="submit" class="w-full py-3 rounded-full text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 shadow-md transition">
                    Yes, Finalize &amp; Score
                </button>
                <button type="button" @click="confirmSubmitModal = false" class="w-full py-2.5 rounded-full text-xs font-semibold text-gray-600 hover:bg-gray-100 transition">
                    Return to Test
                </button>
            </form>
        </div>
    </div>

    <!-- Engine Script -->
    <script>
        function testEngine(config) {
            return {
                attemptId: config.attemptId,
                totalQuestions: config.totalQuestions,
                questions: config.questions,
                answers: config.initialAnswers || {},
                reviews: config.initialReviews || {},
                remainingSeconds: config.initialRemainingSeconds,
                currentIndex: 0,
                showPaletteModal: false,
                confirmSubmitModal: false,
                saveUrl: config.saveUrl,
                submitUrl: config.submitUrl,
                csrfToken: config.csrfToken,

                get currentQuestion() {
                    return this.questions[this.currentIndex];
                },

                init() {
                    // Start timer countdown
                    setInterval(() => {
                        if (this.remainingSeconds > 0) {
                            this.remainingSeconds--;
                        } else {
                            // Auto submit when time runs out
                            document.forms[0].submit();
                        }
                    }, 1000);
                },

                formatTime(sec) {
                    const m = Math.floor(sec / 60);
                    const s = sec % 60;
                    return (m < 10 ? '0' : '') + m + ':' + (s < 10 ? '0' : '') + s;
                },

                selectOption(opt) {
                    this.answers[this.currentQuestion.id] = opt;
                    this.saveAnswerToServer(this.currentQuestion.id, opt, this.reviews[this.currentQuestion.id] || false);
                },

                clearResponse() {
                    delete this.answers[this.currentQuestion.id];
                    this.saveAnswerToServer(this.currentQuestion.id, null, this.reviews[this.currentQuestion.id] || false);
                },

                toggleReview() {
                    const qId = this.currentQuestion.id;
                    this.reviews[qId] = !this.reviews[qId];
                    this.saveAnswerToServer(qId, this.answers[qId] || null, this.reviews[qId]);
                },

                saveAndNext() {
                    if (this.currentIndex < this.totalQuestions - 1) {
                        this.currentIndex++;
                    }
                },

                prevQuestion() {
                    if (this.currentIndex > 0) {
                        this.currentIndex--;
                    }
                },

                goToQuestion(index) {
                    this.currentIndex = index;
                },

                saveAnswerToServer(questionId, option, isReview) {
                    fetch(this.saveUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': this.csrfToken
                        },
                        body: JSON.stringify({
                            question_id: questionId,
                            selected_option: option,
                            is_marked_for_review: isReview
                        })
                    }).catch(err => console.error('Auto-save error', err));
                }
            };
        }
    </script>
</body>
</html>
