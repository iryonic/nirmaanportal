@extends('layouts.admin')

@section('title', 'Edit Question — Admin Command')
@section('header_title', 'Edit Question #' . $question->id)

@section('content')
<div class="max-w-3xl mx-auto space-y-5">
    
    <!-- Top Action Breadcrumb Bar -->
    <div class="flex items-center justify-between bg-white rounded-xl p-3.5 sm:p-4 border border-slate-200/80 shadow-2xs">
        <div class="flex items-center space-x-1.5 text-xs text-slate-500 font-medium">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-950">Dashboard</a>
            <span>/</span>
            <a href="{{ route('admin.questions.index') }}" class="hover:text-blue-950">Question Bank</a>
            <span>/</span>
            <span class="text-slate-900 font-semibold">Edit #{{ $question->id }}</span>
        </div>
        <a href="{{ route('admin.questions.index') }}" class="text-xs font-semibold text-slate-600 hover:text-slate-900 transition-colors">
            &larr; Back to Questions
        </a>
    </div>

    <!-- Question Form Card -->
    <div class="bg-white rounded-xl p-6 sm:p-8 border border-slate-200/80 shadow-2xs">
        <form action="{{ route('admin.questions.update', $question->id) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Subject <span class="text-red-500">*</span></label>
                    <select name="subject" required 
                            class="w-full text-xs sm:text-sm bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">
                        @foreach(['General Knowledge', 'General Science', 'Mathematics', 'Reasoning', 'English', 'Physics'] as $sub)
                            <option value="{{ $sub }}" {{ $question->subject === $sub ? 'selected' : '' }}>{{ $sub }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Topic</label>
                    <input type="text" name="topic" value="{{ old('topic', $question->topic) }}" 
                           class="w-full text-xs sm:text-sm bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Difficulty <span class="text-red-500">*</span></label>
                    <select name="difficulty" required 
                            class="w-full text-xs sm:text-sm bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">
                        <option value="easy" {{ $question->difficulty === 'easy' ? 'selected' : '' }}>Easy Tier</option>
                        <option value="medium" {{ $question->difficulty === 'medium' ? 'selected' : '' }}>Medium Tier</option>
                        <option value="hard" {{ $question->difficulty === 'hard' ? 'selected' : '' }}>Hard Tier</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Question Statement <span class="text-red-500">*</span></label>
                <textarea name="question_text" rows="3" required 
                          class="w-full text-xs sm:text-sm bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">{{ old('question_text', $question->question_text) }}</textarea>
            </div>

            <!-- Options -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Option A <span class="text-red-500">*</span></label>
                    <input type="text" name="option_a" required value="{{ old('option_a', $question->option_a) }}" 
                           class="w-full text-xs bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Option B <span class="text-red-500">*</span></label>
                    <input type="text" name="option_b" required value="{{ old('option_b', $question->option_b) }}" 
                           class="w-full text-xs bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Option C <span class="text-red-500">*</span></label>
                    <input type="text" name="option_c" required value="{{ old('option_c', $question->option_c) }}" 
                           class="w-full text-xs bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Option D <span class="text-red-500">*</span></label>
                    <input type="text" name="option_d" required value="{{ old('option_d', $question->option_d) }}" 
                           class="w-full text-xs bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Correct Option <span class="text-red-500">*</span></label>
                    <select name="correct_option" required 
                            class="w-full text-xs sm:text-sm bg-emerald-50 border border-emerald-200 text-emerald-900 font-semibold rounded-lg px-3.5 py-2 outline-none focus:ring-1 focus:ring-emerald-500">
                        @foreach(['A', 'B', 'C', 'D'] as $opt)
                            <option value="{{ $opt }}" {{ $question->correct_option === $opt ? 'selected' : '' }}>Option {{ $opt }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Marks (+)</label>
                    <input type="number" step="0.25" name="marks" value="{{ old('marks', $question->marks) }}" required 
                           class="w-full text-xs font-mono bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Negative Marks (-)</label>
                    <input type="number" step="0.25" name="negative_marks" value="{{ old('negative_marks', $question->negative_marks) }}" required 
                           class="w-full text-xs font-mono bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Detailed Explanation / Rationale</label>
                <textarea name="explanation" rows="3" 
                          class="w-full text-xs sm:text-sm bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">{{ old('explanation', $question->explanation) }}</textarea>
            </div>

            <div class="pt-4 flex items-center justify-end space-x-3 border-t border-slate-100">
                <a href="{{ route('admin.questions.index') }}" class="px-4 py-2 rounded-lg text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 border border-slate-200 shadow-2xs transition-colors">
                    Cancel
                </a>
                <button type="submit" class="px-5 py-2 rounded-lg text-xs font-semibold text-white bg-blue-950 hover:bg-blue-900 shadow-2xs transition-colors">
                    Update Question
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
