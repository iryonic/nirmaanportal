@extends('layouts.admin')

@section('title', 'Question Bank Repository — Admin Command')
@section('header_title', 'Question Bank')

@section('content')
<div class="space-y-5">
    
    <!-- Action Bar & Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white rounded-xl p-4 sm:p-5 border border-slate-200/80 shadow-2xs">
        <div>
            <div class="flex items-center space-x-1.5 text-xs text-slate-500 font-medium mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-950">Dashboard</a>
                <span>/</span>
                <span class="text-slate-900 font-semibold">Question Repository</span>
            </div>
            <h1 class="text-lg sm:text-xl font-bold text-slate-900 tracking-tight">National Question Bank Repository</h1>
            <p class="text-xs text-slate-500 mt-0.5">Maintain verified MCQs categorized by subject, topic, positive/negative marking rules, and difficulty tiers.</p>
        </div>
        <div class="flex items-center space-x-2 shrink-0">
            <span class="px-2.5 py-1 rounded-md bg-slate-100 text-slate-700 text-xs font-medium border border-slate-200">
                Total: {{ $questions->total() }}
            </span>
            <a href="{{ route('admin.questions.create') }}" 
               class="px-3.5 py-2 rounded-lg text-xs font-semibold text-white bg-blue-950 hover:bg-blue-900 transition-colors flex items-center space-x-1.5 shadow-2xs">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span>Add Question</span>
            </a>
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="bg-white rounded-xl p-3.5 border border-slate-200/80 shadow-2xs">
        <form action="{{ route('admin.questions.index') }}" method="GET" class="flex flex-wrap items-center gap-2.5">
            
            <!-- Keyword Search -->
            <div class="relative flex-grow min-w-[200px]">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </span>
                <input type="text" name="q" placeholder="Search question text, topic, or solution rationale..." value="{{ request('q') }}" 
                       class="w-full pl-8 pr-3 py-1.5 text-xs bg-slate-50/70 border border-slate-200 rounded-lg outline-none focus:border-blue-950 focus:bg-white focus:ring-1 focus:ring-blue-950/20 transition-colors">
            </div>

            <!-- Subject Filter -->
            <select name="subject" onchange="this.form.submit()" class="text-xs bg-slate-50/70 border border-slate-200 rounded-lg px-3 py-1.5 outline-none focus:border-blue-950 focus:bg-white">
                <option value="">All Academic Subjects</option>
                <option value="General Knowledge" {{ request('subject') === 'General Knowledge' ? 'selected' : '' }}>General Knowledge</option>
                <option value="General Science" {{ request('subject') === 'General Science' ? 'selected' : '' }}>General Science</option>
                <option value="Mathematics" {{ request('subject') === 'Mathematics' ? 'selected' : '' }}>Mathematics</option>
                <option value="Reasoning" {{ request('subject') === 'Reasoning' ? 'selected' : '' }}>Reasoning &amp; Military Aptitude</option>
                <option value="English" {{ request('subject') === 'English' ? 'selected' : '' }}>English Comprehension</option>
                <option value="Physics" {{ request('subject') === 'Physics' ? 'selected' : '' }}>Physics</option>
            </select>

            <!-- Difficulty Filter -->
            <select name="difficulty" onchange="this.form.submit()" class="text-xs bg-slate-50/70 border border-slate-200 rounded-lg px-3 py-1.5 outline-none focus:border-blue-950 focus:bg-white">
                <option value="">All Difficulty Tiers</option>
                <option value="easy" {{ request('difficulty') === 'easy' ? 'selected' : '' }}>Easy Tier</option>
                <option value="medium" {{ request('difficulty') === 'medium' ? 'selected' : '' }}>Medium Tier</option>
                <option value="hard" {{ request('difficulty') === 'hard' ? 'selected' : '' }}>Hard Tier</option>
            </select>

            <button type="submit" class="px-3.5 py-1.5 rounded-lg text-xs font-semibold bg-slate-900 hover:bg-slate-800 text-white transition-colors shadow-2xs">
                Filter
            </button>

            @if(request()->hasAny(['q', 'subject', 'difficulty']))
                <a href="{{ route('admin.questions.index') }}" class="text-xs font-semibold text-slate-500 hover:text-red-600 transition-colors pl-1">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Questions Table -->
    <div class="bg-white rounded-xl border border-slate-200/80 overflow-hidden shadow-2xs">
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50/75 text-slate-500 uppercase text-[10px] font-semibold tracking-wider border-b border-slate-200/80">
                    <tr>
                        <th class="px-4 py-2.5 whitespace-nowrap">Subject &amp; Topic</th>
                        <th class="px-4 py-2.5 whitespace-nowrap">Question Prompt</th>
                        <th class="px-4 py-2.5 whitespace-nowrap">Answer Key</th>
                        <th class="px-4 py-2.5 whitespace-nowrap">Marks (+ / -)</th>
                        <th class="px-4 py-2.5 whitespace-nowrap">Difficulty</th>
                        <th class="px-4 py-2.5 whitespace-nowrap text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($questions as $q)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <!-- Subject & Topic -->
                            <td class="px-4 py-3 whitespace-nowrap font-medium">
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-medium bg-blue-50 text-blue-950 border border-blue-200/60 inline-block">
                                    {{ $q->subject }}
                                </span>
                                @if($q->topic)
                                    <span class="text-[10px] text-slate-400 block mt-0.5 font-mono">{{ $q->topic }}</span>
                                @endif
                            </td>

                            <!-- Question Prompt -->
                            <td class="px-4 py-3 whitespace-nowrap font-medium text-slate-900 max-w-md">
                                <a href="{{ route('admin.questions.edit', $q->id) }}" class="hover:text-blue-950 transition-colors block truncate max-w-md" title="{{ $q->question_text }}">
                                    {{ $q->question_text }}
                                </a>
                            </td>

                            <!-- Correct Option -->
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="w-6 h-6 rounded-md bg-emerald-50 text-emerald-800 font-bold text-xs inline-flex items-center justify-center border border-emerald-200">
                                    {{ $q->correct_option }}
                                </span>
                            </td>

                            <!-- Marks -->
                            <td class="px-4 py-3 whitespace-nowrap font-mono font-medium">
                                <span class="text-emerald-700 font-semibold">+{{ $q->marks }}</span>
                                <span class="text-red-500 text-[10px] ml-1">-{{ $q->negative_marks }}</span>
                            </td>

                            <!-- Difficulty Badge -->
                            <td class="px-4 py-3 whitespace-nowrap">
                                @php
                                    $diffClasses = [
                                        'easy' => 'bg-emerald-50 text-emerald-700 border-emerald-200/60',
                                        'medium' => 'bg-amber-50 text-amber-700 border-amber-200/60',
                                        'hard' => 'bg-red-50 text-red-700 border-red-200/60',
                                    ][$q->difficulty] ?? 'bg-slate-100 text-slate-700 border-slate-200';
                                @endphp
                                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md text-[10px] font-medium uppercase border {{ $diffClasses }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $q->difficulty === 'easy' ? 'bg-emerald-500' : ($q->difficulty === 'hard' ? 'bg-red-500' : 'bg-amber-500') }}"></span>
                                    <span>{{ $q->difficulty }}</span>
                                </span>
                            </td>

                            <!-- Actions -->
                            <td class="px-4 py-3 whitespace-nowrap text-right space-x-1.5">
                                <a href="{{ route('admin.questions.edit', $q->id) }}" class="px-2.5 py-1 rounded-md bg-blue-50 hover:bg-blue-100 text-blue-950 font-medium text-xs transition-colors border border-blue-200/60 shadow-2xs">
                                    Edit
                                </a>
                                <form action="{{ route('admin.questions.destroy', $q->id) }}" method="POST" class="inline" onsubmit="return confirm('Permanently remove this question from question bank?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-2.5 py-1 rounded-md text-xs font-medium text-red-600 hover:bg-red-50 border border-red-200 transition-colors shadow-2xs">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-xs text-slate-400">
                                <svg class="w-8 h-8 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                No questions found in the repository matching your query.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($questions->hasPages())
            <div class="p-3 border-t border-slate-200/80 bg-slate-50/50">
                {{ $questions->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
