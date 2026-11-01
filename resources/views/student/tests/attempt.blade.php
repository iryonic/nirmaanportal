<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $test->title }} — CBT Engine | Nirmaan Defence Platform</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400;1,700&display=swap" rel="stylesheet">

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Sleek minimalist scrollbar */
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
<body class="bg-slate-50/80 text-slate-800 min-h-full flex flex-col font-sans select-none antialiased overflow-x-hidden"
      x-data="testEngine({
          attemptId: {{ $attempt->id }},
          totalQuestions: {{ $test->questions->count() }},
          initialRemainingSeconds: {{ (int) floor($remainingSeconds) }},
          endTimestampMs: {{ $endTimestampMs ?? ($attempt->started_at->addSeconds($test->duration_minutes * 60)->getTimestamp() * 1000) }},
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
              'negative_marks' => $q->negative_marks,
          ])) }},
          initialAnswers: {{ json_encode($answers) }},
          initialReviews: {{ json_encode($reviewFlags) }},
          saveUrl: '{{ route('student.tests.save', $attempt->id) }}',
          submitUrl: '{{ route('student.tests.submit', $attempt->id) }}',
          csrfToken: '{{ csrf_token() }}'
      })">

    <!-- Top Examination Command Bar (Mobile-Engineered) -->
    <header class="bg-white/95 backdrop-blur-md border-b border-slate-200/80 px-3 sm:px-4 py-2 sm:py-2.5 sticky top-0 z-30 shadow-2xs">
        <div class="max-w-7xl mx-auto flex items-center justify-between gap-2">
            
            <!-- Left: Test Identity & Cadet Meta -->
            <div class="flex items-center space-x-2 sm:space-x-3 min-w-0 flex-1 mr-1">
                <div class="w-7 h-7 sm:w-9 sm:h-9 rounded-lg sm:rounded-xl bg-blue-950 text-white font-black text-[11px] sm:text-xs flex items-center justify-center shrink-0 shadow-2xs ring-1 ring-blue-900">
                    CBT
                </div>
                <div class="min-w-0 flex-1">
                    <div class="hidden sm:flex items-center space-x-1.5 text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-0.5">
                        <span>Candidate Assessment Hall</span>
                        <span>•</span>
                        <span class="text-blue-950 font-semibold">Defence Paper</span>
                    </div>
                    <h1 class="text-xs sm:text-sm font-black text-slate-900 truncate tracking-tight" title="{{ $test->title }}">
                        {{ $test->title }}
                    </h1>
                </div>
            </div>

            <!-- Right: Stopwatch HUD, Progress & Actions -->
            <div class="flex items-center space-x-1.5 sm:space-x-2.5 shrink-0">
                
                <!-- Resilient Defense Stopwatch HUD -->
                <div class="flex items-center space-x-1 sm:space-x-1.5 px-2 sm:px-3 py-1 sm:py-1.5 rounded-lg sm:rounded-xl text-[11px] sm:text-xs font-mono font-black tracking-wider transition-colors shadow-2xs"
                     :class="remainingSeconds < 300 
                        ? 'bg-rose-950 text-rose-200 border border-rose-500/50 animate-pulse' 
                        : 'bg-slate-900 text-white border border-slate-800'">
                    <span class="w-1.5 h-1.5 rounded-full"
                          :class="remainingSeconds < 300 ? 'bg-rose-500 animate-ping' : 'bg-emerald-400'"></span>
                    <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 text-slate-400" :class="remainingSeconds < 300 ? 'text-rose-400' : 'text-slate-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span x-text="formatTime(remainingSeconds)">00:00</span>
                </div>

                <!-- Answered Counter Chip (Desktop) -->
                <div class="hidden md:flex items-center space-x-1.5 px-3 py-1.5 rounded-xl bg-slate-100 border border-slate-200 text-slate-700 text-xs font-semibold">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    <span>Answered: <strong class="text-slate-900 font-bold" x-text="getAnsweredCount()"></strong>/<span x-text="totalQuestions"></span></span>
                </div>

                <!-- Mobile Palette Toggle -->
                <button type="button" @click="showPaletteModal = true" 
                        class="lg:hidden px-2 sm:px-2.5 py-1 sm:py-1.5 rounded-lg sm:rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold flex items-center space-x-1 border border-slate-200 transition-colors shadow-2xs shrink-0" 
                        title="Question Palette">
                    <svg class="w-3.5 h-3.5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    <span class="hidden xs:inline text-[11px]">Grid</span>
                    <span class="text-[10px] px-1 py-0.2 rounded-full bg-blue-100 text-blue-900 font-bold font-mono" x-text="getAnsweredCount()"></span>
                </button>

                <!-- Final Submit Exam Button -->
                <button type="button" @click="confirmSubmitModal = true" 
                        class="px-2.5 sm:px-3.5 py-1 sm:py-1.5 rounded-lg sm:rounded-xl text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 transition-colors shadow-2xs flex items-center space-x-1 shrink-0">
                    <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                    <span class="hidden xs:inline">Submit Exam</span>
                    <span class="xs:hidden">Submit</span>
                </button>
            </div>

        </div>
    </header>

    <!-- Main CBT Examination Workspace -->
    <main class="flex-grow max-w-7xl w-full mx-auto p-2.5 sm:p-5 lg:p-6 grid grid-cols-1 lg:grid-cols-12 gap-4 sm:gap-5 overflow-visible lg:overflow-hidden">
        
        <!-- Left: Question Workspace & Options Card (lg:col-span-8) -->
        <div class="lg:col-span-8 flex flex-col justify-between bg-white rounded-xl sm:rounded-2xl p-3.5 sm:p-6 lg:p-7 border border-slate-200/90 shadow-2xs overflow-y-auto min-h-0 sm:min-h-[520px]">
            
            <div>
                <!-- Question Meta Strip -->
                <div class="flex flex-wrap items-center justify-between gap-2 pb-3 sm:pb-4 border-b border-slate-100 mb-3 sm:mb-5">
                    <div class="flex items-center space-x-1.5 sm:space-x-2">
                        <span class="text-xs sm:text-sm font-black text-blue-950 tracking-tight" x-text="'Question ' + (currentIndex + 1) + ' of ' + totalQuestions"></span>
                        <span class="text-slate-300">•</span>
                        <span class="text-[11px] sm:text-xs font-semibold text-slate-700 bg-slate-100 border border-slate-200/80 px-2 sm:px-2.5 py-0.5 rounded-md" x-text="currentQuestion.subject || 'General'"></span>
                        <template x-if="currentQuestion.topic">
                            <span class="hidden md:inline-block text-[11px] font-medium text-slate-500 bg-slate-50 border border-slate-200/60 px-2 py-0.5 rounded-md" x-text="currentQuestion.topic"></span>
                        </template>
                    </div>

                    <div class="flex items-center space-x-1.5 text-[10px] sm:text-xs font-bold">
                        <span class="inline-flex items-center px-1.5 sm:px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 border border-emerald-200" x-text="'+' + Number(currentQuestion.marks || 4).toFixed(1) + ' Marks'"></span>
                        <span class="text-slate-300">•</span>
                        <span class="inline-flex items-center px-1.5 sm:px-2 py-0.5 rounded-md bg-rose-50 text-rose-700 border border-rose-200" x-text="'-' + Number(currentQuestion.negative_marks || 0.25).toFixed(2) + ' Neg'"></span>
                    </div>
                </div>

                <!-- Question Prompt -->
                <div class="text-sm sm:text-base md:text-lg font-bold text-slate-900 leading-snug sm:leading-relaxed tracking-tight py-1 mb-4 sm:mb-6 select-text" x-text="currentQuestion.text"></div>

                <!-- Answer Options (Linear / Lenskart Style Interactive Radio Cards) -->
                <div class="space-y-2.5 sm:space-y-3 mb-4 sm:mb-6">
                    <template x-for="opt in ['A', 'B', 'C', 'D']" :key="opt">
                        <button type="button"
                                @click="selectOption(opt)"
                                :class="answers[currentQuestion.id] === opt 
                                    ? 'border-2 border-blue-950 bg-blue-50/60 text-slate-950 font-semibold ring-2 ring-blue-950/10 shadow-xs' 
                                    : 'border border-slate-200/90 bg-white hover:bg-slate-50/80 hover:border-slate-300 text-slate-800 font-medium shadow-2xs'"
                                class="w-full p-3 sm:p-4 rounded-xl text-left flex items-start sm:items-center space-x-3 text-xs sm:text-sm transition-all cursor-pointer group">
                            
                            <!-- Letter Badge Pill -->
                            <span :class="answers[currentQuestion.id] === opt 
                                ? 'bg-blue-950 text-white font-black shadow-xs' 
                                : 'bg-slate-100 group-hover:bg-slate-200 text-slate-700 border border-slate-200 font-bold'" 
                                class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg flex items-center justify-center text-xs shrink-0 transition-colors mt-0.5 sm:mt-0">
                                <span x-text="opt"></span>
                            </span>

                            <!-- Option Text -->
                            <span class="flex-grow select-text leading-relaxed pt-0.5 sm:pt-0" x-text="currentQuestion['option_' + opt.toLowerCase()]"></span>

                            <!-- Active Check Indicator -->
                            <template x-if="answers[currentQuestion.id] === opt">
                                <span class="w-4 h-4 sm:w-5 sm:h-5 rounded-full bg-blue-950 text-white flex items-center justify-center shrink-0 mt-1 sm:mt-0">
                                    <svg class="w-2.5 h-2.5 sm:w-3 sm:h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                </span>
                            </template>
                        </button>
                    </template>
                </div>
            </div>

            <!-- Bottom Question Controls (Responsive Layout) -->
            <div class="pt-3 sm:pt-4 border-t border-slate-100 mt-2">
                <!-- Mobile 2x2 Grid Layout (< sm) -->
                <div class="sm:hidden flex flex-col space-y-2">
                    <div class="grid grid-cols-2 gap-2">
                        <button type="button" 
                                @click="clearResponse()" 
                                :disabled="!answers[currentQuestion.id]" 
                                class="py-2 rounded-xl text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 border border-slate-200/80 disabled:opacity-40 transition-colors text-center shadow-2xs">
                            Clear Response
                        </button>
                        <button type="button" 
                                @click="toggleReview()" 
                                class="py-2 rounded-xl text-xs font-semibold transition-all text-center shadow-2xs" 
                                :class="reviews[currentQuestion.id] 
                                    ? 'bg-amber-500 text-white font-bold shadow-xs' 
                                    : 'text-amber-900 bg-amber-50 hover:bg-amber-100 border border-amber-200'">
                            <span x-text="reviews[currentQuestion.id] ? '★ In Review' : 'Mark Review'"></span>
                        </button>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <button type="button" 
                                @click="prevQuestion()" 
                                :disabled="currentIndex === 0" 
                                class="py-2.5 rounded-xl text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 border border-slate-200/80 disabled:opacity-30 transition-colors text-center shadow-2xs">
                            ← Previous
                        </button>
                        <button type="button" 
                                @click="saveAndNext()" 
                                class="py-2.5 rounded-xl text-xs font-bold text-white bg-blue-950 hover:bg-blue-900 shadow-2xs transition-all text-center">
                            <span x-text="currentIndex < totalQuestions - 1 ? 'Save & Next →' : 'Save Response'"></span>
                        </button>
                    </div>
                </div>

                <!-- Desktop Linear Layout (>= sm) -->
                <div class="hidden sm:flex flex-wrap items-center justify-between gap-3">
                    <div class="flex items-center space-x-2">
                        <button type="button" 
                                @click="clearResponse()" 
                                :disabled="!answers[currentQuestion.id]" 
                                class="px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 border border-slate-200/80 disabled:opacity-40 transition-colors shadow-2xs">
                            Clear Response
                        </button>
                        <button type="button" 
                                @click="toggleReview()" 
                                class="px-3.5 py-2 rounded-xl text-xs font-semibold transition-all shadow-2xs" 
                                :class="reviews[currentQuestion.id] 
                                    ? 'bg-amber-500 hover:bg-amber-600 text-white font-bold shadow-xs' 
                                    : 'text-amber-900 bg-amber-50 hover:bg-amber-100 border border-amber-200'">
                            <span x-text="reviews[currentQuestion.id] ? '★ Marked for Review' : 'Mark for Review'"></span>
                        </button>
                    </div>

                    <div class="flex items-center space-x-2">
                        <button type="button" 
                                @click="prevQuestion()" 
                                :disabled="currentIndex === 0" 
                                class="px-4 py-2 rounded-xl text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 border border-slate-200/80 disabled:opacity-30 transition-colors shadow-2xs">
                            ← Previous
                        </button>
                        <button type="button" 
                                @click="saveAndNext()" 
                                class="px-5 py-2 rounded-xl text-xs font-bold text-white bg-blue-950 hover:bg-blue-900 shadow-2xs transition-all">
                            <span x-text="currentIndex < totalQuestions - 1 ? 'Save & Next →' : 'Save Response'"></span>
                        </button>
                    </div>
                </div>
            </div>

        </div>

        <!-- Right: Modern Question Palette Sidebar (Desktop lg:col-span-4) -->
        <div class="hidden lg:flex lg:col-span-4 flex-col bg-white rounded-2xl p-5 border border-slate-200/90 shadow-2xs overflow-hidden h-[calc(100vh-6.5rem)] sticky top-20">
            
            <!-- Palette Header & Progress -->
            <div class="pb-3 border-b border-slate-100 shrink-0">
                <div class="flex items-center justify-between">
                    <h3 class="text-xs font-black uppercase tracking-wider text-slate-500">Question Palette</h3>
                    <span class="text-[11px] font-bold text-blue-950 bg-blue-50 px-2 py-0.5 rounded-md border border-blue-100 font-mono" x-text="totalQuestions + ' Total Questions'"></span>
                </div>
                
                <!-- Live Progress Bar -->
                <div class="mt-3">
                    <div class="flex items-center justify-between text-[11px] text-slate-500 font-medium mb-1">
                        <span>Answered: <strong class="text-emerald-700 font-bold" x-text="getAnsweredCount()"></strong> of <span x-text="totalQuestions"></span></span>
                        <span class="font-bold text-slate-800" x-text="Math.round((getAnsweredCount() / Math.max(1, totalQuestions)) * 100) + '%'"></span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden border border-slate-200/60">
                        <div class="bg-emerald-600 h-2 rounded-full transition-all duration-300" :style="'width: ' + ((getAnsweredCount() / Math.max(1, totalQuestions)) * 100) + '%'"></div>
                    </div>
                </div>
            </div>

            <!-- Legend with CBT Status Badges and Live Counts -->
            <div class="grid grid-cols-2 gap-2 text-[11px] font-medium text-slate-700 my-3 bg-slate-50/80 p-2.5 rounded-xl border border-slate-200/80 shrink-0">
                <div class="flex items-center justify-between bg-white px-2.5 py-1.5 rounded-lg border border-slate-200 shadow-2xs">
                    <div class="flex items-center space-x-1.5 truncate mr-1">
                        <span class="w-2.5 h-2.5 rounded-sm bg-emerald-600 shrink-0"></span>
                        <span class="truncate text-[11px]">Answered</span>
                    </div>
                    <strong class="text-emerald-700 text-xs font-bold" x-text="getAnsweredCount()"></strong>
                </div>

                <div class="flex items-center justify-between bg-white px-2.5 py-1.5 rounded-lg border border-slate-200 shadow-2xs">
                    <div class="flex items-center space-x-1.5 truncate mr-1">
                        <span class="w-2.5 h-2.5 rounded-sm bg-amber-500 shrink-0"></span>
                        <span class="truncate text-[11px]">Review</span>
                    </div>
                    <strong class="text-amber-700 text-xs font-bold" x-text="getReviewCount()"></strong>
                </div>

                <div class="flex items-center justify-between bg-white px-2.5 py-1.5 rounded-lg border border-slate-200 shadow-2xs">
                    <div class="flex items-center space-x-1.5 truncate mr-1">
                        <span class="w-2.5 h-2.5 rounded-sm bg-slate-200 border border-slate-400 shrink-0"></span>
                        <span class="truncate text-[11px]">Unanswered</span>
                    </div>
                    <strong class="text-slate-700 text-xs font-bold" x-text="totalQuestions - getAnsweredCount()"></strong>
                </div>

                <div class="flex items-center justify-between bg-white px-2.5 py-1.5 rounded-lg border border-slate-200 shadow-2xs">
                    <div class="flex items-center space-x-1.5 truncate mr-1">
                        <span class="w-2.5 h-2.5 rounded-sm border-2 border-blue-950 bg-white shrink-0"></span>
                        <span class="truncate text-[11px]">Current</span>
                    </div>
                    <span class="text-blue-950 text-xs font-bold" x-text="'Q' + (currentIndex + 1)"></span>
                </div>
            </div>

            <!-- Numbers Grid (Packs tightly at top using content-start, never stretches rows) -->
            <div class="flex-grow overflow-y-auto p-1 custom-scrollbar min-h-0">
                <div class="grid grid-cols-5 gap-2 content-start">
                    <template x-for="(q, idx) in questions" :key="q.id">
                        <button type="button"
                                @click="goToQuestion(idx)"
                                :class="{
                                    'ring-2 ring-blue-950 ring-offset-2 font-black': currentIndex === idx,
                                    'bg-amber-500 text-white font-bold shadow-2xs': reviews[q.id],
                                    'bg-emerald-600 text-white font-bold shadow-2xs': !reviews[q.id] && answers[q.id],
                                    'bg-slate-100 text-slate-700 border border-slate-200 hover:bg-slate-200 font-semibold': !reviews[q.id] && !answers[q.id]
                                }"
                                class="h-9 sm:h-10 rounded-xl text-xs flex items-center justify-center transition-all cursor-pointer">
                            <span x-text="idx + 1"></span>
                        </button>
                    </template>
                </div>
            </div>

            <!-- Bottom Finish CTA -->
            <div class="pt-3 border-t border-slate-100 mt-2 shrink-0">
                <button type="button" @click="confirmSubmitModal = true" class="w-full py-2.5 rounded-xl text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 shadow-2xs transition-colors">
                    Finish &amp; Score Examination
                </button>
            </div>
        </div>

    </main>

    <!-- Mobile Palette Modal / Drawer (Full Screen / Bottom Sheet with Live Legend) -->
    <div x-show="showPaletteModal" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 bg-slate-950/60 backdrop-blur-xs flex flex-col justify-end lg:hidden" style="display:none;">
        
        <div @click.away="showPaletteModal = false" class="bg-white rounded-t-3xl p-4 sm:p-5 max-h-[88vh] flex flex-col shadow-2xl border-t border-slate-200">
            <!-- Drawer Drag Handle -->
            <div class="w-10 h-1 bg-slate-300 rounded-full mx-auto mb-2.5"></div>

            <div class="flex items-center justify-between pb-3 border-b border-slate-100 shrink-0">
                <div>
                    <h3 class="text-sm font-black text-slate-900">Question Palette</h3>
                    <p class="text-[11px] text-slate-400">Tap any question number to jump directly</p>
                </div>
                <button type="button" @click="showPaletteModal = false" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-lg hover:bg-slate-100 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Mobile Live Legend -->
            <div class="grid grid-cols-2 gap-2 text-[10px] font-medium text-slate-700 my-2.5 bg-slate-50/80 p-2 rounded-xl border border-slate-200/80 shrink-0">
                <div class="flex items-center justify-between bg-white px-2 py-1 rounded-md border border-slate-200 shadow-2xs">
                    <div class="flex items-center space-x-1.5 truncate">
                        <span class="w-2 h-2 rounded-sm bg-emerald-600 shrink-0"></span>
                        <span class="truncate">Answered</span>
                    </div>
                    <strong class="text-emerald-700 font-bold font-mono" x-text="getAnsweredCount()"></strong>
                </div>

                <div class="flex items-center justify-between bg-white px-2 py-1 rounded-md border border-slate-200 shadow-2xs">
                    <div class="flex items-center space-x-1.5 truncate">
                        <span class="w-2 h-2 rounded-sm bg-amber-500 shrink-0"></span>
                        <span class="truncate">Review</span>
                    </div>
                    <strong class="text-amber-700 font-bold font-mono" x-text="getReviewCount()"></strong>
                </div>

                <div class="flex items-center justify-between bg-white px-2 py-1 rounded-md border border-slate-200 shadow-2xs">
                    <div class="flex items-center space-x-1.5 truncate">
                        <span class="w-2 h-2 rounded-sm bg-slate-200 border border-slate-400 shrink-0"></span>
                        <span class="truncate">Unanswered</span>
                    </div>
                    <strong class="text-slate-700 font-bold font-mono" x-text="totalQuestions - getAnsweredCount()"></strong>
                </div>

                <div class="flex items-center justify-between bg-white px-2 py-1 rounded-md border border-slate-200 shadow-2xs">
                    <div class="flex items-center space-x-1.5 truncate">
                        <span class="w-2 h-2 rounded-sm border border-blue-950 bg-white shrink-0"></span>
                        <span class="truncate">Current</span>
                    </div>
                    <span class="text-blue-950 font-bold font-mono" x-text="'Q' + (currentIndex + 1)"></span>
                </div>
            </div>

            <!-- Scrollable Number Grid -->
            <div class="flex-grow overflow-y-auto py-2.5 custom-scrollbar min-h-0">
                <div class="grid grid-cols-5 sm:grid-cols-6 gap-2 content-start">
                    <template x-for="(q, idx) in questions" :key="q.id">
                        <button type="button"
                                @click="goToQuestion(idx); showPaletteModal = false"
                                :class="{
                                    'ring-2 ring-blue-950 ring-offset-1 font-black': currentIndex === idx,
                                    'bg-amber-500 text-white font-bold': reviews[q.id],
                                    'bg-emerald-600 text-white font-bold': !reviews[q.id] && answers[q.id],
                                    'bg-slate-100 text-slate-700 border border-slate-200': !reviews[q.id] && !answers[q.id]
                                }"
                                class="h-10 rounded-xl text-xs font-semibold flex items-center justify-center transition-colors">
                            <span x-text="idx + 1"></span>
                        </button>
                    </template>
                </div>
            </div>

            <!-- Mobile Drawer Bottom Submit Action -->
            <div class="pt-3 border-t border-slate-100 shrink-0">
                <button type="button" @click="showPaletteModal = false; confirmSubmitModal = true" class="w-full py-2.5 rounded-xl text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 shadow-2xs transition-colors">
                    Finish &amp; Submit Test
                </button>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal before Submit -->
    <div x-show="confirmSubmitModal" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="fixed inset-0 z-50 bg-slate-950/60 backdrop-blur-xs flex items-center justify-center p-4" style="display:none;">
        
        <div @click.away="confirmSubmitModal = false" class="bg-white w-full max-w-sm rounded-2xl p-5 sm:p-7 text-center space-y-4 border border-slate-200/90 shadow-2xl">
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-700 border border-emerald-200 flex items-center justify-center mx-auto text-xl font-bold shadow-2xs">
                ✓
            </div>
            <div>
                <h3 class="text-base font-black text-slate-900 tracking-tight">Confirm Exam Submission</h3>
                <p class="text-xs text-slate-500 mt-1">Review your response summary before locking your test paper.</p>
            </div>

            <div class="bg-slate-50 rounded-xl p-3.5 sm:p-4 text-xs space-y-2 text-slate-600 text-left border border-slate-200/80">
                <div class="flex justify-between">
                    <span>Total Questions:</span>
                    <strong class="text-slate-900 font-bold" x-text="totalQuestions"></strong>
                </div>
                <div class="flex justify-between text-emerald-700 font-bold">
                    <span>Answered:</span>
                    <strong x-text="getAnsweredCount()"></strong>
                </div>
                <div class="flex justify-between text-amber-700 font-bold">
                    <span>Marked for Review:</span>
                    <strong x-text="getReviewCount()"></strong>
                </div>
                <div class="flex justify-between text-slate-500">
                    <span>Unanswered:</span>
                    <strong class="text-slate-800 font-bold" x-text="totalQuestions - getAnsweredCount()"></strong>
                </div>
            </div>

            <form :action="submitUrl" method="POST" @submit="cleanupStorage()" class="pt-2 space-y-2">
                <input type="hidden" name="_token" :value="csrfToken">
                <button type="submit" class="w-full py-2.5 sm:py-3 rounded-xl text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 shadow-2xs transition-colors">
                    Yes, Finalize &amp; Score
                </button>
                <button type="button" @click="confirmSubmitModal = false" class="w-full py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition-colors">
                    Return to Exam
                </button>
            </form>
        </div>
    </div>

    <!-- Hidden form for automatic submission upon time-out -->
    <form id="auto-submit-form" :action="submitUrl" method="POST" style="display:none;">
        <input type="hidden" name="_token" :value="csrfToken">
    </form>

    <!-- Engine Script -->
    <script>
        function testEngine(config) {
            const attemptKey = 'nirmaan_cbt_attempt_' + config.attemptId;

            // Target End Timestamp calculation (Resilient across reloads & tab background throttles)
            // 1. Prefer server authoritative end timestamp (in ms)
            // 2. Fallback to client localStorage or client time offset
            let targetEndTime = Number(config.endTimestampMs);
            if (!targetEndTime || isNaN(targetEndTime)) {
                const storedEnd = localStorage.getItem(attemptKey + '_end');
                if (storedEnd && !isNaN(Number(storedEnd))) {
                    targetEndTime = Number(storedEnd);
                } else {
                    targetEndTime = Date.now() + (Math.max(0, Number(config.initialRemainingSeconds) || 0) * 1000);
                }
            }
            localStorage.setItem(attemptKey + '_end', targetEndTime);

            // Compute exact current remaining seconds based on absolute target time
            const computeRemaining = () => {
                const msLeft = targetEndTime - Date.now();
                return Math.max(0, Math.floor(msLeft / 1000));
            };

            // Restore last active question index
            let startIdx = 0;
            try {
                const savedQ = parseInt(localStorage.getItem(attemptKey + '_q') || '0', 10);
                if (!isNaN(savedQ) && savedQ >= 0 && savedQ < (config.questions || []).length) {
                    startIdx = savedQ;
                }
            } catch(e) {}

            return {
                attemptId: config.attemptId,
                totalQuestions: config.totalQuestions,
                questions: config.questions || [],
                answers: config.initialAnswers || {},
                reviews: config.initialReviews || {},
                remainingSeconds: computeRemaining(),
                targetEndTime: targetEndTime,
                currentIndex: startIdx,
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
                    // Immediate computation on load
                    this.tick();

                    // Continuous integer countdown timer synchronized to absolute target end timestamp
                    this.timerInterval = setInterval(() => {
                        this.tick();
                    }, 1000);

                    // Also synchronize on tab focus in case mobile browser throttled background intervals
                    window.addEventListener('focus', () => {
                        this.tick();
                    });
                    document.addEventListener('visibilitychange', () => {
                        if (!document.hidden) {
                            this.tick();
                        }
                    });
                },

                tick() {
                    const sec = Math.max(0, Math.floor((this.targetEndTime - Date.now()) / 1000));
                    this.remainingSeconds = sec;

                    if (sec <= 0) {
                        clearInterval(this.timerInterval);
                        this.cleanupStorage();
                        // Auto submit when time runs out
                        document.getElementById('auto-submit-form').submit();
                    }
                },

                cleanupStorage() {
                    try {
                        localStorage.removeItem(attemptKey + '_end');
                        localStorage.removeItem(attemptKey + '_q');
                    } catch(e) {}
                },

                // Guaranteed integer formatting MM:SS (or HH:MM:SS if > 1 hour)
                formatTime(sec) {
                    const totalSec = Math.max(0, Math.floor(Number(sec) || 0));
                    const h = Math.floor(totalSec / 3600);
                    const m = Math.floor((totalSec % 3600) / 60);
                    const s = totalSec % 60;
                    
                    if (h > 0) {
                        return String(h).padStart(2, '0') + ':' + String(m).padStart(2, '0') + ':' + String(s).padStart(2, '0');
                    }
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
                        this.goToQuestion(this.currentIndex + 1);
                    }
                },

                prevQuestion() {
                    if (this.currentIndex > 0) {
                        this.goToQuestion(this.currentIndex - 1);
                    }
                },

                goToQuestion(index) {
                    this.currentIndex = index;
                    try {
                        localStorage.setItem(attemptKey + '_q', index);
                    } catch(e) {}
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
