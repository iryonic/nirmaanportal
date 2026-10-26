@extends('layouts.admin')

@section('title', 'Create Course — Admin')
@section('header_title', 'Create New Defence Course')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    
    <div>
        <a href="{{ route('admin.courses.index') }}" class="text-xs font-bold text-blue-700 hover:underline">
            ← Back to Courses
        </a>
        <h1 class="text-xl font-bold text-gray-900 mt-2">New Course Information</h1>
    </div>

    <div class="bg-white rounded-3xl p-6 sm:p-10 border border-gray-100 shadow-xl">
        <form action="{{ route('admin.courses.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Course Title *</label>
                    <input type="text" name="title" required value="{{ old('title') }}" placeholder="e.g. Indian Army Agniveer GD Full Batch" class="w-full text-xs sm:text-sm bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 outline-none focus:border-blue-500 focus:bg-white">
                    @error('title')<span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>@enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Exam Category *</label>
                    <select name="exam_category_id" required class="w-full text-xs sm:text-sm bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 outline-none focus:border-blue-500 focus:bg-white">
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
                <label class="block text-xs font-bold text-gray-700 mb-1">Subtitle / Target Trades</label>
                <input type="text" name="subtitle" value="{{ old('subtitle') }}" placeholder="e.g. Agniveer / Soldier / Clerk / Technical" class="w-full text-xs sm:text-sm bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 outline-none focus:border-blue-500 focus:bg-white">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Course Description *</label>
                <textarea name="description" rows="4" required class="w-full text-xs sm:text-sm bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 outline-none focus:border-blue-500 focus:bg-white">{{ old('description') }}</textarea>
                @error('description')<span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>@enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Price (₹) *</label>
                    <input type="number" step="0.01" name="price" required value="{{ old('price', 2999) }}" class="w-full text-xs sm:text-sm bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 outline-none focus:border-blue-500 focus:bg-white">
                    @error('price')<span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>@enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Original Price (₹)</label>
                    <input type="number" step="0.01" name="original_price" value="{{ old('original_price', 4999) }}" class="w-full text-xs sm:text-sm bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 outline-none focus:border-blue-500 focus:bg-white">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Duration Text</label>
                    <input type="text" name="duration_text" value="{{ old('duration_text', '6 Months Validity') }}" class="w-full text-xs sm:text-sm bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 outline-none focus:border-blue-500 focus:bg-white">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Chief Instructor / Faculty Name</label>
                <input type="text" name="instructor_name" value="{{ old('instructor_name', 'Ex-Defence Officers & Senior Faculty') }}" class="w-full text-xs sm:text-sm bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 outline-none focus:border-blue-500 focus:bg-white">
            </div>

            <!-- Features Checkboxes -->
            <div class="pt-4 border-t border-gray-100 grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs font-semibold text-gray-700">
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="has_live_classes" value="1" checked class="rounded text-blue-600">
                    <span>Live Classes</span>
                </label>
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="has_mock_tests" value="1" checked class="rounded text-blue-600">
                    <span>Mock Tests</span>
                </label>
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="has_study_material" value="1" checked class="rounded text-blue-600">
                    <span>Study Material</span>
                </label>
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="is_published" value="1" checked class="rounded text-blue-600">
                    <span>Publish Immediately</span>
                </label>
            </div>

            <div class="pt-4">
                <button type="submit" class="px-8 py-3.5 rounded-full text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-md transition">
                    Create Course &amp; Add Modules →
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
