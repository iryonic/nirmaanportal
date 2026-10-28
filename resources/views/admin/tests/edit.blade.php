@extends('layouts.admin')

@section('title', 'Edit Mock Test — Admin Command')
@section('header_title', 'Edit Mock Test: ' . $test->title)

@section('content')
<div class="max-w-3xl mx-auto space-y-5">
    
    <!-- Top Action Breadcrumb Bar -->
    <div class="flex items-center justify-between bg-white rounded-xl p-3.5 sm:p-4 border border-slate-200/80 shadow-2xs">
        <div class="flex items-center space-x-1.5 text-xs text-slate-500 font-medium">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-950">Dashboard</a>
            <span>/</span>
            <a href="{{ route('admin.tests.index') }}" class="hover:text-blue-950">Mock Tests</a>
            <span>/</span>
            <span class="text-slate-900 font-semibold truncate max-w-xs">{{ $test->title }}</span>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('admin.tests.index') }}" class="text-xs font-semibold text-slate-600 hover:text-slate-900 transition-colors">
                &larr; Back
            </a>
            <a href="{{ route('student.tests.instructions', $test->slug) }}" target="_blank" class="px-2.5 py-1 rounded-md bg-white hover:bg-slate-50 text-xs font-medium text-slate-700 border border-slate-200 shadow-2xs transition-colors">
                Simulate ↗
            </a>
        </div>
    </div>

    <!-- Edit Form Card -->
    <div class="bg-white rounded-xl p-6 sm:p-8 border border-slate-200/80 shadow-2xs">
        <form action="{{ route('admin.tests.update', $test->id) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Test Title</label>
                    <input type="text" name="title" required value="{{ old('title', $test->title) }}" 
                           class="w-full text-xs sm:text-sm bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Branch Category</label>
                    <select name="exam_category_id" 
                            class="w-full text-xs sm:text-sm bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">
                        <option value="">General</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ $test->exam_category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Duration (Mins)</label>
                    <input type="number" name="duration_minutes" value="{{ old('duration_minutes', $test->duration_minutes) }}" required 
                           class="w-full text-xs font-mono bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Total Marks</label>
                    <input type="number" step="1" name="total_marks" value="{{ old('total_marks', $test->total_marks) }}" required 
                           class="w-full text-xs font-mono bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Pass Marks</label>
                    <input type="number" step="1" name="pass_marks" value="{{ old('pass_marks', $test->pass_marks) }}" required 
                           class="w-full text-xs font-mono bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Negative Marking</label>
                    <input type="number" step="0.05" name="negative_marking_ratio" value="{{ old('negative_marking_ratio', $test->negative_marking_ratio) }}" required 
                           class="w-full text-xs font-mono bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Instructions</label>
                <textarea name="instructions" rows="3" 
                          class="w-full text-xs bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">{{ old('instructions', $test->instructions) }}</textarea>
            </div>

            <!-- Question Picker Checklist -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Select Questions to Include ({{ $questions->count() }} Available)</label>
                <div class="border border-slate-200 rounded-lg max-h-56 overflow-y-auto divide-y divide-slate-100 p-2 bg-slate-50/50">
                    @foreach($questions as $q)
                        <label class="p-2.5 flex items-start space-x-2.5 text-xs hover:bg-white rounded-md cursor-pointer transition-colors">
                            <input type="checkbox" name="question_ids[]" value="{{ $q->id }}" {{ in_array($q->id, $selectedQuestionIds) ? 'checked' : '' }} class="mt-0.5 rounded text-blue-950 focus:ring-blue-950">
                            <div class="min-w-0">
                                <span class="font-medium text-slate-900 block truncate">[{{ $q->subject }}] {{ $q->question_text }}</span>
                                <span class="text-[10px] text-slate-400">Option {{ $q->correct_option }} • +{{ $q->marks }} / -{{ $q->negative_marks }}</span>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="flex items-center space-x-4 text-xs font-medium text-slate-700">
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="checkbox" name="is_free" value="1" {{ $test->is_free ? 'checked' : '' }} class="rounded text-blue-950 focus:ring-blue-950">
                    <span>Free Test</span>
                </label>
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="checkbox" name="is_published" value="1" {{ $test->is_published ? 'checked' : '' }} class="rounded text-blue-950 focus:ring-blue-950">
                    <span>Published</span>
                </label>
            </div>

            <div class="pt-4 flex items-center justify-end space-x-3 border-t border-slate-100">
                <a href="{{ route('admin.tests.index') }}" class="px-4 py-2 rounded-lg text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 border border-slate-200 shadow-2xs transition-colors">
                    Cancel
                </a>
                <button type="submit" class="px-5 py-2 rounded-lg text-xs font-semibold text-white bg-blue-950 hover:bg-blue-900 shadow-2xs transition-colors">
                    Update Mock Test
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
