@extends('layouts.student')

@section('title', 'Interactive Practice Question Bank — Nirmaan Defence')

@section('content')
<div class="py-6 sm:py-10 bg-slate-50/70 min-h-screen text-slate-900" 
     x-data="practiceHUD()" 
     x-init="initHUD()">

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        
        <!-- Header & Session Performance HUD -->
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs p-5 sm:p-6 space-y-4">
            
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100">
                <div>
                  
                    <h1 class="text-xl sm:text-2xl font-black tracking-tight text-slate-950">
                        Practice Question Bank
                    </h1>
                   
                </div>

                <!-- Session Stopwatch & Quick Controls -->
                <div class="flex flex-wrap items-center gap-2.5 self-start sm:self-auto">
                    <!-- Timer Display Box -->
                    <div class="flex items-center space-x-2 px-3 py-1.5 rounded-xl border transition-all font-mono font-bold text-xs"
                         :class="timerRunning ? 'bg-emerald-50 border-emerald-300 text-emerald-950 shadow-xs' : 'bg-slate-100 border-slate-200 text-slate-700'">
                        <span class="w-2 h-2 rounded-full" 
                              :class="timerRunning ? 'bg-emerald-500 animate-pulse' : (secondsElapsed > 0 ? 'bg-amber-400' : 'bg-slate-400')"></span>
                        <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span x-text="formattedTime" class="text-sm font-black">00:00</span>
                    </div>

                    <!-- Start / Pause / Resume Button -->
                    <button type="button" 
                            @click="toggleTimer()" 
                            class="flex items-center space-x-1.5 px-3 py-1.5 rounded-xl text-xs font-bold transition-all shadow-xs cursor-pointer"
                            :class="timerRunning ? 'bg-amber-500 hover:bg-amber-600 text-white' : 'bg-emerald-600 hover:bg-emerald-700 text-white'">
                        <template x-if="!timerRunning">
                            <span class="flex items-center space-x-1.5">
                                <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                <span x-text="secondsElapsed > 0 ? 'Resume Timer' : 'Start Stopwatch'"></span>
                            </span>
                        </template>
                        <template x-if="timerRunning">
                            <span class="flex items-center space-x-1.5">
                                <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/></svg>
                                <span>Pause</span>
                            </span>
                        </template>
                    </button>

                    <!-- Reset Button -->
                    <button type="button" 
                            @click="resetSessionStats()" 
                            title="Reset practice session metrics & timer"
                            class="px-2.5 py-1.5 rounded-xl text-xs font-semibold text-slate-600 hover:text-slate-900 bg-white border border-slate-200 hover:bg-slate-50 transition-colors shadow-2xs">
                        Reset
                    </button>
                </div>
            </div>

            

        </div>

        <!-- Subject & Difficulty Custom Select Components (Placed Side by Side with Space Between) -->
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-2xs p-4 sm:p-6">
            <div class="flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4 md:gap-8">
                
                <!-- Custom Select 1: Subject Selection (Anchor Tag Component) -->
                <div class="flex-1 relative" x-data="{ open: false }" @click.outside="open = false" @keydown.escape.window="open = false">
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5 flex items-center justify-between">
                        <span class="flex items-center space-x-1.5">
                            <svg class="w-3.5 h-3.5 text-blue-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            <span>Subject</span>
                        </span>
                        <span class="text-[10px] text-slate-400 font-semibold">{{ $totalAvailable }} Total Qs</span>
                    </label>

                    @php
                        $subjectIcons = [
                            'all' => '🎯',
                            'Mathematics' => '📐',
                            'General Science' => '🔬',
                            'General Knowledge' => '🛡️',
                            'Reasoning' => '🧩',
                            'English' => '📖'
                        ];
                        $currentSubIcon = $subjectIcons[$selectedSubject] ?? '📚';
                        $currentSubCount = $selectedSubject === 'all' ? $totalAvailable : ($subjectCounts[$selectedSubject] ?? 0);
                    @endphp

                    <!-- Trigger Button -->
                    <button type="button" 
                            @click="open = !open" 
                            class="w-full flex items-center justify-between px-4 py-3 rounded-xl bg-slate-50/80 hover:bg-slate-100/80 border border-slate-200 transition-all text-left focus:outline-none focus:ring-2 focus:ring-blue-950/20"
                            :class="open ? 'border-blue-900 ring-2 ring-blue-950/15 bg-white' : ''">
                        <div class="flex items-center space-x-3 truncate">
                            <span class="text-xl shrink-0">{{ $currentSubIcon }}</span>
                            <div class="truncate">
                                <span class="block text-xs font-bold text-slate-900 truncate">
                                    {{ $selectedSubject === 'all' ? 'All Subjects' : $selectedSubject }}
                                </span>
                                <span class="block text-[11px] text-slate-500 font-medium">
                                    {{ $currentSubCount }} questions available
                                </span>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2 shrink-0 ml-2">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-900">
                                {{ $currentSubCount }}
                            </span>
                            <svg class="w-4 h-4 text-slate-400 transition-transform duration-200" :class="open ? 'rotate-180 text-blue-950' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </button>

                    <!-- Custom Dropdown Menu with <a> Tag Items -->
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 translate-y-1 scale-98"
                         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                         x-transition:leave-end="opacity-0 translate-y-1 scale-98"
                         class="absolute z-30 left-0 right-0 mt-2 bg-white rounded-xl shadow-xl border border-slate-200 p-2 space-y-1 max-h-80 overflow-y-auto"
                         style="display: none;">
                        
                        <!-- Option: All Subjects -->
                        <a href="{{ route('student.practice', array_merge(request()->except(['subject', 'page']), ['subject' => 'all'])) }}"
                           class="flex items-center justify-between px-3.5 py-2.5 rounded-lg text-xs font-medium transition-all {{ $selectedSubject === 'all' ? 'bg-blue-950 text-white font-bold shadow-xs' : 'text-slate-700 hover:bg-slate-100' }}">
                            <div class="flex items-center space-x-2.5">
                                <span class="text-base">🎯</span>
                                <div>
                                    <span class="block {{ $selectedSubject === 'all' ? 'text-white' : 'text-slate-900' }}">All Subjects</span>
                                    <span class="block text-[10px] {{ $selectedSubject === 'all' ? 'text-blue-200' : 'text-slate-400' }}">Complete practice repository</span>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $selectedSubject === 'all' ? 'bg-blue-800 text-white' : 'bg-slate-100 text-slate-600' }}">
                                    {{ $totalAvailable }}
                                </span>
                                @if($selectedSubject === 'all')
                                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                @endif
                            </div>
                        </a>

                        <div class="h-px bg-slate-100 my-1"></div>

                        <!-- Specific Subjects -->
                        @foreach($subjects as $sub)
                            @php
                                $sIcon = $subjectIcons[$sub] ?? '📚';
                                $sCount = $subjectCounts[$sub] ?? 0;
                                $isSubActive = ($selectedSubject === $sub);
                            @endphp
                            <a href="{{ route('student.practice', array_merge(request()->except(['subject', 'page']), ['subject' => $sub])) }}"
                               class="flex items-center justify-between px-3.5 py-2.5 rounded-lg text-xs font-medium transition-all {{ $isSubActive ? 'bg-blue-950 text-white font-bold shadow-xs' : 'text-slate-700 hover:bg-slate-100' }}">
                                <div class="flex items-center space-x-2.5">
                                    <span class="text-base">{{ $sIcon }}</span>
                                    <div>
                                        <span class="block {{ $isSubActive ? 'text-white' : 'text-slate-900 font-semibold' }}">{{ $sub }}</span>
                                        <span class="block text-[10px] {{ $isSubActive ? 'text-blue-200' : 'text-slate-400' }}">
                                            @if($sub === 'Mathematics') Algebra, Calculus, Trigonometry
                                            @elseif($sub === 'General Science') Physics, Chemistry, Biology
                                            @elseif($sub === 'General Knowledge') Defence, Polity, History, Geo
                                            @elseif($sub === 'Reasoning') Logic, Series, Verbal & Non-Verbal
                                            @elseif($sub === 'English') Grammar, Vocab, Comprehension
                                            @endif
                                        </span>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $isSubActive ? 'bg-blue-800 text-white' : 'bg-slate-100 text-slate-600' }}">
                                        {{ $sCount }}
                                    </span>
                                    @if($isSubActive)
                                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Custom Select 2: Difficulty Selection (Anchor Tag Component) -->
                <div class="flex-1 relative" x-data="{ open: false }" @click.outside="open = false" @keydown.escape.window="open = false">
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5 flex items-center justify-between">
                        <span class="flex items-center space-x-1.5">
                            <svg class="w-3.5 h-3.5 text-blue-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            <span>Difficulty Level</span>
                        </span>
                        <span class="text-[10px] text-slate-400 font-semibold">Self-Paced Target</span>
                    </label>

                    @php
                        $diffMeta = [
                            '' => ['label' => 'All Difficulty Levels', 'icon' => '⚡', 'desc' => 'Mixed foundational to advanced', 'badge' => 'bg-slate-100 text-slate-700'],
                            'easy' => ['label' => 'Easy Level', 'icon' => '🟢', 'desc' => 'Foundational concepts & direct formulae', 'badge' => 'bg-emerald-100 text-emerald-800'],
                            'medium' => ['label' => 'Medium Level', 'icon' => '🟡', 'desc' => 'Standard defence exam pattern', 'badge' => 'bg-amber-100 text-amber-800'],
                            'hard' => ['label' => 'Hard Level', 'icon' => '🔴', 'desc' => 'Multi-step advanced analysis', 'badge' => 'bg-rose-100 text-rose-800'],
                        ];
                        $curDiffKey = request('difficulty') ?? '';
                        $curDiff = $diffMeta[$curDiffKey] ?? $diffMeta[''];
                    @endphp

                    <!-- Trigger Button -->
                    <button type="button" 
                            @click="open = !open" 
                            class="w-full flex items-center justify-between px-4 py-3 rounded-xl bg-slate-50/80 hover:bg-slate-100/80 border border-slate-200 transition-all text-left focus:outline-none focus:ring-2 focus:ring-blue-950/20"
                            :class="open ? 'border-blue-900 ring-2 ring-blue-950/15 bg-white' : ''">
                        <div class="flex items-center space-x-3 truncate">
                            <span class="text-xl shrink-0">{{ $curDiff['icon'] }}</span>
                            <div class="truncate">
                                <span class="block text-xs font-bold text-slate-900 truncate">
                                    {{ $curDiff['label'] }}
                                </span>
                                <span class="block text-[11px] text-slate-500 font-medium">
                                    {{ $curDiff['desc'] }}
                                </span>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2 shrink-0 ml-2">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $curDiff['badge'] }}">
                                {{ $curDiffKey ? ucfirst($curDiffKey) : 'All' }}
                            </span>
                            <svg class="w-4 h-4 text-slate-400 transition-transform duration-200" :class="open ? 'rotate-180 text-blue-950' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </button>

                    <!-- Custom Dropdown Menu with <a> Tag Items -->
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 translate-y-1 scale-98"
                         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                         x-transition:leave-end="opacity-0 translate-y-1 scale-98"
                         class="absolute z-30 left-0 right-0 mt-2 bg-white rounded-xl shadow-xl border border-slate-200 p-2 space-y-1 overflow-hidden"
                         style="display: none;">
                        
                        @foreach($diffMeta as $dKey => $dInfo)
                            @php
                                $isDiffActive = ($curDiffKey === $dKey);
                            @endphp
                            <a href="{{ route('student.practice', array_merge(request()->except(['difficulty', 'page']), ['difficulty' => $dKey])) }}"
                               class="flex items-center justify-between px-3.5 py-2.5 rounded-lg text-xs font-medium transition-all {{ $isDiffActive ? 'bg-blue-950 text-white font-bold shadow-xs' : 'text-slate-700 hover:bg-slate-100' }}">
                                <div class="flex items-center space-x-2.5">
                                    <span class="text-base">{{ $dInfo['icon'] }}</span>
                                    <div>
                                        <span class="block {{ $isDiffActive ? 'text-white' : 'text-slate-900 font-semibold' }}">{{ $dInfo['label'] }}</span>
                                        <span class="block text-[10px] {{ $isDiffActive ? 'text-blue-200' : 'text-slate-400' }}">{{ $dInfo['desc'] }}</span>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $isDiffActive ? 'bg-blue-800 text-white' : $dInfo['badge'] }}">
                                        {{ $dKey ? ucfirst($dKey) : 'All' }}
                                    </span>
                                    @if($isDiffActive)
                                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>

        <!-- Secondary Utility Bar: Search, Topic, and Bookmark Toggle -->
        <div class="bg-white rounded-xl border border-slate-200/90 shadow-2xs p-3.5 flex flex-col md:flex-row md:items-center justify-between gap-3 text-xs">
            
            <!-- Left: Search & Topic Filter -->
            <form action="{{ route('student.practice') }}" method="GET" class="flex flex-wrap items-center gap-2 flex-1">
                <input type="hidden" name="subject" value="{{ $selectedSubject }}">
                @if(request('difficulty'))
                    <input type="hidden" name="difficulty" value="{{ request('difficulty') }}">
                @endif

                <!-- Search Input -->
                <div class="relative flex-1 min-w-[200px] max-w-sm">
                    <input type="text" name="q" value="{{ $search }}" placeholder="Search questions, topics or formulas..."
                           class="w-full pl-8 pr-3 py-2 rounded-lg border border-slate-300 text-xs focus:ring-2 focus:ring-blue-950/20 focus:border-blue-950 outline-none">
                    <svg class="w-4 h-4 text-slate-400 absolute left-2.5 top-2.5 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>

                <!-- Topic Selector -->
                @if(isset($topics) && count($topics) > 0)
                    <select name="topic" onchange="this.form.submit()" 
                            class="py-2 px-3 rounded-lg border border-slate-300 text-xs text-slate-700 bg-white outline-none focus:ring-2 focus:ring-blue-950/20 focus:border-blue-950 max-w-[200px]">
                        <option value="">All Topics ({{ count($topics) }})</option>
                        @foreach($topics as $t)
                            <option value="{{ $t }}" {{ $selectedTopic === $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                @endif

                <button type="submit" class="px-3.5 py-2 rounded-lg bg-blue-950 text-white font-bold text-xs hover:bg-blue-900 transition-colors">
                    Search
                </button>

                @if($search || $selectedTopic || request('difficulty') || $selectedSubject !== 'all')
                    <a href="{{ route('student.practice') }}" 
                       class="px-2.5 py-2 text-slate-500 hover:text-red-600 text-xs font-semibold underline">
                        Reset All Filters
                    </a>
                @endif
            </form>

            <!-- Right: Bookmarks Quick Toggle & Result Count -->
            <div class="flex items-center justify-between sm:justify-end gap-3 shrink-0">
                <span class="text-xs text-slate-500 font-medium">
                    Showing <strong class="text-slate-900 font-bold">{{ $questions->firstItem() ?? 0 }}-{{ $questions->lastItem() ?? 0 }}</strong> of <strong class="text-blue-950 font-bold">{{ $questions->total() }}</strong>
                </span>

                <!-- Bookmark Only Filter Button -->
                <button type="button" @click="onlyBookmarks = !onlyBookmarks" 
                        :class="onlyBookmarks ? 'bg-blue-950 text-white shadow-xs' : 'bg-slate-100 text-slate-700 hover:bg-slate-200 border border-slate-200'"
                        class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all inline-flex items-center space-x-1.5 cursor-pointer">
                    <span x-text="onlyBookmarks ? '★ Filtered: Saved Only' : '☆ Filter Saved'"></span>
                    <span class="px-1.5 py-0.2 rounded-full text-[10px]" :class="onlyBookmarks ? 'bg-blue-800 text-white' : 'bg-slate-200 text-slate-700'" x-text="bookmarks.length"></span>
                </button>
            </div>

        </div>

        <!-- Questions Feed -->
        <div class="space-y-4">
            @forelse($questions as $qIndex => $q)
                <div x-data="{ 
                        qId: {{ $q->id }},
                        selectedOption: null, 
                        submitted: false, 
                        isCorrect: false, 
                        correctOption: '', 
                        explanation: '',
                        loading: false,
                        showHint: false,
                        marksAwarded: 0,
                        checkAnswer() {
                            if (!this.selectedOption || this.submitted) return;
                            this.loading = true;
                            fetch('{{ route('student.practice.check', [], false) }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    question_id: this.qId,
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
                                
                                // Update session HUD metrics
                                $dispatch('drill-evaluated', { isCorrect: this.isCorrect, qId: this.qId });
                            })
                            .catch(err => {
                                this.loading = false;
                            });
                        }
                    }" 
                    x-show="!onlyBookmarks || isBookmarked(qId)"
                    class="bg-white rounded-2xl p-5 sm:p-7 border border-slate-200/90 shadow-2xs space-y-4 transition-all hover:border-slate-300">
                    
                    <!-- Question Top Header & Meta Badges -->
                    <div class="flex items-center justify-between gap-3 text-xs pb-3 border-b border-slate-100">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="font-black text-blue-950 font-mono text-sm">
                                Q{{ $questions->firstItem() + $qIndex }}
                            </span>
                            
                            <span class="font-extrabold text-[11px] px-2.5 py-0.5 rounded-full bg-blue-50 text-blue-950 border border-blue-200/70">
                                {{ $q->subject }}
                            </span>
                            
                            @if($q->topic)
                                <span class="font-medium text-slate-500 text-[11px]">
                                    • {{ $q->topic }}
                                </span>
                            @endif

                            <span class="text-[10px] font-bold px-2 py-0.5 rounded uppercase {{ $q->difficulty === 'easy' ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : ($q->difficulty === 'medium' ? 'bg-amber-50 text-amber-800 border border-amber-200' : 'bg-rose-50 text-rose-800 border border-rose-200') }}">
                                {{ $q->difficulty }}
                            </span>
                        </div>

                        <!-- Right Meta: Marks and Bookmark Toggle -->
                        <div class="flex items-center space-x-2">
                            <span class="text-[11px] font-mono text-slate-400 font-semibold hidden sm:inline-block">
                                +{{ number_format($q->marks ?? 2, 1) }} / -{{ number_format($q->negative_marks ?? 0.5, 2) }}
                            </span>

                            <button type="button" @click="toggleBookmark(qId)" 
                                    class="p-1.5 rounded-lg text-slate-400 hover:text-amber-500 hover:bg-slate-50 transition-colors"
                                    :class="isBookmarked(qId) ? 'text-amber-500 bg-amber-50' : 'text-slate-400'"
                                    :title="isBookmarked(qId) ? 'Remove Bookmark' : 'Bookmark Question'">
                                <svg class="w-4 h-4" :fill="isBookmarked(qId) ? 'currentColor' : 'none'" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path></svg>
                            </button>
                        </div>
                    </div>

                    <!-- Question Content -->
                    <div class="text-sm sm:text-base font-bold text-slate-900 leading-relaxed">
                        {{ $q->question_text }}
                    </div>

                    <!-- 4 Interactive Option Blocks -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 pt-1">
                        @foreach(['A' => $q->option_a, 'B' => $q->option_b, 'C' => $q->option_c, 'D' => $q->option_d] as $optKey => $optVal)
                            <button type="button"
                                    @click="if(!submitted) { selectedOption = '{{ $optKey }}'; checkAnswer(); }"
                                    :disabled="submitted || loading"
                                    :class="{
                                        'border-blue-950 bg-blue-50/70 text-blue-950 font-bold ring-1 ring-blue-950': selectedOption === '{{ $optKey }}' && !submitted,
                                        'border-emerald-600 bg-emerald-50 text-emerald-950 font-extrabold ring-2 ring-emerald-500': submitted && correctOption === '{{ $optKey }}',
                                        'border-rose-500 bg-rose-50 text-rose-950 font-bold ring-1 ring-rose-400': submitted && selectedOption === '{{ $optKey }}' && !isCorrect,
                                        'border-slate-200 bg-white hover:border-slate-300 hover:bg-slate-50 text-slate-800': selectedOption !== '{{ $optKey }}' && (!submitted || correctOption !== '{{ $optKey }}'),
                                        'opacity-60 cursor-not-allowed': submitted && correctOption !== '{{ $optKey }}' && selectedOption !== '{{ $optKey }}'
                                    }"
                                    class="p-3 sm:p-3.5 rounded-xl border text-left flex items-start space-x-3 text-xs sm:text-sm transition-all shadow-2xs cursor-pointer group">
                                <span :class="{
                                        'bg-blue-950 text-white': selectedOption === '{{ $optKey }}' && !submitted,
                                        'bg-emerald-600 text-white': submitted && correctOption === '{{ $optKey }}',
                                        'bg-rose-600 text-white': submitted && selectedOption === '{{ $optKey }}' && !isCorrect,
                                        'bg-slate-100 text-slate-700 group-hover:bg-slate-200': selectedOption !== '{{ $optKey }}' && (!submitted || correctOption !== '{{ $optKey }}')
                                      }"
                                      class="w-6 h-6 rounded-md flex items-center justify-center font-bold text-xs shrink-0 transition-colors">
                                    {{ $optKey }}
                                </span>
                                <span class="pt-0.5 leading-snug">{{ $optVal }}</span>
                            </button>
                        @endforeach
                    </div>

                    <!-- Evaluation Banner & Action Controls -->
                    <div class="flex items-center justify-between pt-2">
                        
                        <!-- Left Status Pill -->
                        <div>
                            <template x-if="!submitted">
                                <span class="text-[11px] text-slate-400 font-medium">
                                    Click any option to evaluate instantly.
                                </span>
                            </template>

                            <template x-if="submitted">
                                <div class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-lg text-xs font-black"
                                     :class="isCorrect ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : 'bg-rose-100 text-rose-800 border border-rose-300'">
                                    <span x-text="isCorrect ? '✓ Correct Answer!' : '✗ Incorrect. Correct is: Option ' + correctOption"></span>
                                </div>
                            </template>
                        </div>

                        <!-- Solution Toggle Button -->
                        <div>
                            <button type="button" 
                                    x-show="submitted" 
                                    @click="showHint = !showHint"
                                    class="text-xs font-bold text-blue-950 hover:underline inline-flex items-center space-x-1">
                                <span x-text="showHint ? 'Hide Explanation' : 'View Detailed Solution →'"></span>
                            </button>
                        </div>

                    </div>

                    <!-- Detailed Solution Card -->
                    <div x-show="submitted && showHint" x-collapse class="mt-3 pt-3 border-t border-slate-100" style="display:none;">
                        <div class="bg-blue-50/50 p-4 rounded-xl border border-blue-100 space-y-2">
                            <div class="flex items-center space-x-1.5 text-xs font-bold text-blue-950 uppercase tracking-wider">
                                <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                                <span>Detailed Solution &amp; Key Concept:</span>
                            </div>
                            <p class="text-xs sm:text-sm text-slate-700 leading-relaxed font-sans" x-text="explanation || 'No extra notes provided.'"></p>
                        </div>
                    </div>

                </div>
            @empty
                <div class="bg-white rounded-2xl p-12 text-center border border-slate-200 shadow-2xs space-y-3">
                    <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto text-xl">
                        🔍
                    </div>
                    <h3 class="text-sm font-bold text-slate-900">No questions found</h3>
                    <p class="text-xs text-slate-500 max-w-sm mx-auto">
                        No questions match your current filter selection. Try selecting "All Subjects" or clearing search filters.
                    </p>
                    <a href="{{ route('student.practice', ['subject' => 'all']) }}" class="inline-block px-4 py-2 bg-blue-950 text-white rounded-lg text-xs font-bold">
                        Reset All Filters
                    </a>
                </div>
            @endforelse
        </div>

        <!-- Pagination Controls -->
        <div class="bg-white rounded-xl border border-slate-200/90 shadow-2xs p-4 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs">
            <div class="text-slate-500 font-medium">
                Showing <span class="font-bold text-slate-900">{{ $questions->firstItem() ?? 0 }}</span> to <span class="font-bold text-slate-900">{{ $questions->lastItem() ?? 0 }}</span> of <span class="font-bold text-slate-900">{{ $questions->total() }}</span> questions
            </div>

            <div>
                {{ $questions->links() }}
            </div>
        </div>

    </div>
