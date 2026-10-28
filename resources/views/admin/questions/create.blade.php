@extends('layouts.admin')

@section('title', 'Add Question — Admin Command')
@section('header_title', 'Add Question ')

@section('content')
<div class="max-w-3xl mx-auto space-y-5">
    
    <!-- Top Action Breadcrumb Bar -->
    <div class="flex items-center justify-between bg-white rounded-xl p-3.5 sm:p-4 border border-slate-200/80 shadow-2xs">
        <div class="flex items-center space-x-1.5 text-xs text-slate-500 font-medium">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-950">Dashboard</a>
            <span>/</span>
            <a href="{{ route('admin.questions.index') }}" class="hover:text-blue-950">Question Bank</a>
            <span>/</span>
            <span class="text-slate-900 font-semibold">New Question</span>
        </div>
        <a href="{{ route('admin.questions.index') }}" class="text-xs font-semibold text-slate-600 hover:text-slate-900 transition-colors">
            &larr; Back to Questions
        </a>
    </div>

    <!-- Question Form Card -->
    <div class="bg-white rounded-xl p-6 sm:p-8 border border-slate-200/80 shadow-2xs">
        <form action="{{ route('admin.questions.store') }}" method="POST" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Subject <span class="text-red-500">*</span></label>
                    <select name="subject" required 
                            class="w-full text-xs sm:text-sm bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">
                        <option value="General Knowledge">General Knowledge</option>
                        <option value="General Science">General Science</option>
                        <option value="Mathematics">Mathematics</option>
                        <option value="Reasoning">Reasoning</option>
                        <option value="English">English</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Topic</label>
                    <input type="text" name="topic" placeholder="e.g. Indian Defence Commands" 
                           class="w-full text-xs sm:text-sm bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 placeholder:text-slate-400 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Difficulty <span class="text-red-500">*</span></label>
                    <select name="difficulty" required 
                            class="w-full text-xs sm:text-sm bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">
                        <option value="easy">Easy Tier</option>
                        <option value="medium" selected>Medium Tier</option>
                        <option value="hard">Hard Tier</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Question Statement <span class="text-red-500">*</span></label>
                <textarea name="question_text" rows="3" required placeholder="Type the question prompt clearly..." 
                          class="w-full text-xs sm:text-sm bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 placeholder:text-slate-400 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">{{ old('question_text') }}</textarea>
                @error('question_text')<span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>@enderror
            </div>

            <!-- 4 Options -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Option A <span class="text-red-500">*</span></label>
                    <input type="text" name="option_a" required value="{{ old('option_a') }}" 
                           class="w-full text-xs bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Option B <span class="text-red-500">*</span></label>
                    <input type="text" name="option_b" required value="{{ old('option_b') }}" 
                           class="w-full text-xs bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Option C <span class="text-red-500">*</span></label>
                    <input type="text" name="option_c" required value="{{ old('option_c') }}" 
                           class="w-full text-xs bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Option D <span class="text-red-500">*</span></label>
                    <input type="text" name="option_d" required value="{{ old('option_d') }}" 
                           class="w-full text-xs bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Correct Option <span class="text-red-500">*</span></label>
                    <select name="correct_option" required 
                            class="w-full text-xs sm:text-sm bg-emerald-50 border border-emerald-200 text-emerald-900 font-semibold rounded-lg px-3.5 py-2 outline-none focus:ring-1 focus:ring-emerald-500">
                        <option value="A">Option A</option>
                        <option value="B">Option B</option>
                        <option value="C">Option C</option>
                        <option value="D">Option D</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Marks (+)</label>
                    <input type="number" step="0.25" name="marks" value="2.00" required 
                           class="w-full text-xs font-mono bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Negative Marks (-)</label>
                    <input type="number" step="0.25" name="negative_marks" value="0.50" required 
                           class="w-full text-xs font-mono bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Detailed Explanation / Rationale</label>
                <textarea name="explanation" rows="3" placeholder="Provide background theory or calculation steps..." 
                          class="w-full text-xs sm:text-sm bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 placeholder:text-slate-400 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">{{ old('explanation') }}</textarea>
            </div>

            <div class="pt-4 flex items-center justify-end space-x-3 border-t border-slate-100">
                <a href="{{ route('admin.questions.index') }}" class="px-4 py-2 rounded-lg text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 border border-slate-200 shadow-2xs transition-colors">
                    Cancel
                </a>
                <button type="submit" class="px-5 py-2 rounded-lg text-xs font-semibold text-white bg-blue-950 hover:bg-blue-900 shadow-2xs transition-colors">
                    Save to Question Bank
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
