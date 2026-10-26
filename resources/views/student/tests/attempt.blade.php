<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $test->title }} — CBT Engine | Nirmaan</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400;1,700&display=swap" rel="stylesheet">

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Minimalist scrollbar for palette */
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #F1F5F9;
            border-radius: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #CBD5E1;
            border-radius: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94A3B8;
        }
    </style>
</head>
<body class="bg-[#F8FAFC] text-gray-900 h-full flex flex-col font-sans select-none"
      x-data="testEngine({
          attemptId: {{ $attempt->id }},
          totalQuestions: {{ $test->questions->count() }},
          initialRemainingSeconds: {{ (int) floor($remainingSeconds) }},
          questions: {{ json_encode($test->questions->values()->map(fn($q) => [
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
            
            <!-- Left Test Info -->
            <div class="flex items-center space-x-3 truncate mr-4">
                <span class="w-8 h-8 rounded-lg bg-blue-700 text-white font-black text-xs flex items-center justify-center flex-shrink-0">
                    CBT
                </span>
                <div class="truncate">
                    <h1 class="text-xs sm:text-sm font-bold text-gray-900 truncate">{{ $test->title }}</h1>
                    <span class="text-[11px] text-gray-500 font-medium">Single Correct Objective • Computer Based Test</span>
                </div>
            </div>

            <!-- Right: Timer, Mobile Palette Toggle & Submit CTA -->
            <div class="flex items-center space-x-2.5 sm:space-x-3 flex-shrink-0">
                <!-- Clean Solid Timer Pill (Zero decimals, strict integer MM:SS) -->
                <div class="flex items-center space-x-2 px-3.5 py-1.5 rounded-lg text-xs font-mono font-bold tracking-wider transition-colors"
                     :class="remainingSeconds < 300 ? 'bg-red-700 text-white' : 'bg-slate-900 text-white'">
                    <svg class="w-3.5 h-3.5" :class="remainingSeconds < 300 ? 'text-white' : 'text-red-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span x-text="formatTime(remainingSeconds)">00:00</span>
                </div>

                <!-- Mobile Palette Trigger -->
                <button type="button" @click="showPaletteModal = true" class="lg:hidden px-3 py-1.5 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold flex items-center space-x-1.5 transition" title="Question Palette">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    <span>Palette</span>
                    <span class="text-[10px] px-1.5 py-0.2 rounded-full bg-emerald-100 text-emerald-800 font-bold" x-text="getAnsweredCount()"></span>
                </button>

                <!-- Submit Button -->
                <button type="button" @click="confirmSubmitModal = true" class="px-4 py-1.5 rounded-lg text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 transition">
                    Submit Exam
                </button>
            </div>

        </div>
    </header>

    <!-- Main Workspace -->
    <main class="flex-grow max-w-7xl w-full mx-auto p-4 sm:p-6 grid grid-cols-1 lg:grid-cols-12 gap-6 overflow-hidden">
        
        <!-- Left: Current Question Area -->
        <div class="lg:col-span-8 flex flex-col justify-between bg-white rounded-2xl p-6 sm:p-8 border border-gray-200 shadow-2xs overflow-y-auto">
            
            <div>
                <!-- Question Meta Strip -->
                <div class="flex items-center justify-between pb-4 border-b border-gray-200 mb-6">
                    <div class="flex items-center space-x-2">
                        <span class="text-sm font-extrabold text-blue-700" x-text="'Question ' + (currentIndex + 1) + ' of ' + totalQuestions"></span>
                        <span class="text-gray-300">•</span>
                        <span class="text-xs font-semibold text-gray-700 bg-gray-100 px-2.5 py-0.5 rounded-md" x-text="currentQuestion.subject"></span>
                    </div>

                    <div class="flex items-center space-x-2 text-xs font-bold">
                        <span class="text-emerald-700" x-text="'+' + currentQuestion.marks + ' Marks'"></span>
                        <span class="text-gray-300">•</span>
                        <span class="text-red-600">-0.25 Neg</span>
                    </div>
                </div>

                <!-- Question Text -->
                <div class="text-base sm:text-lg font-bold text-gray-900 leading-relaxed mb-6" x-text="currentQuestion.text"></div>

                <!-- Answer Options (Clean Solid Cards with Distinct Radio Badges) -->
                <div class="space-y-3 mb-6">
                    <template x-for="opt in ['A', 'B', 'C', 'D']" :key="opt">
                        <button type="button"
                                @click="selectOption(opt)"
                                :class="answers[currentQuestion.id] === opt 
                                    ? 'border-blue-600 bg-blue-50/70 text-blue-900 font-bold ring-1 ring-blue-600' 
                                    : 'border-gray-200 bg-white hover:bg-gray-50 text-gray-800 font-medium'"
                                class="w-full p-4 rounded-xl border text-left flex items-center space-x-3.5 text-xs sm:text-sm transition">
                            <span :class="answers[currentQuestion.id] === opt 
                                ? 'bg-blue-700 text-white' 
                                : 'bg-gray-100 text-gray-700 border border-gray-300'" 
                                class="w-7 h-7 rounded-lg flex items-center justify-center font-bold text-xs flex-shrink-0 transition">
                                <span x-text="opt"></span>
                            </span>
                            <span class="flex-grow" x-text="currentQuestion['option_' + opt.toLowerCase()]"></span>
                        </button>
                    </template>
                </div>
            </div>

            <!-- Bottom Question Controls -->
            <div class="pt-6 border-t border-gray-200 flex flex-wrap items-center justify-between gap-3">
                <div class="flex items-center space-x-2">
                    <button type="button" 
                            @click="clearResponse()" 
                            :disabled="!answers[currentQuestion.id]" 
                            class="px-3.5 py-2 rounded-lg text-xs font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 disabled:opacity-40 transition">
                        Clear Response
                    </button>
                    <button type="button" 
                            @click="toggleReview()" 
                            class="px-3.5 py-2 rounded-lg text-xs font-semibold transition" 
                            :class="reviews[currentQuestion.id] 
                                ? 'bg-purple-700 text-white font-bold' 
                                : 'text-purple-700 bg-purple-50 hover:bg-purple-100 border border-purple-200'">
                        <span x-text="reviews[currentQuestion.id] ? 'Marked for Review ★' : 'Mark for Review'"></span>
                    </button>
                </div>

                <div class="flex items-center space-x-2">
                    <button type="button" 
                            @click="prevQuestion()" 
                            :disabled="currentIndex === 0" 
                            class="px-4 py-2 rounded-lg text-xs font-bold text-gray-700 bg-gray-100 hover:bg-gray-200 disabled:opacity-30 transition">
                        ← Previous
                    </button>
                    <button type="button" 
                            @click="saveAndNext()" 
                            class="px-5 py-2 rounded-lg text-xs font-bold text-white bg-blue-700 hover:bg-blue-800 transition">
                        <span x-text="currentIndex < totalQuestions - 1 ? 'Save & Next →' : 'Save Response'"></span>
                    </button>
                </div>
            </div>

        </div>

        <!-- Right: Industry-Standard Question Palette Sidebar (Desktop) -->
        <div class="hidden lg:flex lg:col-span-4 flex-col bg-white rounded-2xl p-5 border border-gray-200 shadow-2xs overflow-hidden h-[calc(100vh-6.5rem)] sticky top-20">
            
            <!-- Palette Header & Progress -->
            <div class="pb-3 border-b border-gray-100 flex-shrink-0">
                <div class="flex items-center justify-between">
                    <h3 class="text-xs font-extrabold uppercase tracking-wider text-gray-500">Question Palette</h3>
                    <span class="text-[10px] font-bold text-blue-700 bg-blue-50 px-2 py-0.5 rounded-md border border-blue-100" x-text="totalQuestions + ' Total Questions'"></span>
                </div>
                
                <!-- Progress Bar -->
                <div class="mt-2.5">
                    <div class="flex items-center justify-between text-[10px] text-gray-500 font-medium">
                        <span>Answered: <strong class="text-emerald-700" x-text="getAnsweredCount()"></strong> of <span x-text="totalQuestions"></span></span>
                        <span class="font-bold text-gray-700" x-text="Math.round((getAnsweredCount() / Math.max(1, totalQuestions)) * 100) + '%'"></span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-1.5 mt-1 overflow-hidden">
                        <div class="bg-emerald-600 h-1.5 rounded-full transition-all duration-300" :style="'width: ' + ((getAnsweredCount() / Math.max(1, totalQuestions)) * 100) + '%'"></div>
                    </div>
                </div>
            </div>

            <!-- Legend with Standard CBT States and Live Counts -->
            <div class="grid grid-cols-2 gap-2 text-[11px] font-medium text-gray-700 my-3 bg-gray-50 p-2.5 rounded-xl border border-gray-200 flex-shrink-0">
                <div class="flex items-center justify-between bg-white px-2 py-1 rounded-md border border-gray-200">
                    <div class="flex items-center space-x-1.5 truncate mr-1">
                        <span class="w-2.5 h-2.5 rounded-xs bg-emerald-600 flex-shrink-0"></span>
                        <span class="truncate">Answered</span>
                    </div>
                    <strong class="text-emerald-700 text-[10px] font-bold" x-text="getAnsweredCount()"></strong>
                </div>

                <div class="flex items-center justify-between bg-white px-2 py-1 rounded-md border border-gray-200">
                    <div class="flex items-center space-x-1.5 truncate mr-1">
                        <span class="w-2.5 h-2.5 rounded-xs bg-purple-700 flex-shrink-0"></span>
                        <span class="truncate">Review</span>
                    </div>
                    <strong class="text-purple-700 text-[10px] font-bold" x-text="getReviewCount()"></strong>
                </div>

                <div class="flex items-center justify-between bg-white px-2 py-1 rounded-md border border-gray-200">
                    <div class="flex items-center space-x-1.5 truncate mr-1">
                        <span class="w-2.5 h-2.5 rounded-xs bg-gray-200 border border-gray-400 flex-shrink-0"></span>
                        <span class="truncate">Unanswered</span>
                    </div>
                    <strong class="text-gray-700 text-[10px] font-bold" x-text="totalQuestions - getAnsweredCount()"></strong>
                </div>

                <div class="flex items-center justify-between bg-white px-2 py-1 rounded-md border border-gray-200">
                    <div class="flex items-center space-x-1.5 truncate mr-1">
                        <span class="w-2.5 h-2.5 rounded-xs border-2 border-blue-700 bg-white flex-shrink-0"></span>
                        <span class="truncate">Current</span>
                    </div>
                    <span class="text-blue-700 text-[10px] font-bold" x-text="'Q' + (currentIndex + 1)"></span>
                </div>
            </div>

            <!-- Numbers Grid (Packs tightly at top using content-start, never stretches rows!) -->
            <div class="flex-grow overflow-y-auto pr-1 my-1 custom-scrollbar min-h-0">
                <div class="grid grid-cols-5 gap-2 content-start">
                    <template x-for="(q, idx) in questions" :key="q.id">
                        <button type="button"
                                @click="goToQuestion(idx)"
                                :class="{
                                    'ring-2 ring-blue-700 ring-offset-2 font-black': currentIndex === idx,
                                    'bg-purple-700 text-white font-bold': reviews[q.id],
                                    'bg-emerald-600 text-white font-bold': !reviews[q.id] && answers[q.id],
                                    'bg-gray-100 text-gray-700 border border-gray-200 hover:bg-gray-200 font-semibold': !reviews[q.id] && !answers[q.id]
                                }"
                                class="h-9 rounded-lg text-xs flex items-center justify-center transition shadow-2xs">
                            <span x-text="idx + 1"></span>
                        </button>
                    </template>
                </div>
            </div>

            <!-- Submit Final Test CTA -->
            <div class="pt-3 border-t border-gray-200 mt-2 flex-shrink-0">
                <button type="button" @click="confirmSubmitModal = true" class="w-full py-2.5 rounded-xl text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 transition flex items-center justify-center space-x-1.5 shadow-xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span>Submit Final Test</span>
                </button>
            </div>
        </div>

    </main>

    <!-- Mobile Palette Modal / Drawer -->
    <div x-show="showPaletteModal" class="fixed inset-0 z-50 bg-black/50 flex items-end sm:items-center justify-center p-0 sm:p-4" style="display:none;">
        <div @click.away="showPaletteModal = false" class="bg-white w-full sm:max-w-md rounded-t-2xl sm:rounded-2xl p-5 max-h-[85vh] flex flex-col border border-gray-200 shadow-xl">
            <div class="flex items-center justify-between pb-3 border-b border-gray-200 mb-3 flex-shrink-0">
                <div>
                    <h3 class="text-sm font-bold text-gray-900">Question Palette</h3>
                    <p class="text-[11px] text-gray-500">Tap any question number to jump directly</p>
                </div>
                <button @click="showPaletteModal = false" class="text-gray-400 hover:text-gray-700 text-sm font-bold p-1.5 rounded-lg bg-gray-100">✕</button>
            </div>
            
            <!-- Mobile Legend with Live Counts -->
            <div class="grid grid-cols-2 gap-2 text-[11px] font-medium text-gray-700 mb-3 bg-gray-50 p-2.5 rounded-xl border border-gray-200 flex-shrink-0">
                <div class="flex items-center justify-between bg-white px-2 py-1 rounded-md border border-gray-200">
                    <div class="flex items-center space-x-1.5">
                        <span class="w-2.5 h-2.5 rounded-xs bg-emerald-600"></span>
                        <span>Answered</span>
                    </div>
                    <strong class="text-emerald-700 text-[10px]" x-text="getAnsweredCount()"></strong>
                </div>
                <div class="flex items-center justify-between bg-white px-2 py-1 rounded-md border border-gray-200">
                    <div class="flex items-center space-x-1.5">
                        <span class="w-2.5 h-2.5 rounded-xs bg-purple-700"></span>
                        <span>Review</span>
                    </div>
                    <strong class="text-purple-700 text-[10px]" x-text="getReviewCount()"></strong>
                </div>
            </div>

            <!-- Numbers Grid (Packs tightly using content-start) -->
            <div class="overflow-y-auto py-2 custom-scrollbar flex-grow min-h-0">
                <div class="grid grid-cols-5 gap-2 content-start">
                    <template x-for="(q, idx) in questions" :key="q.id">
                        <button type="button"
                                @click="goToQuestion(idx); showPaletteModal = false"
                                :class="{
                                    'ring-2 ring-blue-700 ring-offset-1 font-black': currentIndex === idx,
                                    'bg-purple-700 text-white font-bold': reviews[q.id],
                                    'bg-emerald-600 text-white font-bold': !reviews[q.id] && answers[q.id],
                                    'bg-gray-100 text-gray-700 border border-gray-200': !reviews[q.id] && !answers[q.id]
                                }"
                                class="h-10 rounded-xl text-xs flex items-center justify-center transition">
                            <span x-text="idx + 1"></span>
                        </button>
                    </template>
                </div>
            </div>

            <div class="pt-3 border-t border-gray-200 mt-2 flex-shrink-0">
                <button type="button" @click="showPaletteModal = false; confirmSubmitModal = true" class="w-full py-3 rounded-xl text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 transition">
                    Finish &amp; Submit Test
                </button>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal before Submit -->
    <div x-show="confirmSubmitModal" class="fixed inset-0 z-50 bg-black/60 flex items-center justify-center p-4" style="display:none;">
        <div @click.away="confirmSubmitModal = false" class="bg-white w-full max-w-sm rounded-2xl p-6 sm:p-7 text-center space-y-4 border border-gray-200 shadow-xl">
            <div class="w-11 h-11 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center mx-auto text-xl font-bold">
                ✓
            </div>
            <div>
                <h3 class="text-base font-bold text-gray-900">Confirm Exam Submission</h3>
                <p class="text-xs text-gray-500 mt-1">Review your response counts before locking your answers.</p>
            </div>

            <div class="bg-gray-50 rounded-xl p-4 text-xs space-y-2 text-gray-600 text-left border border-gray-200">
                <div class="flex justify-between">
                    <span>Total Questions:</span>
                    <strong class="text-gray-900 font-bold" x-text="totalQuestions"></strong>
                </div>
                <div class="flex justify-between text-emerald-700 font-bold">
                    <span>Answered:</span>
                    <strong x-text="getAnsweredCount()"></strong>
                </div>
                <div class="flex justify-between text-purple-700 font-bold">
                    <span>Marked for Review:</span>
                    <strong x-text="getReviewCount()"></strong>
                </div>
                <div class="flex justify-between text-gray-500">
                    <span>Unanswered:</span>
                    <strong class="text-gray-800 font-bold" x-text="totalQuestions - getAnsweredCount()"></strong>
                </div>
            </div>

            <form :action="submitUrl" method="POST" class="pt-2 space-y-2">
                <input type="hidden" name="_token" :value="csrfToken">
                <button type="submit" class="w-full py-3 rounded-xl text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 transition">
                    Yes, Finalize &amp; Score
                </button>
                <button type="button" @click="confirmSubmitModal = false" class="w-full py-2.5 rounded-xl text-xs font-semibold text-gray-600 hover:bg-gray-100 transition">
                    Return to Exam
                </button>
            </form>
        </div>
    </div>

    <!-- Hidden form for auto-submission -->
    <form id="auto-submit-form" :action="submitUrl" method="POST" style="display:none;">
        <input type="hidden" name="_token" :value="csrfToken">
    </form>

    <!-- Engine Script -->
    <script>
        function testEngine(config) {
            return {
                attemptId: config.attemptId,
                totalQuestions: config.totalQuestions,
                questions: config.questions || [],
                answers: config.initialAnswers || {},
                reviews: config.initialReviews || {},
                remainingSeconds: Math.max(0, Math.floor(Number(config.initialRemainingSeconds) || 0)),
                currentIndex: 0,
                showPaletteModal: false,
                confirmSubmitModal: false,
                saveUrl: config.saveUrl,
                submitUrl: config.submitUrl,
                csrfToken: config.csrfToken,
                timerInterval: null,

                get currentQuestion() {
                    return this.questions[this.currentIndex] || {};
                },

                init() {
                    // Start integer countdown timer
                    this.timerInterval = setInterval(() => {
                        if (this.remainingSeconds > 0) {
                            this.remainingSeconds = Math.max(0, Math.floor(this.remainingSeconds - 1));
                        } else {
                            clearInterval(this.timerInterval);
                            // Auto submit when time runs out
                            document.getElementById('auto-submit-form').submit();
                        }
                    }, 1000);
                },

                // Guaranteed integer formatting MM:SS (zero float decimals)
                formatTime(sec) {
                    const totalSec = Math.max(0, Math.floor(Number(sec) || 0));
                    const m = Math.floor(totalSec / 60);
                    const s = totalSec % 60;
                    return String(m).padStart(2, '0') + ':' + String(s).padStart(2, '0');
                },

                getAnsweredCount() {
                    return Object.values(this.answers).filter(v => v !== null && v !== undefined && v !== '').length;
                },

                getReviewCount() {
                    return Object.values(this.reviews).filter(Boolean).length;
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
