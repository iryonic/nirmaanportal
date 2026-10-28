@extends('layouts.admin')

@section('title', 'Create Mock Test — Admin Command')
@section('header_title', ' New Mock Test')

@section('content')
<div class="max-w-3xl mx-auto space-y-5">
    
    <!-- Top Action Breadcrumb Bar -->
    <div class="flex items-center justify-between bg-white rounded-xl p-3.5 sm:p-4 border border-slate-200/80 shadow-2xs">
        <div class="flex items-center space-x-1.5 text-xs text-slate-500 font-medium">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-950">Dashboard</a>
            <span>/</span>
            <a href="{{ route('admin.tests.index') }}" class="hover:text-blue-950">Mock Tests</a>
            <span>/</span>
            <span class="text-slate-900 font-semibold">New Blueprint</span>
        </div>
        <a href="{{ route('admin.tests.index') }}" class="text-xs font-semibold text-slate-600 hover:text-slate-900 transition-colors">
            &larr; Back to Tests
        </a>
    </div>

    <!-- Creation Form Card -->
    <div class="bg-white rounded-xl p-6 sm:p-8 border border-slate-200/80 shadow-2xs">
        <form action="{{ route('admin.tests.store') }}" method="POST" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Test Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" required value="{{ old('title') }}" placeholder="e.g. Indian Army Agniveer GD Full Mock Test 02" 
                           class="w-full text-xs sm:text-sm bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 placeholder:text-slate-400 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">
                    @error('title')<span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>@enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Exam Category</label>
                    <select name="exam_category_id" 
                            class="w-full text-xs sm:text-sm bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">
                        <option value="">General Defence</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Duration (Mins) <span class="text-red-500">*</span></label>
                    <input type="number" name="duration_minutes" required value="{{ old('duration_minutes', 60) }}" 
                           class="w-full text-xs font-mono bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Total Marks <span class="text-red-500">*</span></label>
                    <input type="number" step="1" name="total_marks" required value="{{ old('total_marks', 100) }}" 
                           class="w-full text-xs font-mono bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Pass Marks <span class="text-red-500">*</span></label>
                    <input type="number" step="1" name="pass_marks" required value="{{ old('pass_marks', 35) }}" 
                           class="w-full text-xs font-mono bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Negative Marking <span class="text-red-500">*</span></label>
                    <input type="number" step="0.05" name="negative_marking_ratio" required value="0.25" 
                           class="w-full text-xs font-mono bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Instructions / Advisory</label>
                <textarea name="instructions" rows="3" 
                          class="w-full text-xs bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">1. Each correct answer carries marks as configured.&#10;2. Negative marking is applicable for incorrect attempts.&#10;3. Review answers before final submission.</textarea>
            </div>

            <!-- Question Picker Checklist -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Select Questions to Include ({{ $questions->count() }} Available) <span class="text-red-500">*</span></label>
                @error('question_ids')<span class="text-xs text-red-500 mb-2 block">{{ $message }}</span>@enderror
                
                <div class="border border-slate-200 rounded-lg max-h-56 overflow-y-auto divide-y divide-slate-100 p-2 bg-slate-50/50">
                    @foreach($questions as $q)
                        <label class="p-2.5 flex items-start space-x-2.5 text-xs hover:bg-white rounded-md cursor-pointer transition-colors">
                            <input type="checkbox" name="question_ids[]" value="{{ $q->id }}" checked class="mt-0.5 rounded text-blue-950 focus:ring-blue-950">
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
                    <input type="checkbox" name="is_free" value="1" checked class="rounded text-blue-950 focus:ring-blue-950">
                    <span>Free Test for All Cadets</span>
                </label>
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="checkbox" name="is_published" value="1" checked class="rounded text-blue-950 focus:ring-blue-950">
                    <span>Publish Immediately</span>
                </label>
            </div>

            <div class="pt-4 flex items-center justify-end space-x-3 border-t border-slate-100">
                <a href="{{ route('admin.tests.index') }}" class="px-4 py-2 rounded-lg text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 border border-slate-200 shadow-2xs transition-colors">
                    Cancel
                </a>
                <button type="submit" class="px-5 py-2 rounded-lg text-xs font-semibold text-white bg-blue-950 hover:bg-blue-900 shadow-2xs transition-colors">
                    Launch Mock Test
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
