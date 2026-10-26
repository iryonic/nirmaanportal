@extends('layouts.admin')

@section('title', 'Course & Curriculum Management — Admin Command')
@section('header_title', 'Course Batches & Curricula')

@section('content')
<div class="space-y-6">
    
    <!-- Action Bar & Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white rounded-2xl p-5 sm:p-6 border border-gray-200 shadow-2xs">
        <div>
            <div class="flex items-center space-x-2 text-xs text-gray-400 font-semibold mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-700">HQ Dashboard</a>
                <span>&bull;</span>
                <span class="text-blue-700">Courses Directory</span>
            </div>
            <h1 class="text-xl font-bold text-gray-900 tracking-tight">Courses &amp; Curriculum Management</h1>
            <p class="text-xs text-gray-500 mt-0.5">Manage defence batches, fee structures, syllabus modules, video lessons, and preview triggers.</p>
        </div>
        <div class="flex items-center space-x-2.5">
            <span class="px-3 py-1.5 rounded-xl bg-blue-50 text-blue-800 text-xs font-bold border border-blue-200">
                Total Batches: {{ $courses->total() }}
            </span>
            <a href="{{ route('admin.courses.create') }}" 
               class="px-4 py-2.5 rounded-xl text-xs font-bold text-white bg-blue-700 hover:bg-blue-800 transition flex items-center space-x-2 shrink-0 shadow-xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span>+ Create Course Batch</span>
            </a>
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="bg-white rounded-2xl p-4 border border-gray-200 shadow-2xs">
        <form action="{{ route('admin.courses.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
            <!-- Search Input -->
            <div class="relative flex-grow min-w-[220px]">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </span>
                <input type="text" name="q" placeholder="Search by batch title or keyword..." value="{{ request('q') }}" 
                       class="w-full pl-9 pr-4 py-2.5 text-xs bg-gray-50 border border-gray-300 rounded-xl outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white focus:border-transparent transition">
            </div>

            <!-- Category Filter -->
            <select name="category" onchange="this.form.submit()" class="text-xs bg-gray-50 border border-gray-300 rounded-xl px-3.5 py-2.5 outline-none focus:ring-2 focus:ring-blue-600">
                <option value="">All Defence Wings</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>

            <!-- Status Filter -->
            <select name="status" onchange="this.form.submit()" class="text-xs bg-gray-50 border border-gray-300 rounded-xl px-3.5 py-2.5 outline-none focus:ring-2 focus:ring-blue-600">
                <option value="">All Statuses</option>
                <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published Batches</option>
                <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft Batches</option>
            </select>

            <button type="submit" class="px-4 py-2.5 rounded-xl text-xs font-bold bg-slate-900 hover:bg-slate-800 text-white transition shadow-2xs">
                Filter
            </button>

            @if(request()->hasAny(['q', 'category', 'status']))
                <a href="{{ route('admin.courses.index') }}" class="text-xs font-bold text-gray-500 hover:text-red-600 transition pl-1">
                    Clear Filters ✕
                </a>
            @endif
        </form>
    </div>

    <!-- Courses Table with strict whitespace-nowrap -->
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-2xs">
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left text-xs text-gray-600">
                <thead class="bg-gray-50 text-gray-500 uppercase text-[10px] font-bold border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 whitespace-nowrap">Batch Title</th>
                        <th class="px-6 py-4 whitespace-nowrap">Branch / Wing</th>
                        <th class="px-6 py-4 whitespace-nowrap">Enrollment Fee</th>
                        <th class="px-6 py-4 whitespace-nowrap">Modules</th>
                        <th class="px-6 py-4 whitespace-nowrap">Enrolled Cadets</th>
                        <th class="px-6 py-4 whitespace-nowrap">Publication Status</th>
                        <th class="px-6 py-4 whitespace-nowrap text-right">Operations</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($courses as $c)
                        <tr class="hover:bg-gray-50/70 transition">
                            <!-- Batch Title -->
                            <td class="px-6 py-4 whitespace-nowrap font-bold text-gray-900">
                                <a href="{{ route('admin.courses.edit', $c->id) }}" class="hover:text-blue-700 transition flex items-center space-x-2">
                                    <span class="text-sm font-bold">{{ $c->title }}</span>
                                </a>
                                <span class="text-[11px] text-gray-400 font-normal block mt-0.5 max-w-sm truncate" title="{{ $c->subtitle }}">{{ $c->subtitle }}</span>
                            </td>

                            <!-- Branch / Wing -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-800 border border-blue-200">
                                    {{ $c->examCategory ? $c->examCategory->name : 'General Defence' }}
                                </span>
                            </td>

                            <!-- Fee Structure -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-bold text-gray-900 font-mono">{{ $c->formatted_price }}</span>
                                @if($c->original_price)
                                    <span class="text-[10px] text-gray-400 line-through block font-mono">₹ {{ number_format($c->original_price, 2) }}</span>
                                @endif
                            </td>

                            <!-- Modules Count -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-md bg-gray-100 text-gray-700 font-bold text-[11px]">
                                    {{ $c->modules_count }} Modules
                                </span>
                            </td>

                            <!-- Enrolled Cadets -->
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-700">
                                <div class="flex items-center space-x-1.5">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                    <span>{{ $c->enrollments_count }} Cadets</span>
                                </div>
                            </td>

                            <!-- Publication Status with 1-Click Toggle -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <form action="{{ route('admin.courses.toggle', $c->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase transition border {{ $c->is_published ? 'bg-emerald-50 text-emerald-800 border-emerald-200 hover:bg-emerald-100' : 'bg-gray-100 text-gray-600 border-gray-300 hover:bg-gray-200' }}"
                                            title="Click to toggle publication status">
                                        {{ $c->is_published ? '● Published' : '● Draft' }}
                                    </button>
                                </form>
                            </td>

                            <!-- Operations -->
                            <td class="px-6 py-4 whitespace-nowrap text-right space-x-2 font-bold">
                                <a href="{{ route('admin.courses.edit', $c->id) }}" class="px-3 py-1.5 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-700 transition">
                                    Manage Curriculum
                                </a>
                                <a href="{{ route('courses.show', $c->slug) }}" target="_blank" class="px-2.5 py-1.5 rounded-lg text-gray-500 hover:text-gray-800 hover:bg-gray-100 border border-gray-200 transition" title="Preview Public Page">
                                    Preview ↗
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-xs text-gray-400">
                                <svg class="w-10 h-10 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                No course batches found matching your search.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($courses->hasPages())
            <div class="p-4 border-t border-gray-200 bg-gray-50/50">
                {{ $courses->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
