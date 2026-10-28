@extends('layouts.admin')

@section('title', 'Notice Board & Notification Management — Admin Command')
@section('header_title', 'Notice Board ')

@section('content')
<div class="space-y-5">
    
    <!-- Action Bar & Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white rounded-xl p-4 sm:p-5 border border-slate-200/80 shadow-2xs">
        <div>
            <div class="flex items-center space-x-1.5 text-xs text-slate-500 font-medium mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-950">Dashboard</a>
                <span>/</span>
                <span class="text-slate-900 font-semibold">Notice Board</span>
            </div>
            <h1 class="text-lg sm:text-xl font-bold text-slate-900 tracking-tight">Notice Board &amp; Notification Management</h1>
            <p class="text-xs text-slate-500 mt-0.5">Manage live homepage alerts, recruitment bulletins, admit card notices, and circulars with 72-hour NEW badge tracking.</p>
        </div>
        <div class="flex items-center space-x-2 shrink-0">
            <a href="{{ route('announcements') }}" target="_blank" 
               class="px-3 py-2 rounded-lg text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 border border-slate-200 transition-colors flex items-center space-x-1.5 shadow-2xs">
                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                <span>Live Notices ↗</span>
            </a>
            <a href="{{ route('admin.notifications.create') }}" 
               class="px-3.5 py-2 rounded-lg text-xs font-semibold text-white bg-blue-950 hover:bg-blue-900 transition-colors flex items-center space-x-1.5 shadow-2xs">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span>Post Notice</span>
            </a>
        </div>
    </div>

    <!-- 4 KPI Summary Cards (Linear Minimalist) -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
        <!-- Total -->
        <div class="bg-white rounded-xl p-4 border border-slate-200/80 shadow-2xs hover:border-slate-300 transition-colors flex items-center space-x-3">
            <div class="w-9 h-9 rounded-lg bg-blue-50 text-blue-950 flex items-center justify-center shrink-0 border border-blue-200/60">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
            </div>
            <div>
                <span class="text-[11px] text-slate-500 font-semibold uppercase tracking-wider block">Total Notices</span>
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

        <!-- Active NEW Badges -->
        <div class="bg-white rounded-xl p-4 border border-slate-200/80 shadow-2xs hover:border-slate-300 transition-colors flex items-center space-x-3">
            <div class="w-9 h-9 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center shrink-0 border border-rose-200/60">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            </div>
            <div>
                <span class="text-[11px] text-slate-500 font-semibold uppercase tracking-wider block">&le; 72h Badges</span>
                <div class="flex items-center space-x-1.5">
                    <span class="text-xl font-bold text-rose-600 font-mono">{{ $activeNewCount }}</span>
                    <span class="text-[9px] font-semibold text-rose-600 uppercase bg-rose-50 px-1 py-0.2 rounded border border-rose-200/60">Active</span>
                </div>
            </div>
        </div>

        <!-- Drafts -->
        <div class="bg-white rounded-xl p-4 border border-slate-200/80 shadow-2xs hover:border-slate-300 transition-colors flex items-center space-x-3">
            <div class="w-9 h-9 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center shrink-0 border border-slate-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            </div>
            <div>
                <span class="text-[11px] text-slate-500 font-semibold uppercase tracking-wider block">Drafts</span>
                <span class="text-xl font-bold text-slate-900 block font-mono">{{ $draftCount }}</span>
            </div>
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="bg-white rounded-xl p-3.5 border border-slate-200/80 shadow-2xs">
        <form action="{{ route('admin.notifications.index') }}" method="GET" class="flex flex-wrap items-center gap-2.5">
            <!-- Search Input -->
            <div class="relative flex-grow min-w-[220px]">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </span>
                <input type="text" name="q" placeholder="Search notices by title, description or category..." value="{{ request('q') }}" 
                       class="w-full pl-8 pr-3 py-1.5 text-xs bg-slate-50/70 border border-slate-200 rounded-lg outline-none focus:border-blue-950 focus:bg-white focus:ring-1 focus:ring-blue-950/20 transition-colors">
            </div>

            <!-- Category Filter -->
            <select name="category" onchange="this.form.submit()" class="text-xs bg-slate-50/70 border border-slate-200 rounded-lg px-3 py-1.5 outline-none focus:border-blue-950 focus:bg-white">
                <option value="all">All Categories</option>
                @foreach($allCategories as $category)
                    <option value="{{ $category }}" {{ request('category') === $category ? 'selected' : '' }}>
                        {{ $category }}
                    </option>
                @endforeach
            </select>

            <!-- Status Filter -->
            <select name="status" onchange="this.form.submit()" class="text-xs bg-slate-50/70 border border-slate-200 rounded-lg px-3 py-1.5 outline-none focus:border-blue-950 focus:bg-white">
                <option value="">All Statuses</option>
                <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Live Published</option>
                <option value="new_active" {{ request('status') === 'new_active' ? 'selected' : '' }}>NEW Badge Active (&le; 72h)</option>
                <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Drafts Only</option>
                <option value="pinned" {{ request('status') === 'pinned' ? 'selected' : '' }}>Pinned</option>
            </select>

            <button type="submit" class="px-3.5 py-1.5 rounded-lg text-xs font-semibold bg-slate-900 hover:bg-slate-800 text-white transition-colors shadow-2xs">
                Filter
            </button>

            @if(request()->hasAny(['q', 'category', 'status']))
                <a href="{{ route('admin.notifications.index') }}" class="text-xs font-semibold text-slate-500 hover:text-red-600 transition-colors pl-1">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Notifications Table -->
    <div class="bg-white rounded-xl border border-slate-200/80 shadow-2xs overflow-hidden">
        @if($notifications->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="bg-slate-50/75 text-slate-500 uppercase text-[10px] font-semibold tracking-wider border-b border-slate-200/80">
                        <tr>
                            <th class="px-4 py-2.5 whitespace-nowrap">Status &amp; Badge</th>
                            <th class="px-4 py-2.5">Notice Details</th>
                            <th class="px-4 py-2.5 whitespace-nowrap">Target / Attachment</th>
                            <th class="px-4 py-2.5 whitespace-nowrap">Published</th>
                            <th class="px-4 py-2.5 whitespace-nowrap text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($notifications as $notice)
                            <tr class="hover:bg-slate-50/70 transition-colors group">
                                <!-- Status & NEW Badge Column -->
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="space-y-1">
                                        @if($notice->is_published)
                                            <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md text-[10px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                <span>Live</span>
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md text-[10px] font-medium bg-slate-100 text-slate-600 border border-slate-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                                <span>Draft</span>
                                            </span>
                                        @endif

                                        <!-- NEW Badge Indicator -->
                                        @if($notice->is_new)
                                            <div class="flex items-center space-x-1.5">
                                                <span class="px-1.5 py-0.2 rounded text-[9px] font-bold uppercase tracking-wider bg-rose-600 text-white shadow-2xs">
                                                    NEW
                                                </span>
                                                <span class="text-[10px] text-rose-600 font-mono font-medium">
                                                    {{ $notice->new_badge_remaining_hours }}h left
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                </td>

                                <!-- Title, Category, and Snippet -->
                                <td class="px-4 py-3 max-w-md">
                                    <div class="space-y-1">
                                        <div class="flex flex-wrap items-center gap-1.5">
                                            @if($notice->is_pinned)
                                                <span class="inline-flex items-center space-x-1 px-1.5 py-0.2 rounded text-[9px] font-semibold bg-amber-50 text-amber-800 border border-amber-200" title="Pinned to top">
                                                    <svg class="w-2.5 h-2.5 text-amber-600" fill="currentColor" viewBox="0 0 20 20"><path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"></path></svg>
                                                    <span>PINNED</span>
                                                </span>
                                            @endif
                                            <span class="px-2 py-0.5 rounded-md text-[10px] font-medium bg-blue-50 text-blue-950 border border-blue-200/60">
                                                {{ $notice->category }}
                                            </span>
                                        </div>

                                        <a href="{{ route('admin.notifications.edit', $notice->id) }}" class="font-semibold text-slate-900 group-hover:text-blue-950 transition-colors block text-xs leading-snug">
                                            {{ $notice->title }}
                                        </a>

                                        @if($notice->description)
                                            <p class="text-[11px] text-slate-500 line-clamp-1">
                                                {{ $notice->description }}
                                            </p>
                                        @endif
                                    </div>
                                </td>

                                <!-- Target / Document -->
                                <td class="px-4 py-3 whitespace-nowrap">
                                    @if($notice->attachment_path)
                                        <a href="{{ route('notifications.download', $notice->id) }}" target="_blank" 
                                           class="inline-flex items-center space-x-1.5 px-2.5 py-1 rounded-md bg-slate-50 text-slate-700 hover:bg-slate-100 border border-slate-200 transition-colors font-medium text-[11px] shadow-2xs">
                                            <svg class="w-3.5 h-3.5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                            <span class="truncate max-w-[120px]">{{ $notice->attachment_name ?: 'PDF Circular' }}</span>
                                            @if($notice->attachment_size)
                                                <span class="text-[10px] text-slate-400">({{ $notice->attachment_size }})</span>
                                            @endif
                                        </a>
                                    @elseif($notice->external_url)
                                        <a href="{{ $notice->external_url }}" target="_blank" 
                                           class="inline-flex items-center space-x-1.5 px-2.5 py-1 rounded-md bg-blue-50 text-blue-950 hover:bg-blue-100 border border-blue-200/60 transition-colors font-medium text-[11px] shadow-2xs">
                                            <svg class="w-3.5 h-3.5 text-blue-950" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                            <span>External Link</span>
                                        </a>
                                    @else
                                        <span class="text-slate-400 text-[11px]">Text Bulletin</span>
                                    @endif
                                </td>

                                <!-- Publication Date -->
                                <td class="px-4 py-3 whitespace-nowrap">
                                    @if($notice->published_at)
                                        <span class="text-xs font-medium text-slate-800 block">
                                            {{ $notice->published_at->format('M d, Y') }}
                                        </span>
                                        <span class="text-[10px] text-slate-400 block">
                                            {{ $notice->published_at->diffForHumans() }}
                                        </span>
                                    @else
                                        <span class="text-xs text-slate-400 italic">Not published</span>
                                    @endif
                                </td>

                                <!-- Actions -->
                                <td class="px-4 py-3 whitespace-nowrap text-right">
                                    <div class="inline-flex items-center space-x-1">
                                        <!-- Quick Publish / Unpublish Toggle -->
                                        <form action="{{ route('admin.notifications.toggle', $notice->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    title="{{ $notice->is_published ? 'Unpublish to draft' : 'Publish live' }}"
                                                    class="p-1 rounded-md border transition-colors shadow-2xs {{ $notice->is_published ? 'border-amber-200 text-amber-700 bg-amber-50 hover:bg-amber-100' : 'border-emerald-200 text-emerald-700 bg-emerald-50 hover:bg-emerald-100' }}">
                                                @if($notice->is_published)
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"></path></svg>
                                                @else
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                @endif
                                            </button>
                                        </form>

                                        <!-- Edit -->
                                        <a href="{{ route('admin.notifications.edit', $notice->id) }}" 
                                           title="Edit notice details"
                                           class="p-1 rounded-md border border-slate-200 text-slate-700 bg-white hover:bg-slate-50 transition-colors shadow-2xs">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </a>

                                        <!-- Delete -->
                                        <form action="{{ route('admin.notifications.destroy', $notice->id) }}" method="POST" class="inline" onsubmit="return confirm('Permanently delete this notification?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    title="Delete notice"
                                                    class="p-1 rounded-md border border-red-200 text-red-600 bg-white hover:bg-red-50 transition-colors shadow-2xs">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($notifications->hasPages())
                <div class="p-3 border-t border-slate-200/80 bg-slate-50/50">
                    {{ $notifications->links() }}
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="py-16 text-center px-4">
                <div class="w-10 h-10 rounded-lg bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-2.5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                </div>
                <h3 class="text-xs font-semibold text-slate-900">No Notifications Found</h3>
                <p class="text-xs text-slate-500 max-w-sm mx-auto mt-0.5">No notices match your active filter criteria. Clear filters or post a new notice.</p>
                <div class="mt-3">
                    <a href="{{ route('admin.notifications.create') }}" class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-white bg-blue-950 hover:bg-blue-900 transition-colors shadow-2xs">
                        <span>Post Notice</span>
                    </a>
                </div>
            </div>
        @endif
    </div>

</div>
@endsection
