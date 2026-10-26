@extends('layouts.admin')

@section('title', 'Edit Mock Test — Admin')
@section('header_title', 'Edit Mock Test: ' . $test->title)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    
    <div class="flex items-center justify-between bg-white rounded-2xl p-4 border border-gray-100 shadow-2xs">
        <div class="flex items-center space-x-2 text-xs font-semibold text-gray-500">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-700">HQ Dashboard</a>
            <span>&bull;</span>
            <a href="{{ route('admin.tests.index') }}" class="hover:text-blue-700">Mock Tests</a>
            <span>&bull;</span>
            <span class="text-blue-700 font-bold truncate max-w-xs">{{ $test->title }}</span>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.tests.index') }}" class="text-xs font-bold text-gray-500 hover:text-gray-800">
                ← Back to Tests
            </a>
            <a href="{{ route('student.tests.instructions', $test->slug) }}" target="_blank" class="px-3 py-1.5 rounded-lg bg-gray-50 hover:bg-gray-100 text-xs font-bold text-blue-700 border border-gray-200">
                Live Simulation ↗
            </a>
        </div>
    </div>

    <div class="bg-white rounded-3xl p-6 sm:p-10 border border-gray-100 shadow-xl">
        <form action="{{ route('admin.tests.update', $test->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Test Title</label>
                    <input type="text" name="title" required value="{{ old('title', $test->title) }}" class="w-full text-xs sm:text-sm bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 outline-none focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Branch Category</label>
                    <select name="exam_category_id" class="w-full text-xs sm:text-sm bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 outline-none focus:border-blue-500">
                        <option value="">General</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ $test->exam_category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Duration (Mins)</label>
                    <input type="number" name="duration_minutes" value="{{ old('duration_minutes', $test->duration_minutes) }}" required class="w-full text-xs bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Total Marks</label>
                    <input type="number" step="1" name="total_marks" value="{{ old('total_marks', $test->total_marks) }}" required class="w-full text-xs bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Pass Marks</label>
                    <input type="number" step="1" name="pass_marks" value="{{ old('pass_marks', $test->pass_marks) }}" required class="w-full text-xs bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Negative Marking</label>
                    <input type="number" step="0.05" name="negative_marking_ratio" value="{{ old('negative_marking_ratio', $test->negative_marking_ratio) }}" required class="w-full text-xs bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Instructions</label>
                <textarea name="instructions" rows="3" class="w-full text-xs bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 outline-none">{{ old('instructions', $test->instructions) }}</textarea>
            </div>

            <!-- Question Picker Checklist -->
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-2">Select Questions to Include ({{ $questions->count() }} Available)</label>
                <div class="border border-gray-200 rounded-2xl max-h-60 overflow-y-auto divide-y divide-gray-100 p-2 bg-gray-50/50">
                    @foreach($questions as $q)
                        <label class="p-3 flex items-start space-x-3 text-xs hover:bg-white rounded-xl cursor-pointer transition">
                            <input type="checkbox" name="question_ids[]" value="{{ $q->id }}" {{ in_array($q->id, $selectedQuestionIds) ? 'checked' : '' }} class="mt-0.5 rounded text-blue-600 focus:ring-blue-500">
                            <div>
                                <span class="font-bold text-gray-900 block">[{{ $q->subject }}] {{ $q->question_text }}</span>
                                <span class="text-[10px] text-gray-500">Option {{ $q->correct_option }} • +{{ $q->marks }} / -{{ $q->negative_marks }}</span>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="flex items-center space-x-4 text-xs font-semibold">
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="is_free" value="1" {{ $test->is_free ? 'checked' : '' }} class="rounded text-blue-600">
                    <span>Free Test</span>
                </label>
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="is_published" value="1" {{ $test->is_published ? 'checked' : '' }} class="rounded text-blue-600">
                    <span>Published</span>
                </label>
            </div>

            <div>
                <button type="submit" class="px-8 py-3 rounded-full text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-md transition">
                    Update Mock Test
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
