@extends('layouts.admin')

@section('title', 'Question Bank Repository — Admin Command')
@section('header_title', 'National Question Bank')

@section('content')
<div class="space-y-6">
    
    <!-- Action Bar & Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white rounded-2xl p-5 sm:p-6 border border-gray-200 shadow-2xs">
        <div>
            <div class="flex items-center space-x-2 text-xs text-gray-400 font-semibold mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-700">HQ Dashboard</a>
                <span>&bull;</span>
                <span class="text-blue-700">Question Repository</span>
            </div>
            <h1 class="text-xl font-bold text-gray-900 tracking-tight">National Question Bank Repository</h1>
            <p class="text-xs text-gray-500 mt-0.5">Maintain verified MCQs categorized by subject, topic, positive/negative marking rules, and difficulty tiers.</p>
        </div>
        <div class="flex items-center space-x-2.5">
            <span class="px-3 py-1.5 rounded-xl bg-blue-50 text-blue-800 text-xs font-bold border border-blue-200">
                Total MCQs: {{ $questions->total() }}
            </span>
            <a href="{{ route('admin.questions.create') }}" 
               class="px-4 py-2.5 rounded-xl text-xs font-bold text-white bg-blue-700 hover:bg-blue-800 transition flex items-center space-x-2 shrink-0 shadow-xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span>+ Add Question</span>
            </a>
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="bg-white rounded-2xl p-4 border border-gray-200 shadow-2xs">
        <form action="{{ route('admin.questions.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
            
            <!-- Keyword Search -->
            <div class="relative flex-grow min-w-[200px]">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </span>
                <input type="text" name="q" placeholder="Search question text, topic, or solution rationale..." value="{{ request('q') }}" 
                       class="w-full pl-9 pr-4 py-2.5 text-xs bg-gray-50 border border-gray-300 rounded-xl outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white focus:border-transparent transition">
            </div>

            <!-- Subject Filter -->
            <select name="subject" onchange="this.form.submit()" class="text-xs bg-gray-50 border border-gray-300 rounded-xl px-3.5 py-2.5 outline-none focus:ring-2 focus:ring-blue-600">
                <option value="">All Academic Subjects</option>
                <option value="General Knowledge" {{ request('subject') === 'General Knowledge' ? 'selected' : '' }}>General Knowledge</option>
                <option value="General Science" {{ request('subject') === 'General Science' ? 'selected' : '' }}>General Science</option>
                <option value="Mathematics" {{ request('subject') === 'Mathematics' ? 'selected' : '' }}>Mathematics</option>
                <option value="Reasoning" {{ request('subject') === 'Reasoning' ? 'selected' : '' }}>Reasoning &amp; Military Aptitude</option>
                <option value="English" {{ request('subject') === 'English' ? 'selected' : '' }}>English Comprehension</option>
                <option value="Physics" {{ request('subject') === 'Physics' ? 'selected' : '' }}>Physics</option>
            </select>

            <!-- Difficulty Filter -->
            <select name="difficulty" onchange="this.form.submit()" class="text-xs bg-gray-50 border border-gray-300 rounded-xl px-3.5 py-2.5 outline-none focus:ring-2 focus:ring-blue-600">
                <option value="">All Difficulty Tiers</option>
                <option value="easy" {{ request('difficulty') === 'easy' ? 'selected' : '' }}>Easy Tier</option>
                <option value="medium" {{ request('difficulty') === 'medium' ? 'selected' : '' }}>Medium Tier</option>
                <option value="hard" {{ request('difficulty') === 'hard' ? 'selected' : '' }}>Hard Tier</option>
            </select>

            <button type="submit" class="px-4 py-2.5 rounded-xl text-xs font-bold bg-slate-900 hover:bg-slate-800 text-white transition shadow-2xs">
                Filter Repository
            </button>

            @if(request()->hasAny(['q', 'subject', 'difficulty']))
                <a href="{{ route('admin.questions.index') }}" class="text-xs font-bold text-gray-500 hover:text-red-600 transition pl-1">
                    Clear Filters ✕
                </a>
            @endif
        </form>
    </div>

    <!-- Questions Table with strict whitespace-nowrap -->
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-2xs">
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left text-xs text-gray-600">
                <thead class="bg-gray-50 text-gray-500 uppercase text-[10px] font-bold border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 whitespace-nowrap">Subject &amp; Topic</th>
                        <th class="px-6 py-4 whitespace-nowrap">Question Prompt</th>
                        <th class="px-6 py-4 whitespace-nowrap">Answer Key</th>
                        <th class="px-6 py-4 whitespace-nowrap">Scoring (+ / -)</th>
                        <th class="px-6 py-4 whitespace-nowrap">Difficulty</th>
                        <th class="px-6 py-4 whitespace-nowrap text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($questions as $q)
                        <tr class="hover:bg-gray-50/70 transition">
                            <!-- Subject & Topic -->
                            <td class="px-6 py-4 whitespace-nowrap font-medium">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-800 border border-blue-200 inline-block">
                                    {{ $q->subject }}
                                </span>
                                @if($q->topic)
                                    <span class="text-[11px] text-gray-400 block mt-1 font-mono">{{ $q->topic }}</span>
                                @endif
                            </td>

                            <!-- Question Prompt -->
                            <td class="px-6 py-4 whitespace-nowrap font-bold text-gray-900 max-w-md">
                                <a href="{{ route('admin.questions.edit', $q->id) }}" class="hover:text-blue-700 transition block truncate max-w-md" title="{{ $q->question_text }}">
                                    {{ $q->question_text }}
                                </a>
                            </td>

                            <!-- Correct Option -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-800 font-bold text-xs inline-flex items-center justify-center border border-emerald-300">
                                    {{ $q->correct_option }}
                                </span>
                            </td>

                            <!-- Marks -->
                            <td class="px-6 py-4 whitespace-nowrap font-mono font-bold">
                                <span class="text-emerald-700">+{{ $q->marks }}</span>
                                <span class="text-red-500 text-[11px] ml-1">-{{ $q->negative_marks }}</span>
                            </td>

                            <!-- Difficulty Badge -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $diffClasses = [
                                        'easy' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
                                        'medium' => 'bg-amber-50 text-amber-800 border-amber-200',
                                        'hard' => 'bg-red-50 text-red-800 border-red-200',
                                    ][$q->difficulty] ?? 'bg-gray-100 text-gray-700 border-gray-200';
                                @endphp
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase border {{ $diffClasses }}">
                                    {{ $q->difficulty }}
                                </span>
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4 whitespace-nowrap text-right space-x-2 font-bold">
                                <a href="{{ route('admin.questions.edit', $q->id) }}" class="px-3 py-1.5 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-700 transition">
                                    Edit
                                </a>
                                <form action="{{ route('admin.questions.destroy', $q->id) }}" method="POST" class="inline" onsubmit="return confirm('Permanently remove this question from question bank?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-2.5 py-1.5 rounded-lg text-xs font-semibold text-red-600 hover:bg-red-50 border border-red-200 transition">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-xs text-gray-400">
                                <svg class="w-10 h-10 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                No questions found in the repository matching your query.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($questions->hasPages())
            <div class="p-4 border-t border-gray-200 bg-gray-50/50">
                {{ $questions->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
