@extends('layouts.admin')

@section('title', 'Add Question — Admin')
@section('header_title', 'Add Question to Question Bank')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    
    <div>
        <a href="{{ route('admin.questions.index') }}" class="text-xs font-bold text-blue-700 hover:underline">
            ← Back to Questions
        </a>
        <h1 class="text-xl font-bold text-gray-900 mt-2">New Objective Question</h1>
    </div>

    <div class="bg-white rounded-3xl p-6 sm:p-10 border border-gray-100 shadow-xl">
        <form action="{{ route('admin.questions.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Subject *</label>
                    <select name="subject" required class="w-full text-xs sm:text-sm bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 outline-none focus:border-blue-500">
                        <option value="General Knowledge">General Knowledge</option>
                        <option value="General Science">General Science</option>
                        <option value="Mathematics">Mathematics</option>
                        <option value="Reasoning">Reasoning</option>
                        <option value="English">English</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Topic</label>
                    <input type="text" name="topic" placeholder="e.g. Indian Defence Commands" class="w-full text-xs sm:text-sm bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 outline-none focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Difficulty *</label>
                    <select name="difficulty" required class="w-full text-xs sm:text-sm bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 outline-none focus:border-blue-500">
                        <option value="easy">Easy</option>
                        <option value="medium" selected>Medium</option>
                        <option value="hard">Hard</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Question Statement *</label>
                <textarea name="question_text" rows="3" required placeholder="Type the question statement clearly..." class="w-full text-xs sm:text-sm bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 outline-none focus:border-blue-500">{{ old('question_text') }}</textarea>
                @error('question_text')<span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>@enderror
            </div>

            <!-- 4 Options -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Option A *</label>
                    <input type="text" name="option_a" required value="{{ old('option_a') }}" class="w-full text-xs bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Option B *</label>
                    <input type="text" name="option_b" required value="{{ old('option_b') }}" class="w-full text-xs bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Option C *</label>
                    <input type="text" name="option_c" required value="{{ old('option_c') }}" class="w-full text-xs bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Option D *</label>
                    <input type="text" name="option_d" required value="{{ old('option_d') }}" class="w-full text-xs bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 outline-none focus:border-blue-500">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Correct Answer *</label>
                    <select name="correct_option" required class="w-full text-xs sm:text-sm bg-emerald-50 border border-emerald-200 text-emerald-900 font-extrabold rounded-xl px-4 py-2.5 outline-none">
                        <option value="A">Option A</option>
                        <option value="B">Option B</option>
                        <option value="C">Option C</option>
                        <option value="D">Option D</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Marks for Correct (+)</label>
                    <input type="number" step="0.25" name="marks" value="2.00" required class="w-full text-xs bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Negative Marks Penalty (-)</label>
                    <input type="number" step="0.25" name="negative_marks" value="0.50" required class="w-full text-xs bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Detailed Explanation / Solution</label>
                <textarea name="explanation" rows="3" placeholder="Provide background theory or calculation steps..." class="w-full text-xs sm:text-sm bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 outline-none focus:border-blue-500">{{ old('explanation') }}</textarea>
            </div>

            <div>
                <button type="submit" class="px-8 py-3 rounded-full text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-md transition">
                    Save to Question Bank
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
