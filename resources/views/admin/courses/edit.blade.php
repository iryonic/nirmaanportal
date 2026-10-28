@extends('layouts.admin')

@section('title', 'Edit Course & Curriculum — Admin Command')
@section('header_title', 'Curriculum: ' . $course->title)

@section('content')
<div class="space-y-6 max-w-5xl mx-auto">
    
    <!-- Top Action Breadcrumb Bar -->
    <div class="flex items-center justify-between bg-white rounded-xl p-3.5 sm:p-4 border border-slate-200/80 shadow-2xs">
        <div class="flex items-center space-x-1.5 text-xs text-slate-500 font-medium">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-950">Dashboard</a>
            <span>/</span>
            <a href="{{ route('admin.courses.index') }}" class="hover:text-blue-950">Courses</a>
            <span>/</span>
            <span class="text-slate-900 font-semibold truncate max-w-xs">{{ $course->title }}</span>
        </div>
        <div class="flex items-center space-x-2">
            <a href="{{ route('admin.courses.index') }}" class="text-xs font-semibold text-slate-600 hover:text-slate-900 transition-colors">
                &larr; Back
            </a>
            <a href="{{ route('courses.show', $course->slug) }}" target="_blank" class="px-2.5 py-1 rounded-md bg-white hover:bg-slate-50 text-xs font-medium text-slate-700 border border-slate-200 shadow-2xs transition-colors">
                Live Preview ↗
            </a>
        </div>
    </div>

    <!-- Section 1: Course Basic Details Form -->
    <div class="bg-white rounded-xl p-5 sm:p-7 border border-slate-200/80 shadow-2xs space-y-5">
        <div>
            <h2 class="text-sm font-semibold text-slate-900">1. Course Parameters &amp; Pricing</h2>
            <p class="text-[11px] text-slate-500">Edit course title, category, pricing, and feature highlights.</p>
        </div>

        <form action="{{ route('admin.courses.update', $course->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Course Title</label>
                    <input type="text" name="title" required value="{{ old('title', $course->title) }}" 
                           class="w-full text-xs sm:text-sm bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Exam Category</label>
                    <select name="exam_category_id" required 
                            class="w-full text-xs sm:text-sm bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ $course->exam_category_id == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Price (₹)</label>
                    <input type="number" step="0.01" name="price" value="{{ old('price', $course->price) }}" 
                           class="w-full text-xs sm:text-sm font-mono bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Original Price (₹)</label>
                    <input type="number" step="0.01" name="original_price" value="{{ old('original_price', $course->original_price) }}" 
                           class="w-full text-xs sm:text-sm font-mono bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Duration Text</label>
                    <input type="text" name="duration_text" value="{{ old('duration_text', $course->duration_text) }}" 
                           class="w-full text-xs sm:text-sm bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Course Description</label>
                <textarea name="description" rows="3" 
                          class="w-full text-xs sm:text-sm bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">{{ old('description', $course->description) }}</textarea>
            </div>

            <div class="pt-2 flex flex-wrap gap-4 text-xs font-medium text-slate-700">
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="checkbox" name="is_published" value="1" {{ $course->is_published ? 'checked' : '' }} class="rounded text-blue-950 focus:ring-blue-950">
                    <span>Published</span>
                </label>
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="checkbox" name="has_live_classes" value="1" {{ $course->has_live_classes ? 'checked' : '' }} class="rounded text-blue-950 focus:ring-blue-950">
                    <span>Live Classes</span>
                </label>
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="checkbox" name="has_mock_tests" value="1" {{ $course->has_mock_tests ? 'checked' : '' }} class="rounded text-blue-950 focus:ring-blue-950">
                    <span>Mock Tests</span>
                </label>
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="checkbox" name="has_study_material" value="1" {{ $course->has_study_material ? 'checked' : '' }} class="rounded text-blue-950 focus:ring-blue-950">
                    <span>Study Material</span>
                </label>
            </div>

            <div class="pt-2">
                <button type="submit" class="px-4 py-2 rounded-lg text-xs font-semibold text-white bg-blue-950 hover:bg-blue-900 shadow-2xs transition-colors">
                    Update Course Parameters
                </button>
            </div>
        </form>
    </div>

    <!-- Section 2: Bulk Playlist Importer & Multi-Episode Adder -->
    <div class="bg-white rounded-xl p-5 sm:p-7 border border-slate-200/80 shadow-2xs space-y-5" x-data="{ 
        importing: false,
        episodes: [
            { title: 'Episode 1', video_input: '' },
            { title: 'Episode 2', video_input: '' }
        ],
        addEpisode() {
            this.episodes.push({ title: 'Episode ' + (this.episodes.length + 1), video_input: '' });
        },
        removeEpisode(idx) {
            if (this.episodes.length > 1) {
                this.episodes.splice(idx, 1);
            }
        }
    }">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-3 border-b border-slate-100 gap-3">
            <div>
                <h2 class="text-sm font-semibold text-slate-900">2. Episode Links &amp; YouTube Playlist Importer</h2>
                <p class="text-[11px] text-slate-500">Add episode titles and YouTube links, or auto-fetch an entire playlist.</p>
            </div>
            <div class="flex items-center space-x-2">
                <button type="button" @click="addEpisode()" class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-lg text-xs font-medium text-slate-700 bg-white hover:bg-slate-50 border border-slate-200 transition-colors shadow-2xs">
                    <span>+ Add Row</span>
                </button>
                <a href="{{ route('courses.watch', $course->slug) }}" target="_blank" class="inline-flex items-center space-x-1 px-3 py-1.5 rounded-lg text-xs font-medium text-blue-950 bg-blue-50 hover:bg-blue-100 border border-blue-200/60 transition-colors shadow-2xs">
                    <span>Video View ↗</span>
                </a>
            </div>
        </div>

        <!-- Bulk Playlist Importer Box -->
        <div class="rounded-xl border border-slate-200 bg-slate-50/70 p-4 space-y-2.5">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2 text-slate-800 font-semibold text-xs">
                    <svg class="w-4 h-4 text-blue-950" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    <span>Auto-Import YouTube Playlist</span>
                </div>
                <span class="text-[11px] text-slate-500 font-medium">Auto-fetches all video IDs</span>
            </div>

            <form action="{{ route('admin.courses.import-playlist', $course->id) }}" method="POST" @submit="importing = true" class="flex flex-col sm:flex-row gap-2.5">
                @csrf
                <div class="relative flex-grow">
                    <input type="text" 
                           name="playlist_url" 
                           required 
                           value="{{ old('playlist_url', $course->youtube_playlist_url) }}"
                           placeholder="https://www.youtube.com/playlist?list=..." 
                           class="w-full text-xs font-mono bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">
                </div>
                <button type="submit" 
                        :disabled="importing"
                        class="px-4 py-2 rounded-lg text-xs font-semibold text-white bg-blue-950 hover:bg-blue-900 transition-colors flex items-center justify-center space-x-1.5 shrink-0 shadow-2xs disabled:opacity-50">
                    <svg x-show="!importing" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    <span x-show="!importing">Import Playlist</span>
                    <span x-show="importing" style="display:none;" class="flex items-center space-x-1">
                        <svg class="animate-spin h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        <span>Fetching...</span>
                    </span>
                </button>
            </form>
        </div>

        <!-- Manual Episode Rows -->
        <form action="{{ route('admin.courses.save-episodes', $course->id) }}" method="POST" class="space-y-3 pt-1">
            @csrf
            
            <template x-for="(ep, index) in episodes" :key="index">
                <div class="border border-slate-200 rounded-lg p-3 sm:p-4 bg-white space-y-2.5 shadow-2xs hover:border-slate-300 transition-colors">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <span class="w-5 h-5 rounded-md bg-slate-100 text-slate-700 text-[10px] font-bold flex items-center justify-center font-mono" x-text="index + 1"></span>
                            <span class="text-xs font-semibold uppercase tracking-wider text-slate-700">Episode Row</span>
                        </div>
                        <button type="button" @click="removeEpisode(index)" class="text-xs font-medium text-red-500 hover:text-red-700 transition-colors" x-show="episodes.length > 1">
                            Remove
                        </button>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-2.5">
                        <div class="sm:col-span-5">
                            <input type="text" 
                                   :name="'episodes[' + index + '][title]'" 
                                   x-model="ep.title"
                                   required 
                                   placeholder="Episode Title" 
                                   class="w-full text-xs bg-white border border-slate-200 rounded-lg px-3 py-1.5 text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">
                        </div>
                        <div class="sm:col-span-7">
                            <input type="text" 
                                   :name="'episodes[' + index + '][video_input]'" 
                                   x-model="ep.video_input"
                                   required 
                                   placeholder="YouTube Link or <iframe src='...'></iframe>" 
                                   class="w-full text-xs font-mono bg-white border border-slate-200 rounded-lg px-3 py-1.5 text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">
                        </div>
                    </div>
                </div>
            </template>

            <!-- Click to Add Another Episode -->
            <button type="button" 
                    @click="addEpisode()" 
                    class="w-full py-2.5 border border-dashed border-slate-300 hover:border-blue-950 rounded-lg text-center text-xs font-medium text-slate-500 hover:text-blue-950 hover:bg-slate-50 transition-colors flex items-center justify-center space-x-1.5">
                <span>+ Click to Add Episode Row (<span x-text="episodes.length + 1"></span>)</span>
            </button>

            <div class="flex justify-end pt-1">
                <button type="submit" class="px-4 py-2 rounded-lg text-xs font-semibold text-white bg-slate-900 hover:bg-slate-800 transition-colors shadow-2xs">
                    Save Manual Episodes
                </button>
            </div>
        </form>
    </div>

    <!-- Section 3: Curriculum Modules & Lessons Manager -->
    <div class="bg-white rounded-xl p-5 sm:p-7 border border-slate-200/80 shadow-2xs space-y-5">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div>
                <h2 class="text-sm font-semibold text-slate-900">3. Curriculum Modules &amp; Video Lessons</h2>
                <p class="text-[11px] text-slate-500">Structured syllabus units and individual video lesson attachments.</p>
            </div>
            <span class="px-2 py-0.5 rounded text-xs font-medium bg-slate-100 text-slate-700 border border-slate-200">
                {{ $course->modules->count() }} Modules
            </span>
        </div>

        <!-- Add Module Card -->
        <div class="bg-slate-50/70 p-4 rounded-xl border border-slate-200/80">
            <h3 class="text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2.5">+ Add Syllabus Module</h3>
            <form action="{{ route('admin.courses.modules.store', $course->id) }}" method="POST" class="flex flex-col sm:flex-row gap-2.5">
                @csrf
                <input type="text" name="title" required placeholder="Module Title (e.g. Module 3: Quantitative Aptitude & Numerical Ability)" 
                       class="flex-grow text-xs bg-white border border-slate-200 rounded-lg px-3.5 py-2 text-slate-900 outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">
                <button type="submit" class="px-4 py-2 rounded-lg text-xs font-semibold text-white bg-blue-950 hover:bg-blue-900 transition-colors shrink-0 shadow-2xs">
                    Add Module
                </button>
            </form>
        </div>

        <!-- Modules List -->
        <div class="space-y-4">
            @foreach($course->modules as $mIndex => $module)
                <div class="border border-slate-200 rounded-xl p-4 bg-white space-y-3 shadow-2xs">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <span class="w-5 h-5 rounded-md bg-blue-50 text-blue-950 text-xs font-bold flex items-center justify-center border border-blue-200/60">
                                {{ $mIndex + 1 }}
                            </span>
                            <h4 class="text-xs font-semibold text-slate-900">{{ $module->title }}</h4>
                        </div>
                        <span class="text-[11px] text-slate-400 font-medium">{{ $module->lessons->count() }} Lessons</span>
                    </div>

                    <!-- Lessons list -->
                    <div class="space-y-1.5">
                        @foreach($module->lessons as $lesson)
                            <div class="p-2.5 bg-slate-50 rounded-lg flex items-center justify-between text-xs border border-slate-100">
                                <div class="flex items-center space-x-2.5 min-w-0">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500 shrink-0"></span>
                                    <span class="font-medium text-slate-800 truncate">{{ $lesson->title }}</span>
                                    <span class="text-[10px] text-slate-400 shrink-0">({{ $lesson->duration_minutes }}m)</span>
                                    @if($lesson->is_preview)
                                        <span class="text-[9px] font-semibold uppercase bg-emerald-50 text-emerald-700 px-1.5 py-0.2 rounded border border-emerald-200/60 shrink-0">Free Preview</span>
                                    @endif
                                </div>

                                <div class="flex items-center space-x-2.5 shrink-0">
                                    <span class="text-[10px] text-slate-400 font-mono hidden sm:inline">YT: {{ $lesson->video_id }}</span>
                                    <form action="{{ route('admin.courses.lessons.destroy', $lesson->id) }}" method="POST" onsubmit="return confirm('Delete this lesson?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-slate-400 hover:text-red-600 text-xs px-1">✕</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Add Lesson Form into this Module -->
                    <div class="pt-2.5 border-t border-slate-100">
                        <form action="{{ route('admin.courses.lessons.store', $module->id) }}" method="POST" class="grid grid-cols-1 sm:grid-cols-12 gap-2 items-center">
                            @csrf
                            <input type="hidden" name="video_provider" value="youtube">
                            
                            <div class="sm:col-span-5">
                                <input type="text" name="title" required placeholder="Lesson Title" 
                                       class="w-full text-xs bg-white border border-slate-200 rounded-lg px-2.5 py-1.5 outline-none focus:border-blue-950">
                            </div>
                            <div class="sm:col-span-3">
                                <input type="text" name="video_id" required placeholder="YouTube ID (e.g. dQw4w9WgXcQ)" 
                                       class="w-full text-xs font-mono bg-white border border-slate-200 rounded-lg px-2.5 py-1.5 outline-none focus:border-blue-950">
                            </div>
                            <div class="sm:col-span-2">
                                <input type="number" name="duration_minutes" required value="30" placeholder="Mins" 
                                       class="w-full text-xs bg-white border border-slate-200 rounded-lg px-2.5 py-1.5 outline-none focus:border-blue-950">
                            </div>
                            <div class="sm:col-span-2 flex items-center space-x-2">
                                <label class="flex items-center space-x-1 text-[11px] text-slate-600 shrink-0 cursor-pointer">
                                    <input type="checkbox" name="is_preview" value="1" class="rounded text-blue-950">
                                    <span>Free</span>
                                </label>
                                <button type="submit" class="px-3 py-1.5 rounded-lg bg-blue-950 text-white font-semibold text-xs hover:bg-blue-900 transition-colors shadow-2xs">
                                    + Add
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            @endforeach
        </div>
    </div>

</div>
@endsection
