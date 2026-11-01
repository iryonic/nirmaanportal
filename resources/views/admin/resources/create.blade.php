@extends('layouts.admin')

@section('title', 'Upload Study Resource — Admin Command')
@section('header_title', 'Upload Resource')

@section('content')
<div class="max-w-3xl mx-auto space-y-5">
    
    <!-- Top Action Breadcrumb Bar -->
    <div class="flex items-center justify-between bg-white rounded-xl p-3.5 sm:p-4 border border-slate-200/80 shadow-2xs">
        <div class="flex items-center space-x-1.5 text-xs text-slate-500 font-medium">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-950">Dashboard</a>
            <span>/</span>
            <a href="{{ route('admin.resources.index') }}" class="hover:text-blue-950">Study Resources</a>
            <span>/</span>
            <span class="text-slate-900 font-semibold">Upload Resource</span>
        </div>
        <a href="{{ route('admin.resources.index') }}" class="text-xs font-semibold text-slate-600 hover:text-slate-900 transition-colors">
            &larr; Back to Resources
        </a>
    </div>

    <!-- Main Creation Form Card -->
    <div class="bg-white rounded-xl p-6 sm:p-8 border border-slate-200/80 shadow-2xs">
        <form action="{{ route('admin.resources.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Section 1: Resource Identity & Category -->
            <div class="space-y-4">
                <h3 class="text-xs font-semibold uppercase tracking-wider text-slate-500 border-b border-slate-100 pb-2">
                    1. Resource Information &amp; Category
                </h3>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Resource Title <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" required value="{{ old('title') }}" 
                           placeholder="e.g. Indian Army Agniveer GD Complete Syllabus &amp; Physical Standards Guide 2027" 
                           class="w-full text-xs sm:text-sm bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 placeholder:text-slate-400 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">
                    @error('title')<span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>@enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Category -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Exam Wing / Category
                        </label>
                        <select name="exam_category_id" class="w-full text-xs sm:text-sm bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">
                            <option value="">General Defence / All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('exam_category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('exam_category_id')<span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>@enderror
                    </div>

                    <!-- Material Type -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Material Type <span class="text-red-500">*</span>
                        </label>
                        <select name="type" required class="w-full text-xs sm:text-sm bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">
                            @foreach($types as $key => $label)
                                <option value="{{ $key }}" {{ old('type', 'pdf') === $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('type')<span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Resource Description &amp; Highlights</label>
                    <textarea name="description" rows="3" 
                              placeholder="Brief summary of what this document covers, chapters included, target exams..." 
                              class="w-full text-xs sm:text-sm bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 placeholder:text-slate-400 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">{{ old('description') }}</textarea>
                    @error('description')<span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>@enderror
                </div>
            </div>

            <!-- Section 2: File Upload / Cloud Document -->
            <div class="space-y-4 pt-4 border-t border-slate-100" 
                 x-data="{ 
                     fileName: '', 
                     fileSize: '', 
                     isDragging: false,
                     handleFiles(files) {
                         if (files && files[0]) {
                             this.fileName = files[0].name;
                             let bytes = files[0].size;
                             if (bytes >= 1048576) {
                                 this.fileSize = (bytes / 1048576).toFixed(1) + ' MB';
                             } else if (bytes >= 1024) {
                                 this.fileSize = (bytes / 1024).toFixed(0) + ' KB';
                             } else {
                                 this.fileSize = bytes + ' B';
                             }
                         }
                     }
                 }">
                <h3 class="text-xs font-semibold uppercase tracking-wider text-slate-500 border-b border-slate-100 pb-2">
                    2. Document File Upload &amp; Cloud Mirrors
                </h3>

                <!-- Modern Drag-and-drop Upload Zone -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Upload Document File <span class="text-slate-400 font-normal">(PDF, DOC, DOCX, ZIP, PPT, XLS — up to 50MB)</span>
                    </label>
                    <div @dragover.prevent="isDragging = true" 
                         @dragleave.prevent="isDragging = false" 
                         @drop.prevent="isDragging = false; $refs.fileInput.files = $event.dataTransfer.files; handleFiles($event.dataTransfer.files)"
                         :class="{ 'border-blue-950 bg-blue-50/50': isDragging, 'border-slate-300 hover:border-slate-400 bg-slate-50/60': !isDragging }"
                         class="relative border-2 border-dashed rounded-xl p-6 text-center transition-colors cursor-pointer group">
                        
                        <input type="file" 
                               name="file" 
                               x-ref="fileInput" 
                               @change="handleFiles($event.target.files)"
                               accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.zip,.rar,.txt"
                               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">

                        <div class="space-y-2">
                            <div class="w-10 h-10 rounded-full bg-white shadow-2xs border border-slate-200 flex items-center justify-center mx-auto text-blue-950 group-hover:scale-105 transition-transform">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                            </div>
                            
                            <template x-if="!fileName">
                                <div>
                                    <p class="text-xs font-semibold text-slate-800">
                                        Click to browse or drag and drop your file here
                                    </p>
                                    <p class="text-[11px] text-slate-400 mt-0.5">
                                        Supports PDF, DOCX, ZIP, Syllabus sheets up to 50MB
                                    </p>
                                </div>
                            </template>

                            <template x-if="fileName">
                                <div class="bg-white rounded-lg p-3 border border-emerald-200 inline-flex items-center space-x-3 text-left shadow-2xs">
                                    <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center font-bold text-xs">
                                        ✓
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold text-slate-900 truncate max-w-xs" x-text="fileName"></p>
                                        <p class="text-[10px] text-slate-500 font-mono" x-text="fileSize"></p>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                    @error('file')<span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>@enderror
                </div>

                <!-- External URL Alternative -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2">
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                            External Cloud / Mirror URL <span class="text-slate-400 font-normal">(Optional)</span>
                        </label>
                        <input type="url" name="external_url" value="{{ old('external_url') }}" 
                               placeholder="https://drive.google.com/file/d/... or official portal link" 
                               class="w-full text-xs sm:text-sm font-mono bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 placeholder:text-slate-400 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">
                        <span class="text-[10px] text-slate-400 mt-0.5 block">Used if hosting large mock banks or files on Google Drive / Mega.</span>
                        @error('external_url')<span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>@enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Display File Size <span class="text-slate-400 font-normal">(Override)</span>
                        </label>
                        <input type="text" name="manual_file_size" value="{{ old('manual_file_size') }}" 
                               placeholder="e.g. 4.2 MB" 
                               class="w-full text-xs sm:text-sm font-mono bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 placeholder:text-slate-400 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">
                        <span class="text-[10px] text-slate-400 mt-0.5 block">Auto-computed if uploading file.</span>
                    </div>
                </div>
            </div>

            <!-- Section 3: Publishing & Visibility Flags -->
            <div class="space-y-4 pt-4 border-t border-slate-100">
                <h3 class="text-xs font-semibold uppercase tracking-wider text-slate-500 border-b border-slate-100 pb-2">
                    3. Visibility &amp; Access Controls
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    
                    <!-- Free Access -->
                    <label class="relative flex items-start p-3 rounded-xl border border-slate-200 hover:border-slate-300 bg-white cursor-pointer transition-colors shadow-2xs">
                        <div class="flex items-center h-5">
                            <input type="checkbox" name="is_free" value="1" {{ old('is_free', true) ? 'checked' : '' }}
                                   class="w-4 h-4 text-blue-950 rounded border-slate-300 focus:ring-blue-950">
                        </div>
                        <div class="ml-2.5 text-xs">
                            <span class="font-bold text-slate-900 block">Free Download</span>
                            <span class="text-slate-500 text-[11px] block mt-0.5">Available to all aspirants without payment</span>
                        </div>
                    </label>

                    <!-- Published Status -->
                    <label class="relative flex items-start p-3 rounded-xl border border-slate-200 hover:border-slate-300 bg-white cursor-pointer transition-colors shadow-2xs">
                        <div class="flex items-center h-5">
                            <input type="checkbox" name="is_published" value="1" {{ old('is_published', true) ? 'checked' : '' }}
                                   class="w-4 h-4 text-blue-950 rounded border-slate-300 focus:ring-blue-950">
                        </div>
                        <div class="ml-2.5 text-xs">
                            <span class="font-bold text-slate-900 block">Publish on /resources</span>
                            <span class="text-slate-500 text-[11px] block mt-0.5">Visible publicly on website downloads page</span>
                        </div>
                    </label>

                    <!-- Featured Star -->
                    <label class="relative flex items-start p-3 rounded-xl border border-slate-200 hover:border-slate-300 bg-white cursor-pointer transition-colors shadow-2xs">
                        <div class="flex items-center h-5">
                            <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', false) ? 'checked' : '' }}
                                   class="w-4 h-4 text-amber-500 rounded border-slate-300 focus:ring-amber-500">
                        </div>
                        <div class="ml-2.5 text-xs">
                            <span class="font-bold text-slate-900 block">Featured Resource ★</span>
                            <span class="text-slate-500 text-[11px] block mt-0.5">Pins resource to the top of list</span>
                        </div>
                    </label>

                </div>
            </div>

            <!-- Form Action Footer -->
            <div class="pt-5 border-t border-slate-100 flex items-center justify-between">
                <a href="{{ route('admin.resources.index') }}" 
                   class="px-4 py-2 rounded-lg text-xs font-semibold text-slate-600 hover:bg-slate-100 transition-colors">
                    Cancel
                </a>
                <div class="flex items-center space-x-2">
                    <button type="submit" 
                            class="px-5 py-2.5 rounded-lg text-xs font-semibold text-white bg-blue-950 hover:bg-blue-900 transition-colors shadow-2xs flex items-center space-x-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span>Save &amp; Upload Resource</span>
                    </button>
                </div>
            </div>

        </form>
    </div>

</div>
@endsection