</div>

<script>
function practiceHUD() {
    return {
        attemptedCount: 0,
        correctCount: 0,
        streakCount: 0,
        bookmarks: [],
        onlyBookmarks: false,
        secondsElapsed: 0,
        timerRunning: false,
        timerInterval: null,

        get accuracyRate() {
            if (this.attemptedCount === 0) return 0;
            return Math.round((this.correctCount / this.attemptedCount) * 100);
        },

        get formattedTime() {
            let m = Math.floor(this.secondsElapsed / 60);
            let s = this.secondsElapsed % 60;
            return (m < 10 ? '0' + m : m) + ':' + (s < 10 ? '0' + s : s);
        },

        initHUD() {
            // Load saved bookmarks from localStorage
            try {
                let saved = localStorage.getItem('nirmaan_practice_bookmarks');
                this.bookmarks = saved ? JSON.parse(saved) : [];
            } catch (e) {
                this.bookmarks = [];
            }

            // Note: Stopwatch is initially stationary at 00:00 until student clicks "Start Stopwatch"

            // Listen for evaluated drills (auto-starts timer if user evaluates without clicking start)
            window.addEventListener('drill-evaluated', (e) => {
                if (!this.timerRunning && this.secondsElapsed === 0) {
                    this.startTimer();
                }
                this.attemptedCount++;
                if (e.detail.isCorrect) {
                    this.correctCount++;
                    this.streakCount++;
                } else {
                    this.streakCount = 0;
                }
            });
        },

        startTimer() {
            if (this.timerRunning) return;
            this.timerRunning = true;
            this.timerInterval = setInterval(() => {
                this.secondsElapsed++;
            }, 1000);
        },

        pauseTimer() {
            if (!this.timerRunning) return;
            this.timerRunning = false;
            if (this.timerInterval) {
                clearInterval(this.timerInterval);
                this.timerInterval = null;
            }
        },

        toggleTimer() {
            if (this.timerRunning) {
                this.pauseTimer();
            } else {
                this.startTimer();
            }
        },

        isBookmarked(qId) {
            return this.bookmarks.includes(qId);
        },

        toggleBookmark(qId) {
            if (this.isBookmarked(qId)) {
                this.bookmarks = this.bookmarks.filter(id => id !== qId);
            } else {
                this.bookmarks.push(qId);
            }
            try {
                localStorage.setItem('nirmaan_practice_bookmarks', JSON.stringify(this.bookmarks));
            } catch (e) {}
        },

        resetSessionStats() {
            this.pauseTimer();
            this.attemptedCount = 0;
            this.correctCount = 0;
            this.streakCount = 0;
            this.secondsElapsed = 0;
        }
    };
}
</script>
@endsection
