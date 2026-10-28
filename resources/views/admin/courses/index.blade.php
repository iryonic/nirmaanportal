@extends('layouts.admin')

@section('title', 'Course & Curriculum Management — Admin Command')
@section('header_title', 'Course Batches & Curricula')

@section('content')
<div class="space-y-5">
    
    <!-- Action Bar & Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white rounded-xl p-4 sm:p-5 border border-slate-200/80 shadow-2xs">
        <div>
            <div class="flex items-center space-x-1.5 text-xs text-slate-500 font-medium mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-950">Dashboard</a>
                <span>/</span>
                <span class="text-slate-900 font-semibold">Courses Directory</span>
            </div>
            <h1 class="text-lg sm:text-xl font-bold text-slate-900 tracking-tight">Courses &amp; Curriculum Management</h1>
            <p class="text-xs text-slate-500 mt-0.5">Manage defence training batches, syllabus modules, video lessons, and pricing tiers.</p>
        </div>
        <div class="flex items-center space-x-2 shrink-0">
            <span class="px-2.5 py-1 rounded-md bg-slate-100 text-slate-700 text-xs font-medium border border-slate-200">
                Total: {{ $courses->total() }}
            </span>
            <a href="{{ route('admin.courses.create') }}" 
               class="px-3.5 py-2 rounded-lg text-xs font-semibold text-white bg-blue-950 hover:bg-blue-900 transition-colors flex items-center space-x-1.5 shadow-2xs">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span>New Course Batch</span>
            </a>
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="bg-white rounded-xl p-3.5 border border-slate-200/80 shadow-2xs">
        <form action="{{ route('admin.courses.index') }}" method="GET" class="flex flex-wrap items-center gap-2.5">
            <!-- Search Input -->
            <div class="relative flex-grow min-w-[220px]">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </span>
                <input type="text" name="q" placeholder="Search by batch title or keyword..." value="{{ request('q') }}" 
                       class="w-full pl-8 pr-3 py-1.5 text-xs bg-slate-50/70 border border-slate-200 rounded-lg outline-none focus:border-blue-950 focus:bg-white focus:ring-1 focus:ring-blue-950/20 transition-colors">
            </div>

            <!-- Category Filter -->
            <select name="category" onchange="this.form.submit()" class="text-xs bg-slate-50/70 border border-slate-200 rounded-lg px-3 py-1.5 outline-none focus:border-blue-950 focus:bg-white">
                <option value="">All Defence Wings</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>

            <!-- Status Filter -->
            <select name="status" onchange="this.form.submit()" class="text-xs bg-slate-50/70 border border-slate-200 rounded-lg px-3 py-1.5 outline-none focus:border-blue-950 focus:bg-white">
                <option value="">All Statuses</option>
                <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published Batches</option>
                <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft Batches</option>
            </select>

            <button type="submit" class="px-3.5 py-1.5 rounded-lg text-xs font-semibold bg-slate-900 hover:bg-slate-800 text-white transition-colors shadow-2xs">
                Filter
            </button>

            @if(request()->hasAny(['q', 'category', 'status']))
                <a href="{{ route('admin.courses.index') }}" class="text-xs font-semibold text-slate-500 hover:text-red-600 transition-colors pl-1">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Courses Table -->
    <div class="bg-white rounded-xl border border-slate-200/80 overflow-hidden shadow-2xs">
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50/75 text-slate-500 uppercase text-[10px] font-semibold tracking-wider border-b border-slate-200/80">
                    <tr>
                        <th class="px-4 py-2.5 whitespace-nowrap">Batch Title</th>
                        <th class="px-4 py-2.5 whitespace-nowrap">Branch / Wing</th>
                        <th class="px-4 py-2.5 whitespace-nowrap">Price</th>
                        <th class="px-4 py-2.5 whitespace-nowrap">Curriculum</th>
                        <th class="px-4 py-2.5 whitespace-nowrap">Cadets</th>
                        <th class="px-4 py-2.5 whitespace-nowrap">Status</th>
                        <th class="px-4 py-2.5 whitespace-nowrap text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($courses as $c)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <!-- Batch Title -->
                            <td class="px-4 py-3 whitespace-nowrap font-medium text-slate-900">
                                <a href="{{ route('admin.courses.edit', $c->id) }}" class="hover:text-blue-950 transition-colors font-semibold text-xs block">
                                    {{ $c->title }}
                                </a>
                                <span class="text-[11px] text-slate-400 font-normal block max-w-sm truncate" title="{{ $c->subtitle }}">{{ $c->subtitle }}</span>
                            </td>

                            <!-- Branch / Wing -->
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-medium bg-blue-50 text-blue-950 border border-blue-200/60">
                                    {{ $c->examCategory ? $c->examCategory->name : 'General Defence' }}
                                </span>
                            </td>

                            <!-- Fee Structure -->
                            <td class="px-4 py-3 whitespace-nowrap font-mono">
                                <span class="font-semibold text-slate-900">{{ $c->formatted_price }}</span>
                                @if($c->original_price)
                                    <span class="text-[10px] text-slate-400 line-through block">₹ {{ number_format($c->original_price, 2) }}</span>
                                @endif
                            </td>

                            <!-- Modules Count -->
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="px-2 py-0.5 rounded text-[11px] font-medium bg-slate-100 text-slate-700 border border-slate-200">
                                    {{ $c->modules_count }} Modules
                                </span>
                            </td>

                            <!-- Enrolled Cadets -->
                            <td class="px-4 py-3 whitespace-nowrap font-medium text-slate-700">
                                <div class="flex items-center space-x-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    <span>{{ $c->enrollments_count }} Cadets</span>
                                </div>
                            </td>

                            <!-- Publication Status with 1-Click Toggle -->
                            <td class="px-4 py-3 whitespace-nowrap">
                                <form action="{{ route('admin.courses.toggle', $c->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md text-[10px] font-medium uppercase transition-colors border {{ $c->is_published ? 'bg-emerald-50 text-emerald-700 border-emerald-200/60 hover:bg-emerald-100' : 'bg-slate-100 text-slate-600 border-slate-200 hover:bg-slate-200' }}"
                                            title="Click to toggle publication status">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $c->is_published ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                        <span>{{ $c->is_published ? 'Published' : 'Draft' }}</span>
                                    </button>
                                </form>
                            </td>

                            <!-- Operations -->
                            <td class="px-4 py-3 whitespace-nowrap text-right space-x-1.5">
                                <a href="{{ route('admin.courses.edit', $c->id) }}" class="px-2.5 py-1 rounded-md bg-blue-50 hover:bg-blue-100 text-blue-950 font-medium text-xs transition-colors border border-blue-200/60 shadow-2xs">
                                    Manage
                                </a>
                                <a href="{{ route('courses.show', $c->slug) }}" target="_blank" class="px-2 py-1 rounded-md text-slate-500 hover:text-slate-800 hover:bg-slate-100 border border-slate-200 text-xs transition-colors shadow-2xs" title="Preview Public Page">
                                    Preview ↗
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-xs text-slate-400">
                                <svg class="w-8 h-8 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                No course batches found matching your search.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($courses->hasPages())
            <div class="p-3 border-t border-slate-200/80 bg-slate-50/50">
                {{ $courses->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
