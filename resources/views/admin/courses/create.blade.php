@extends('layouts.admin')

@section('title', 'Create Course Batch — Admin Command')
@section('header_title', 'Course')

@section('content')
<div class="max-w-3xl mx-auto space-y-5">
    
    <!-- Top Action Breadcrumb Bar -->
    <div class="flex items-center justify-between bg-white rounded-xl p-3.5 sm:p-4 border border-slate-200/80 shadow-2xs">
        <div class="flex items-center space-x-1.5 text-xs text-slate-500 font-medium">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-950">Dashboard</a>
            <span>/</span>
            <a href="{{ route('admin.courses.index') }}" class="hover:text-blue-950">Courses</a>
            <span>/</span>
            <span class="text-slate-900 font-semibold">New Batch</span>
        </div>
        <a href="{{ route('admin.courses.index') }}" class="text-xs font-semibold text-slate-600 hover:text-slate-900 transition-colors">
            &larr; Back to Courses
        </a>
    </div>

    <!-- Creation Form Card -->
    <div class="bg-white rounded-xl p-6 sm:p-8 border border-slate-200/80 shadow-2xs">
        <form action="{{ route('admin.courses.store') }}" method="POST" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Course Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" required value="{{ old('title') }}" placeholder="e.g. Indian Army Agniveer GD Full Batch" 
                           class="w-full text-xs sm:text-sm bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 placeholder:text-slate-400 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">
                    @error('title')<span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>@enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Exam Category <span class="text-red-500">*</span></label>
                    <select name="exam_category_id" required 
                            class="w-full text-xs sm:text-sm bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">
                        <option value="">Select Category</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('exam_category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }} ({{ $cat->code }})
                            </option>
                        @endforeach
                    </select>
                    @error('exam_category_id')<span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>@enderror
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Subtitle / Target Trades</label>
                <input type="text" name="subtitle" value="{{ old('subtitle') }}" placeholder="e.g. Agniveer / Soldier / Clerk / Technical" 
                       class="w-full text-xs sm:text-sm bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 placeholder:text-slate-400 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Course Description <span class="text-red-500">*</span></label>
                <textarea name="description" rows="4" required 
                          class="w-full text-xs sm:text-sm bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 placeholder:text-slate-400 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">{{ old('description') }}</textarea>
                @error('description')<span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>@enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Enrollment Fee (₹) <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" name="price" required value="{{ old('price', 2999) }}" 
                           class="w-full text-xs sm:text-sm font-mono bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">
                    @error('price')<span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>@enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Original Price (₹)</label>
                    <input type="number" step="0.01" name="original_price" value="{{ old('original_price', 4999) }}" 
                           class="w-full text-xs sm:text-sm font-mono bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Duration Text</label>
                    <input type="text" name="duration_text" value="{{ old('duration_text', '6 Months Validity') }}" 
                           class="w-full text-xs sm:text-sm bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Chief Instructor / Faculty Name</label>
                <input type="text" name="instructor_name" value="{{ old('instructor_name', 'Ex-Defence Officers & Senior Faculty') }}" 
                       class="w-full text-xs sm:text-sm bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">
            </div>

            <!-- Features Checkboxes -->
            <div class="pt-4 border-t border-slate-100 grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs font-medium text-slate-700">
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="checkbox" name="has_live_classes" value="1" checked class="rounded text-blue-950 focus:ring-blue-950">
                    <span>Live Classes</span>
                </label>
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="checkbox" name="has_mock_tests" value="1" checked class="rounded text-blue-950 focus:ring-blue-950">
                    <span>Mock Tests</span>
                </label>
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="checkbox" name="has_study_material" value="1" checked class="rounded text-blue-950 focus:ring-blue-950">
                    <span>Study Material</span>
                </label>
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="checkbox" name="is_published" value="1" checked class="rounded text-blue-950 focus:ring-blue-950">
                    <span>Publish Immediately</span>
                </label>
            </div>

            <div class="pt-4 flex items-center justify-end space-x-3 border-t border-slate-100">
                <a href="{{ route('admin.courses.index') }}" class="px-4 py-2 rounded-lg text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 border border-slate-200 shadow-2xs transition-colors">
                    Cancel
                </a>
                <button type="submit" class="px-5 py-2 rounded-lg text-xs font-semibold text-white bg-blue-950 hover:bg-blue-900 shadow-2xs transition-colors">
                    Create Course &amp; Add Modules &rarr;
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
