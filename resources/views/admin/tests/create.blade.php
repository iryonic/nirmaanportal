@extends('layouts.admin')

@section('title', 'Create Mock Test — Admin')
@section('header_title', 'Configure New Mock Examination')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    
    <div>
        <a href="{{ route('admin.tests.index') }}" class="text-xs font-bold text-blue-700 hover:underline">
            ← Back to Tests
        </a>
        <h1 class="text-xl font-bold text-gray-900 mt-2">New Examination Blueprint</h1>
    </div>

    <div class="bg-white rounded-3xl p-6 sm:p-10 border border-gray-100 shadow-xl">
        <form action="{{ route('admin.tests.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Test Title *</label>
                    <input type="text" name="title" required value="{{ old('title') }}" placeholder="e.g. Indian Army Agniveer GD Full Mock Test 02" class="w-full text-xs sm:text-sm bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 outline-none focus:border-blue-500">
                    @error('title')<span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>@enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Exam Branch Category</label>
                    <select name="exam_category_id" class="w-full text-xs sm:text-sm bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 outline-none focus:border-blue-500">
                        <option value="">General Defence</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Duration (Minutes) *</label>
                    <input type="number" name="duration_minutes" required value="{{ old('duration_minutes', 60) }}" class="w-full text-xs bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Total Marks *</label>
                    <input type="number" step="1" name="total_marks" required value="{{ old('total_marks', 100) }}" class="w-full text-xs bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Pass Marks *</label>
                    <input type="number" step="1" name="pass_marks" required value="{{ old('pass_marks', 35) }}" class="w-full text-xs bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Negative Marking Ratio *</label>
                    <input type="number" step="0.05" name="negative_marking_ratio" required value="0.25" class="w-full text-xs bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Instructions / Advisory</label>
                <textarea name="instructions" rows="3" class="w-full text-xs bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 outline-none focus:border-blue-500">1. Each correct answer carries marks as configured.&#10;2. Negative marking is applicable for incorrect attempts.&#10;3. Review answers before final submission.</textarea>
            </div>

            <!-- Question Picker Checklist -->
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-2">Select Questions to Include ({{ $questions->count() }} Available) *</label>
                @error('question_ids')<span class="text-xs text-red-500 mb-2 block">{{ $message }}</span>@enderror
                
                <div class="border border-gray-200 rounded-2xl max-h-60 overflow-y-auto divide-y divide-gray-100 p-2 bg-gray-50/50">
                    @foreach($questions as $q)
                        <label class="p-3 flex items-start space-x-3 text-xs hover:bg-white rounded-xl cursor-pointer transition">
                            <input type="checkbox" name="question_ids[]" value="{{ $q->id }}" checked class="mt-0.5 rounded text-blue-600 focus:ring-blue-500">
                            <div>
                                <span class="font-bold text-gray-900 block">[{{ $q->subject }}] {{ $q->question_text }}</span>
                                <span class="text-[10px] text-gray-500">Correct: Option {{ $q->correct_option }} • +{{ $q->marks }} / -{{ $q->negative_marks }}</span>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="flex items-center space-x-4 text-xs font-semibold">
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="is_free" value="1" checked class="rounded text-blue-600">
                    <span>Free Test for All Students</span>
                </label>
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="is_published" value="1" checked class="rounded text-blue-600">
                    <span>Publish Immediately</span>
                </label>
            </div>

            <div>
                <button type="submit" class="px-8 py-3 rounded-full text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-md transition">
                    Launch Mock Test
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
