@extends('layouts.admin')

@section('title', 'Study Resources & Download Management — Admin Command')
@section('header_title', 'Study Resources')

@section('content')
<div class="space-y-5" x-data="bulkActionManager({ allIds: {{ json_encode($resources->pluck('id')) }}, actionUrl: '{{ route('admin.resources.bulk') }}' })">
    
    <!-- Action Bar & Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white rounded-xl p-4 sm:p-5 border border-slate-200/80 shadow-2xs">
        <div>
            <div class="flex items-center space-x-1.5 text-xs text-slate-500 font-medium mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-950">Dashboard</a>
                <span>/</span>
                <span class="text-slate-900 font-semibold">Study Resources</span>
            </div>
            <h1 class="text-lg sm:text-xl font-bold text-slate-900 tracking-tight">Study Resources &amp; Downloads Management</h1>
            <p class="text-xs text-slate-500 mt-0.5">Upload and manage syllabi, revision notes, previous year question papers, and downloadable PDFs for cadets on the <code class="text-blue-950 font-mono">/resources</code> portal.</p>
        </div>
        <div class="flex items-center space-x-2 shrink-0">
            <a href="{{ route('resources.index') }}" target="_blank" 
               class="px-3 py-2 rounded-lg text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 border border-slate-200 transition-colors flex items-center space-x-1.5 shadow-2xs">
                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                <span>Live Resources ↗</span>
            </a>
            <a href="{{ route('admin.resources.create') }}" 
               class="px-3.5 py-2 rounded-lg text-xs font-semibold text-white bg-blue-950 hover:bg-blue-900 transition-colors flex items-center space-x-1.5 shadow-2xs">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span>Upload Resource</span>
            </a>
        </div>
    </div>

    <!-- 4 KPI Summary Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
        <!-- Total -->
        <div class="bg-white rounded-xl p-4 border border-slate-200/80 shadow-2xs hover:border-slate-300 transition-colors flex items-center space-x-3">
            <div class="w-9 h-9 rounded-lg bg-blue-50 text-blue-950 flex items-center justify-center shrink-0 border border-blue-200/60">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
            </div>
            <div>
                <span class="text-[11px] text-slate-500 font-semibold uppercase tracking-wider block">Total Resources</span>
                <span class="text-xl font-bold text-slate-900 block font-mono">{{ $totalCount }}</span>
            </div>
        </div>

        <!-- Published -->
        <div class="bg-white rounded-xl p-4 border border-slate-200/80 shadow-2xs hover:border-slate-300 transition-colors flex items-center space-x-3">
            <div class="w-9 h-9 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center shrink-0 border border-emerald-200/60">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <span class="text-[11px] text-slate-500 font-semibold uppercase tracking-wider block">Live Published</span>
                <span class="text-xl font-bold text-slate-900 block font-mono">{{ $publishedCount }}</span>
            </div>
        </div>

        <!-- Featured / Pinned -->
        <div class="bg-white rounded-xl p-4 border border-slate-200/80 shadow-2xs hover:border-slate-300 transition-colors flex items-center space-x-3">
            <div class="w-9 h-9 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center shrink-0 border border-amber-200/60">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
            </div>
            <div>
                <span class="text-[11px] text-slate-500 font-semibold uppercase tracking-wider block">Featured / Pinned</span>
                <span class="text-xl font-bold text-slate-900 block font-mono">{{ $featuredCount }}</span>
            </div>
        </div>

        <!-- Total Downloads -->
        <div class="bg-white rounded-xl p-4 border border-slate-200/80 shadow-2xs hover:border-slate-300 transition-colors flex items-center space-x-3">
            <div class="w-9 h-9 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0 border border-indigo-200/60">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
            </div>
            <div>
                <span class="text-[11px] text-slate-500 font-semibold uppercase tracking-wider block">Total Downloads</span>
                <span class="text-xl font-bold text-slate-900 block font-mono">{{ number_format($totalDownloads) }}</span>
            </div>
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="bg-white rounded-xl p-3.5 border border-slate-200/80 shadow-2xs">
        <form action="{{ route('admin.resources.index') }}" method="GET" class="flex flex-wrap items-center gap-2.5">
            <!-- Search Input -->
            <div class="relative flex-grow min-w-[200px]">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </span>
                <input type="text" name="q" placeholder="Search by title, description or file name..." value="{{ request('q') }}" 
                       class="w-full pl-8 pr-3 py-1.5 text-xs bg-slate-50/70 border border-slate-200 rounded-lg outline-none focus:border-blue-950 focus:bg-white focus:ring-1 focus:ring-blue-950/20 transition-colors">
            </div>

            <!-- Exam Category Filter -->
            <select name="category_id" onchange="this.form.submit()" class="text-xs bg-slate-50/70 border border-slate-200 rounded-lg px-3 py-1.5 outline-none focus:border-blue-950 focus:bg-white">
                <option value="all">All Categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>

            <!-- Resource Type Filter -->
            <select name="type" onchange="this.form.submit()" class="text-xs bg-slate-50/70 border border-slate-200 rounded-lg px-3 py-1.5 outline-none focus:border-blue-950 focus:bg-white">
                <option value="all">All Material Types</option>
                @foreach($types as $key => $label)
                    <option value="{{ $key }}" {{ request('type') === $key ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>

            <!-- Status Filter -->
            <select name="status" onchange="this.form.submit()" class="text-xs bg-slate-50/70 border border-slate-200 rounded-lg px-3 py-1.5 outline-none focus:border-blue-950 focus:bg-white">
                <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>All Statuses</option>
                <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published (Live)</option>
                <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft (Hidden)</option>
                <option value="featured" {{ request('status') === 'featured' ? 'selected' : '' }}>Featured Only</option>
                <option value="with_file" {{ request('status') === 'with_file' ? 'selected' : '' }}>Uploaded File</option>
                <option value="external" {{ request('status') === 'external' ? 'selected' : '' }}>External / Cloud Link</option>
            </select>

            <!-- Sorting -->
            <select name="sort" onchange="this.form.submit()" class="text-xs bg-slate-50/70 border border-slate-200 rounded-lg px-3 py-1.5 outline-none focus:border-blue-950 focus:bg-white">
                <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Sort: Latest First</option>
                <option value="downloads" {{ request('sort') === 'downloads' ? 'selected' : '' }}>Sort: Most Downloaded</option>
                <option value="title" {{ request('sort') === 'title' ? 'selected' : '' }}>Sort: Title (A-Z)</option>
                <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Sort: Oldest First</option>
            </select>

            <button type="submit" class="px-3 py-1.5 bg-slate-900 hover:bg-black text-white text-xs font-semibold rounded-lg shadow-2xs transition-colors">
                Apply
            </button>

            @if(request()->anyFilled(['q', 'category_id', 'type', 'status', 'sort']))
                <a href="{{ route('admin.resources.index') }}" class="px-2.5 py-1.5 text-xs text-slate-500 hover:text-red-600 font-medium transition-colors">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Resources Table Card -->
    <div class="bg-white rounded-xl border border-slate-200/80 shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200 text-slate-500 font-semibold uppercase tracking-wider text-[10px]">
                        <th class="py-3 px-3 w-10 text-center">
                            <input type="checkbox" 
                                   @change="toggleSelectAll()" 
                                   :checked="isAllSelected()"
                                   :indeterminate.prop="isIndeterminate()"
                                   class="w-3.5 h-3.5 text-blue-900 border-slate-300 rounded focus:ring-blue-900/30 cursor-pointer">
                        </th>
                        <th class="py-3 px-4">Resource Details</th>
                        <th class="py-3 px-4">Category</th>
                        <th class="py-3 px-4">Type</th>
                        <th class="py-3 px-4">File / Format</th>
                        <th class="py-3 px-4 text-center">Downloads</th>
                        <th class="py-3 px-4 text-center">Featured</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($resources as $item)
                        <tr class="hover:bg-slate-50/70 transition-colors" :class="selected.includes({{ $item->id }}) ? 'bg-blue-50/30' : ''">
                            <td class="py-3.5 px-3 text-center">
                                <input type="checkbox" 
                                       :value="{{ $item->id }}" 
                                       x-model="selected" 
                                       class="w-3.5 h-3.5 text-blue-900 border-slate-300 rounded focus:ring-blue-900/30 cursor-pointer">
                            </td>
                            
                            <!-- Title & Description -->
                            <td class="py-3.5 px-4 max-w-sm">
                                <div class="flex items-start space-x-2.5">
                                    <div class="w-7 h-7 rounded-lg bg-blue-50 text-blue-950 font-bold flex items-center justify-center shrink-0 border border-blue-200/60 mt-0.5">
                                        @if($item->hasFile())
                                            <span class="text-[9px] uppercase font-mono">{{ $item->file_extension }}</span>
                                        @elseif($item->external_url)
                                            <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                        @else
                                            <span class="text-[9px] uppercase font-mono">DOC</span>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <div class="flex items-center space-x-1.5">
                                            <a href="{{ route('admin.resources.edit', $item->id) }}" class="font-bold text-slate-900 hover:text-blue-950 line-clamp-1">
                                                {{ $item->title }}
                                            </a>
                                            @if($item->is_featured)
                                                <span class="shrink-0 text-amber-500" title="Featured Resource">
                                                    ★
                                                </span>
                                            @endif
                                        </div>
                                        @if($item->description)
                                            <p class="text-[11px] text-slate-500 line-clamp-1 mt-0.5">{{ $item->description }}</p>
                                        @endif
                                        <div class="flex items-center space-x-2 mt-1 text-[10px] text-slate-400">
                                            <span>Added {{ $item->created_at->format('M d, Y') }}</span>
                                            @if($item->creator)
                                                <span>• by {{ $item->creator->name }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Category -->
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                @if($item->examCategory)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-slate-100 text-slate-800 border border-slate-200">
                                        {{ $item->examCategory->name }}
                                    </span>
                                @else
                                    <span class="text-[11px] text-slate-400 italic">General Defence</span>
                                @endif
                            </td>

                            <!-- Type -->
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold 
                                    {{ $item->type === 'syllabus' ? 'bg-indigo-50 text-indigo-700 border border-indigo-200' : '' }}
                                    {{ $item->type === 'pdf' ? 'bg-blue-50 text-blue-700 border border-blue-200' : '' }}
                                    {{ $item->type === 'previous_paper' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : '' }}
                                    {{ $item->type === 'formula_sheet' ? 'bg-amber-50 text-amber-700 border border-amber-200' : '' }}
                                    {{ $item->type === 'current_affairs' ? 'bg-rose-50 text-rose-700 border border-rose-200' : '' }}
                                ">
                                    {{ $item->typeName }}
                                </span>
                            </td>

                            <!-- File / Format -->
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                @if($item->hasFile())
                                    <div class="flex flex-col">
                                        <span class="font-mono text-slate-800 font-semibold text-[11px] flex items-center space-x-1">
                                            <span>💾</span>
                                            <span class="truncate max-w-[120px]" title="{{ $item->file_name }}">{{ $item->file_name ?: 'Uploaded File' }}</span>
                                        </span>
                                        <span class="text-[10px] text-slate-400 font-mono">{{ $item->file_size ?: 'Uploaded' }}</span>
                                    </div>
                                @elseif($item->external_url)
                                    <div class="flex flex-col">
                                        <a href="{{ $item->external_url }}" target="_blank" class="text-blue-600 hover:underline font-mono text-[11px] flex items-center space-x-1">
                                            <span>🔗 Cloud Link ↗</span>
                                        </a>
                                        <span class="text-[10px] text-slate-400 font-mono">{{ $item->file_size ?: 'Remote' }}</span>
                                    </div>
                                @else
                                    <span class="text-[10px] text-slate-400 font-mono">System Guide ({{ $item->file_size ?: 'Text' }})</span>
                                @endif
                            </td>

                            <!-- Downloads Count -->
                            <td class="py-3.5 px-4 text-center whitespace-nowrap font-mono font-bold text-slate-800">
                                <span class="bg-slate-100 px-2 py-0.5 rounded text-[11px] border border-slate-200">
                                    📥 {{ number_format($item->downloads_count) }}
                                </span>
                            </td>

                            <!-- Featured Star Toggle -->
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                <form action="{{ route('admin.resources.toggle', $item->id) }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="field" value="is_featured">
                                    <button type="submit" 
                                            class="p-1 rounded-md transition-colors {{ $item->is_featured ? 'text-amber-500 hover:text-amber-600 bg-amber-50' : 'text-slate-300 hover:text-amber-500 hover:bg-slate-100' }}"
                                            title="{{ $item->is_featured ? 'Unpin from Featured' : 'Pin to Featured' }}">
                                        <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    </button>
                                </form>
                            </td>

                            <!-- Publish Status Toggle -->
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                <form action="{{ route('admin.resources.toggle', $item->id) }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="field" value="is_published">
                                    <button type="submit" 
                                            class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold transition-colors border {{ $item->is_published ? 'bg-emerald-50 text-emerald-700 border-emerald-200 hover:bg-emerald-100' : 'bg-slate-100 text-slate-600 border-slate-300 hover:bg-slate-200' }}"
                                            title="Click to toggle publish status">
                                        {{ $item->is_published ? 'Published' : 'Draft' }}
                                    </button>
                                </form>
                            </td>

                            <!-- Actions -->
                            <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                <div class="inline-flex items-center space-x-1">
                                    <!-- Download / Verify Action -->
                                    <a href="{{ route('admin.resources.download', $item->id) }}" 
                                       title="Download / Test File"
                                       class="p-1.5 text-slate-500 hover:text-blue-950 hover:bg-slate-100 rounded-lg transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                    </a>

                                    <!-- Edit Action -->
                                    <a href="{{ route('admin.resources.edit', $item->id) }}" 
                                       title="Edit Resource"
                                       class="p-1.5 text-slate-500 hover:text-blue-950 hover:bg-slate-100 rounded-lg transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>

                                    <!-- Delete Action -->
                                    <form action="{{ route('admin.resources.destroy', $item->id) }}" method="POST" 
                                          onsubmit="return confirm('Are you sure you want to permanently delete \'{{ addslashes($item->title) }}\'? Any attached file on storage will also be deleted.');"
                                          class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                title="Delete Resource"
                                                class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-12 text-center text-slate-400">
                                <div class="max-w-xs mx-auto space-y-3">
                                    <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-slate-700">No Study Resources Found</p>
                                        <p class="text-xs text-slate-400 mt-0.5">Try clearing filters or click below to upload your first study material.</p>
                                    </div>
                                    <a href="{{ route('admin.resources.create') }}" class="inline-flex items-center space-x-1.5 px-3.5 py-1.5 rounded-lg text-xs font-semibold text-white bg-blue-950 hover:bg-blue-900 shadow-2xs">
                                        <span>+ Upload New Resource</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($resources->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                {{ $resources->links() }}
            </div>
        @endif
    </div>

    <!-- Reusable Bulk Action Bar Component -->
    <x-bulk-action-bar 
        :action-url="route('admin.resources.bulk')"
        item-name="Resource" 
        item-name-plural="Resources"
        :actions="[
            ['name' => 'publish', 'label' => 'Publish', 'icon' => 'check', 'style' => 'success'],
            ['name' => 'draft', 'label' => 'Move to Draft', 'icon' => 'pause', 'style' => 'warning'],
            ['name' => 'export', 'label' => 'Export CSV', 'icon' => 'download', 'style' => 'secondary'],
            ['name' => 'delete', 'label' => 'Delete Selected', 'icon' => 'trash', 'style' => 'danger', 'confirm' => 'Are you sure you want to permanently delete the selected resources?']
        ]" />

</div>
@endsection
