@extends('layouts.admin')

@section('title', 'Edit Course & Curriculum — Admin')
@section('header_title', 'Curriculum & Modules: ' . $course->title)

@section('content')
<div class="space-y-8 max-w-5xl mx-auto">
    
    <div class="flex items-center justify-between bg-white rounded-2xl p-4 border border-gray-100 shadow-2xs">
        <div class="flex items-center space-x-2 text-xs font-semibold text-gray-500">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-700">HQ Dashboard</a>
            <span>&bull;</span>
            <a href="{{ route('admin.courses.index') }}" class="hover:text-blue-700">Courses</a>
            <span>&bull;</span>
            <span class="text-blue-700 font-bold truncate max-w-xs">{{ $course->title }}</span>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.courses.index') }}" class="text-xs font-bold text-gray-500 hover:text-gray-800">
                ← Back to Courses
            </a>
            <a href="{{ route('courses.show', $course->slug) }}" target="_blank" class="px-3 py-1.5 rounded-lg bg-gray-50 hover:bg-gray-100 text-xs font-bold text-blue-700 border border-gray-200">
                Live Preview ↗
            </a>
        </div>
    </div>

    <!-- Course Basic Details Edit Form -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-gray-100 shadow-xs">
        <h2 class="text-base font-bold text-gray-900 mb-6">Course Parameters</h2>

        <form action="{{ route('admin.courses.update', $course->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Course Title</label>
                    <input type="text" name="title" required value="{{ old('title', $course->title) }}" class="w-full text-xs sm:text-sm bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 outline-none focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Exam Category</label>
                    <select name="exam_category_id" required class="w-full text-xs sm:text-sm bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 outline-none focus:border-blue-500">
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
                    <label class="block text-xs font-bold text-gray-700 mb-1">Price (₹)</label>
                    <input type="number" step="0.01" name="price" value="{{ old('price', $course->price) }}" class="w-full text-xs bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 outline-none focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Original Price (₹)</label>
                    <input type="number" step="0.01" name="original_price" value="{{ old('original_price', $course->original_price) }}" class="w-full text-xs bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 outline-none focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1">Duration Text</label>
                    <input type="text" name="duration_text" value="{{ old('duration_text', $course->duration_text) }}" class="w-full text-xs bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 outline-none focus:border-blue-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1">Course Description</label>
                <textarea name="description" rows="3" class="w-full text-xs sm:text-sm bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 outline-none focus:border-blue-500">{{ old('description', $course->description) }}</textarea>
            </div>

            <div class="pt-2 flex flex-wrap gap-4 text-xs font-semibold">
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="is_published" value="1" {{ $course->is_published ? 'checked' : '' }} class="rounded text-blue-600">
                    <span>Published</span>
                </label>
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="has_live_classes" value="1" {{ $course->has_live_classes ? 'checked' : '' }} class="rounded text-blue-600">
                    <span>Live Classes</span>
                </label>
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="has_mock_tests" value="1" {{ $course->has_mock_tests ? 'checked' : '' }} class="rounded text-blue-600">
                    <span>Mock Tests</span>
                </label>
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="has_study_material" value="1" {{ $course->has_study_material ? 'checked' : '' }} class="rounded text-blue-600">
                    <span>Study Material</span>
                </label>
            </div>

            <div class="pt-2">
                <button type="submit" class="px-6 py-2.5 rounded-full text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-sm transition">
                    Update Course Parameters
                </button>
            </div>
        </form>
    </div>

    <!-- Bulk YouTube Playlist Importer & Multi-Episode Adder (Matching User Screenshot) -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-gray-100 shadow-xs space-y-6" x-data="{ 
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
        <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-gray-100 gap-4">
            <div>
                <h2 class="text-base font-bold text-gray-900 uppercase tracking-tight text-amber-700">2. Episodes List (Paste Multiple Episode Links)</h2>
                <p class="text-xs text-gray-500 mt-0.5">Add episode titles and YouTube or iframe links below, or auto-fetch complete YouTube playlists.</p>
            </div>
            <div class="flex items-center space-x-3">
                <button type="button" @click="addEpisode()" class="inline-flex items-center space-x-1.5 px-4 py-2 rounded-xl text-xs font-bold text-amber-800 bg-amber-50 hover:bg-amber-100 border border-amber-300 transition">
                    <span>+ Add Episode</span>
                </button>
                <a href="{{ route('courses.watch', $course->slug) }}" target="_blank" class="inline-flex items-center space-x-1.5 px-4 py-2 rounded-xl text-xs font-bold text-blue-700 bg-blue-50 hover:bg-blue-100 border border-blue-200 transition">
                    <span>Dedicated Video View ↗</span>
                </a>
            </div>
        </div>

        <!-- BULK YOUTUBE PLAYLIST IMPORTER (AUTO-IMPORT ALL EPISODES) (Exact Match to Image 2) -->
        <div class="rounded-2xl border-2 border-amber-300 bg-amber-50/40 p-5 space-y-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2 text-amber-900 font-extrabold text-xs uppercase tracking-wider">
                    <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    <span>BULK YOUTUBE PLAYLIST IMPORTER (AUTO-IMPORT ALL EPISODES)</span>
                </div>
                <span class="text-[11px] font-bold text-amber-700">Paste Playlist URL</span>
            </div>

            <form action="{{ route('admin.courses.import-playlist', $course->id) }}" method="POST" @submit="importing = true" class="flex flex-col sm:flex-row gap-3">
                @csrf
                <div class="relative flex-grow">
                    <input type="text" 
                           name="playlist_url" 
                           required 
                           value="{{ old('playlist_url', $course->youtube_playlist_url) }}"
                           placeholder="Paste YouTube Playlist link (e.g. https://www.youtube.com/playlist?list=...)" 
                           class="w-full text-xs font-mono bg-white border border-amber-200 rounded-xl px-4 py-3 outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 text-gray-800">
                </div>
                <button type="submit" 
                        :disabled="importing"
                        class="px-6 py-3 rounded-xl text-xs font-bold text-white bg-amber-600 hover:bg-amber-700 transition flex items-center justify-center space-x-2 flex-shrink-0 shadow-xs disabled:opacity-50">
                    <svg x-show="!importing" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    <span x-show="!importing">Import Episodes</span>
                    <span x-show="importing" style="display:none;" class="flex items-center space-x-1">
                        <svg class="animate-spin h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        <span>Fetching Videos...</span>
                    </span>
                </button>
            </form>
        </div>

        <!-- Manual Multiple Episodes List (Matching Image 2) -->
        <form action="{{ route('admin.courses.save-episodes', $course->id) }}" method="POST" class="space-y-4 pt-2">
            @csrf
            
            <template x-for="(ep, index) in episodes" :key="index">
                <div class="border border-gray-200 rounded-2xl p-4 sm:p-5 bg-white space-y-3 shadow-2xs hover:border-gray-300 transition">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <span class="w-6 h-6 rounded-full bg-slate-900 text-white text-[11px] font-black flex items-center justify-center font-mono" x-text="index + 1"></span>
                            <span class="text-xs font-black uppercase tracking-wider text-slate-800">EPISODE DETAILS</span>
                        </div>
                        <button type="button" @click="removeEpisode(index)" class="text-xs font-bold text-red-500 hover:text-red-700 transition" x-show="episodes.length > 1">
                            Remove
                        </button>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-3">
                        <div class="sm:col-span-5">
                            <input type="text" 
                                   :name="'episodes[' + index + '][title]'" 
                                   x-model="ep.title"
                                   required 
                                   placeholder="Episode Title" 
                                   class="w-full text-xs bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 outline-none focus:border-blue-500">
                        </div>
                        <div class="sm:col-span-7">
                            <input type="text" 
                                   :name="'episodes[' + index + '][video_input]'" 
                                   x-model="ep.video_input"
                                   required 
                                   placeholder="Paste YouTube Link or <iframe src='...'></iframe>" 
                                   class="w-full text-xs font-mono bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 outline-none focus:border-blue-500">
                        </div>
                    </div>
                </div>
            </template>

            <!-- Click to Add Another Episode (Dashed Card matching Image 2) -->
            <button type="button" 
                    @click="addEpisode()" 
                    class="w-full py-4 border-2 border-dashed border-gray-300 hover:border-blue-500 rounded-2xl text-center text-xs font-bold text-gray-500 hover:text-blue-700 hover:bg-blue-50/30 transition flex items-center justify-center space-x-2">
                <span>+ Click to Add Another Episode (Episode <span x-text="episodes.length + 1"></span>, <span x-text="episodes.length + 2"></span>...)</span>
            </button>

            <div class="flex justify-end pt-2">
                <button type="submit" class="px-6 py-2.5 rounded-xl text-xs font-bold text-white bg-slate-900 hover:bg-slate-800 transition shadow-xs">
                    Save Manual Episodes
                </button>
            </div>
        </form>
    </div>

    <!-- Curriculum Modules & Lessons Manager -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-gray-100 shadow-xs space-y-6">
        <div class="flex items-center justify-between pb-4 border-b border-gray-100">
            <div>
                <h2 class="text-base font-bold text-gray-900">Curriculum Modules ({{ $course->modules->count() }})</h2>
                <p class="text-xs text-gray-400">Add syllabus modules and attach video lessons.</p>
            </div>
        </div>

        <!-- Add Module Card -->
        <div class="bg-gray-50/70 p-5 rounded-2xl border border-gray-200/70">
            <h3 class="text-xs font-bold text-gray-700 uppercase tracking-wider mb-3">+ Add New Syllabus Module</h3>
            <form action="{{ route('admin.courses.modules.store', $course->id) }}" method="POST" class="flex flex-col sm:flex-row gap-3">
                @csrf
                <input type="text" name="title" required placeholder="Module Title (e.g. Module 5: Current Affairs & Defence Updates)" class="flex-grow text-xs bg-white border border-gray-200 rounded-xl px-4 py-2.5 outline-none focus:border-blue-500">
                <button type="submit" class="px-6 py-2.5 rounded-xl text-xs font-bold text-white bg-slate-900 hover:bg-slate-800 transition flex-shrink-0">
                    Add Module
                </button>
            </form>
        </div>

        <!-- Modules List -->
        <div class="space-y-6">
            @foreach($course->modules as $mIndex => $module)
                <div class="border border-gray-200 rounded-2xl p-5 bg-white space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <span class="w-6 h-6 rounded-full bg-blue-100 text-blue-800 text-xs font-bold flex items-center justify-center">
                                {{ $mIndex + 1 }}
                            </span>
                            <h4 class="text-sm font-bold text-gray-900">{{ $module->title }}</h4>
                        </div>
                        <span class="text-xs text-gray-400 font-medium">{{ $module->lessons->count() }} Lessons</span>
                    </div>

                    <!-- Lessons in module -->
                    <div class="space-y-2">
                        @foreach($module->lessons as $lesson)
                            <div class="p-3 bg-gray-50 rounded-xl flex items-center justify-between text-xs">
                                <div class="flex items-center space-x-3">
                                    <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                                    <span class="font-semibold text-gray-800">{{ $lesson->title }}</span>
                                    <span class="text-[10px] text-gray-400">({{ $lesson->duration_minutes }}m)</span>
                                    @if($lesson->is_preview)
                                        <span class="text-[9px] font-bold uppercase bg-emerald-100 text-emerald-800 px-2 py-0.5 rounded-full">Free Preview</span>
                                    @endif
                                </div>

                                <div class="flex items-center space-x-3">
                                    <span class="text-[10px] text-gray-400 font-mono">YT: {{ $lesson->video_id }}</span>
                                    <form action="{{ route('admin.courses.lessons.destroy', $lesson->id) }}" method="POST" onsubmit="return confirm('Delete this lesson?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 text-xs">✕</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Add Lesson Form into this Module -->
                    <div class="pt-3 border-t border-gray-100">
                        <form action="{{ route('admin.courses.lessons.store', $module->id) }}" method="POST" class="grid grid-cols-1 sm:grid-cols-12 gap-2 items-center">
                            @csrf
                            <input type="hidden" name="video_provider" value="youtube">
                            
                            <div class="sm:col-span-5">
                                <input type="text" name="title" required placeholder="Lesson Title" class="w-full text-xs bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-blue-500">
                            </div>
                            <div class="sm:col-span-3">
                                <input type="text" name="video_id" required placeholder="YouTube ID (e.g. dQw4w9WgXcQ)" class="w-full text-xs bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-blue-500">
                            </div>
                            <div class="sm:col-span-2">
                                <input type="number" name="duration_minutes" required value="30" placeholder="Mins" class="w-full text-xs bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-blue-500">
                            </div>
                            <div class="sm:col-span-2 flex items-center space-x-2">
                                <label class="flex items-center space-x-1 text-[11px] text-gray-600">
                                    <input type="checkbox" name="is_preview" value="1" class="rounded text-blue-600">
                                    <span>Free</span>
                                </label>
                                <button type="submit" class="px-3 py-2 rounded-lg bg-blue-600 text-white font-bold text-xs hover:bg-blue-700 transition">
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
