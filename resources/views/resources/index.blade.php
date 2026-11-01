@extends('layouts.app')

@section('title', 'Free Defence Study Resources & Official Downloads — Nirmaan')
@section('meta_description', 'Download official Indian Army, Navy, Air Force, NDA, CDS and Paramilitary syllabi, high-yield revision notes, previous year solved question papers and formula sheets.')

@section('content')
<div class="py-10 bg-slate-50/50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- Header & Breadcrumb -->
        <div class="pb-6 border-b border-slate-200">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div>
                    <nav class="flex text-xs text-slate-500 mb-2">
                        <a href="{{ route('home') }}" class="hover:text-blue-950">Home</a>
                        <span class="mx-2 text-slate-300">/</span>
                        <span class="text-slate-800 font-semibold">Free Resources</span>
                    </nav>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Free Defence Preparation Resources</h1>
                    <p class="text-sm text-slate-600 mt-1 max-w-2xl">
                        Official syllabi, revision notes, previous year solved papers, and formula guides curated by officers and defence experts.
                    </p>
                </div>

                @if(auth()->check() && auth()->user()->is_admin)
                    <div class="shrink-0">
                        <a href="{{ route('admin.resources.index') }}" 
                           class="inline-flex items-center space-x-1.5 px-3.5 py-2 rounded-lg text-xs font-semibold text-white bg-blue-950 hover:bg-blue-900 transition-colors shadow-2xs">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                            <span>Manage in Admin Panel ⚙</span>
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Free Mock Tests Banner -->
        @if($freeMockTests->count() > 0)
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-base sm:text-lg font-bold text-slate-900 flex items-center space-x-2 tracking-tight">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span>Free Interactive Mock Tests</span>
                    </h2>
                    <a href="{{ route('student.tests.index') }}" class="text-xs font-semibold text-blue-950 hover:underline">
                        View All Tests →
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($freeMockTests as $test)
                        <div class="bg-white rounded-xl p-5 border border-slate-200 flex items-center justify-between shadow-2xs hover:border-slate-300 transition">
                            <div>
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-blue-50 text-blue-950 border border-blue-200 uppercase">
                                    {{ $test->examCategory ? $test->examCategory->name : 'Defence Mock' }}
                                </span>
                                <h3 class="text-sm font-bold text-slate-900 mt-2">{{ $test->title }}</h3>
                                <div class="flex items-center space-x-4 text-xs text-slate-500 mt-1 font-mono">
                                    <span>⏱ {{ $test->duration_minutes }} Mins</span>
                                    <span>•</span>
                                    <span>🎯 {{ number_format($test->total_marks, 0) }} Marks</span>
                                </div>
                            </div>
                            <a href="{{ route('student.tests.instructions', $test->id) }}" class="px-4 py-2 rounded-lg text-xs font-semibold text-white bg-blue-950 hover:bg-blue-900 transition flex-shrink-0 shadow-2xs">
                                Start Test
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Search & Filter Bar -->
        <div class="space-y-3">
            <form action="{{ route('resources.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
                
                <!-- Keyword Search -->
                <div class="relative flex-grow">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </span>
                    <input type="text" name="q" value="{{ request('q') }}" 
                           placeholder="Search syllabus, notes, question papers by keywords..." 
                           class="w-full pl-10 pr-4 py-2 text-xs sm:text-sm bg-white border border-slate-200 rounded-xl outline-none focus:border-blue-950 focus:ring-1 focus:ring-blue-950/20 shadow-2xs transition-colors">
                </div>

                <!-- Exam Category Selector -->
                <div class="w-full sm:w-56 shrink-0">
                    <select name="category" onchange="this.form.submit()" 
                            class="w-full px-3.5 py-2 text-xs sm:text-sm bg-white border border-slate-200 rounded-xl outline-none focus:border-blue-950 shadow-2xs text-slate-800">
                        <option value="all">All Defence Wings</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->slug }}" {{ request('category') === $category->slug ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                @if(request('type'))
                    <input type="hidden" name="type" value="{{ request('type') }}">
                @endif

                <button type="submit" class="px-5 py-2 bg-blue-950 hover:bg-blue-900 text-white text-xs font-semibold rounded-xl shadow-2xs transition-colors shrink-0">
                    Search
                </button>

                @if(request()->anyFilled(['q', 'category', 'type']))
                    <a href="{{ route('resources.index') }}" class="px-3.5 py-2 text-xs text-slate-500 hover:text-red-600 font-semibold transition-colors flex items-center justify-center shrink-0">
                        Clear
                    </a>
                @endif
            </form>

            <!-- Material Type Filter Tabs -->
            <div class="flex flex-wrap gap-2 pt-1">
                <a href="{{ route('resources.index', array_merge(request()->except('type', 'page'), ['type' => ''])) }}" 
                   class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition border {{ !request('type') ? 'bg-blue-950 text-white border-blue-950 shadow-2xs' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-50' }}">
                    All Materials
                </a>
                <a href="{{ route('resources.index', array_merge(request()->except('type', 'page'), ['type' => 'syllabus'])) }}" 
                   class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition border {{ request('type') === 'syllabus' ? 'bg-blue-950 text-white border-blue-950 shadow-2xs' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-50' }}">
                    Official Syllabi
                </a>
                <a href="{{ route('resources.index', array_merge(request()->except('type', 'page'), ['type' => 'pdf'])) }}" 
                   class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition border {{ request('type') === 'pdf' ? 'bg-blue-950 text-white border-blue-950 shadow-2xs' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-50' }}">
                    Revision Notes
                </a>
                <a href="{{ route('resources.index', array_merge(request()->except('type', 'page'), ['type' => 'previous_paper'])) }}" 
                   class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition border {{ request('type') === 'previous_paper' ? 'bg-blue-950 text-white border-blue-950 shadow-2xs' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-50' }}">
                    Previous Papers
                </a>
                <a href="{{ route('resources.index', array_merge(request()->except('type', 'page'), ['type' => 'formula_sheet'])) }}" 
                   class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition border {{ request('type') === 'formula_sheet' ? 'bg-blue-950 text-white border-blue-950 shadow-2xs' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-50' }}">
                    Formula Sheets
                </a>
                <a href="{{ route('resources.index', array_merge(request()->except('type', 'page'), ['type' => 'current_affairs'])) }}" 
                   class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition border {{ request('type') === 'current_affairs' ? 'bg-blue-950 text-white border-blue-950 shadow-2xs' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-50' }}">
                    Current Affairs
                </a>
            </div>
        </div>

        <!-- Materials Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($materials as $material)
                <div class="bg-white rounded-2xl border {{ $material->is_featured ? 'border-amber-300 shadow-sm' : 'border-slate-200 shadow-2xs' }} p-6 hover:shadow-md hover:border-slate-300 transition-all duration-200 flex flex-col justify-between group">
                    <div>
                        <!-- Category, Type & Format Badges -->
                        <div class="flex items-center justify-between mb-3 gap-2">
                            <div class="flex items-center space-x-1.5 flex-wrap gap-y-1">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-blue-950 bg-blue-50 px-2 py-0.5 rounded border border-blue-200">
                                    {{ $material->typeName }}
                                </span>
                                @if($material->examCategory)
                                    <span class="text-[10px] font-semibold text-slate-600 bg-slate-100 px-2 py-0.5 rounded border border-slate-200">
                                        {{ $material->examCategory->name }}
                                    </span>
                                @endif
                            </div>

                            @if($material->is_featured)
                                <span class="inline-flex items-center space-x-1 text-[10px] font-bold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-full border border-amber-200 shrink-0">
                                    <span>★</span>
                                    <span>Featured</span>
                                </span>
                            @else
                                <span class="text-xs text-slate-400 font-mono shrink-0">
                                    {{ $material->file_size ?? 'PDF' }}
                                </span>
                            @endif
                        </div>

                        <!-- Title -->
                        <h3 class="text-base font-bold text-slate-900 leading-snug mb-2 group-hover:text-blue-950 transition-colors">
                            {{ $material->title }}
                        </h3>

                        <!-- Description -->
                        <p class="text-xs text-slate-600 line-clamp-3 leading-relaxed mb-4">
                            {{ $material->description ?: 'Official defence study guide prepared strictly in accordance with current syllabus standards.' }}
                        </p>
                    </div>

                    <!-- Footer Row: Downloads & Action Button -->
                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <span class="text-[11px] text-slate-400 font-mono">
                                📥 {{ number_format($material->downloads_count) }} downloads
                            </span>
                            @if(auth()->check() && auth()->user()->is_admin)
                                <a href="{{ route('admin.resources.edit', $material->id) }}" 
                                   class="text-[10px] text-blue-950 font-semibold hover:underline"
                                   title="Edit in Admin Panel">
                                    [Edit]
                                </a>
                            @endif
                        </div>

                        <a href="{{ route('resources.download', $material->id) }}" 
                           class="inline-flex items-center space-x-1.5 px-3.5 py-1.5 text-xs font-semibold text-white bg-blue-950 hover:bg-blue-900 rounded-lg transition-colors shadow-2xs">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            <span>{{ $material->external_url ? 'Access Link ↗' : 'Download' }}</span>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-16 text-center bg-white rounded-2xl border border-slate-200 shadow-2xs p-8 space-y-3">
                    <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-800">No resources found</h3>
                    <p class="text-xs text-slate-500 max-w-sm mx-auto">
                        No study materials matched your current search and category filters. Try resetting the filters.
                    </p>
                    <a href="{{ route('resources.index') }}" class="inline-block px-4 py-2 text-xs font-semibold text-blue-950 bg-blue-50 hover:bg-blue-100 rounded-lg border border-blue-200">
                        View All Materials
                    </a>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($materials->hasPages())
            <div class="mt-8">
                {{ $materials->links() }}
            </div>
        @endif

    </div>
</div>
@endsection
