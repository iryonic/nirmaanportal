@extends('layouts.admin')

@section('title', 'Edit Question — Admin')
@section('header_title', 'Edit Question #' . $question->id)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    
    <div>
        <a href="{{ route('admin.questions.index') }}" class="text-xs font-bold text-blue-700 hover:underline">
            ← Back to Questions
        </a>
        <h1 class="text-xl font-bold text-gray-900 mt-2">Edit Question</h1>
    </div>

    <div class="bg-white rounded-3xl p-6 sm:p-10 border border-gray-100 shadow-xl">
        <form action="{{ route('admin.questions.update', $question->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Subject</label>
                    <select name="subject" required class="w-full text-xs sm:text-sm bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 outline-none focus:border-blue-500">
                        @foreach(['General Knowledge', 'General Science', 'Mathematics', 'Reasoning', 'English'] as $sub)
                            <option value="{{ $sub }}" {{ $question->subject === $sub ? 'selected' : '' }}>{{ $sub }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Topic</label>
                    <input type="text" name="topic" value="{{ old('topic', $question->topic) }}" class="w-full text-xs sm:text-sm bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 outline-none focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Difficulty</label>
                    <select name="difficulty" required class="w-full text-xs sm:text-sm bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 outline-none focus:border-blue-500">
                        <option value="easy" {{ $question->difficulty === 'easy' ? 'selected' : '' }}>Easy</option>
                        <option value="medium" {{ $question->difficulty === 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="hard" {{ $question->difficulty === 'hard' ? 'selected' : '' }}>Hard</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Question Statement</label>
                <textarea name="question_text" rows="3" required class="w-full text-xs sm:text-sm bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 outline-none focus:border-blue-500">{{ old('question_text', $question->question_text) }}</textarea>
            </div>

            <!-- Options -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Option A</label>
                    <input type="text" name="option_a" required value="{{ old('option_a', $question->option_a) }}" class="w-full text-xs bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Option B</label>
                    <input type="text" name="option_b" required value="{{ old('option_b', $question->option_b) }}" class="w-full text-xs bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Option C</label>
                    <input type="text" name="option_c" required value="{{ old('option_c', $question->option_c) }}" class="w-full text-xs bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Option D</label>
                    <input type="text" name="option_d" required value="{{ old('option_d', $question->option_d) }}" class="w-full text-xs bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 outline-none">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Correct Option</label>
                    <select name="correct_option" required class="w-full text-xs sm:text-sm bg-emerald-50 border border-emerald-200 text-emerald-900 font-extrabold rounded-xl px-4 py-2.5 outline-none">
                        @foreach(['A', 'B', 'C', 'D'] as $opt)
                            <option value="{{ $opt }}" {{ $question->correct_option === $opt ? 'selected' : '' }}>Option {{ $opt }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Marks</label>
                    <input type="number" step="0.25" name="marks" value="{{ old('marks', $question->marks) }}" required class="w-full text-xs bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Negative Marks</label>
                    <input type="number" step="0.25" name="negative_marks" value="{{ old('negative_marks', $question->negative_marks) }}" required class="w-full text-xs bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Explanation</label>
                <textarea name="explanation" rows="3" class="w-full text-xs sm:text-sm bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 outline-none focus:border-blue-500">{{ old('explanation', $question->explanation) }}</textarea>
            </div>

            <div>
                <button type="submit" class="px-8 py-3 rounded-full text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-md transition">
                    Update Question
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
