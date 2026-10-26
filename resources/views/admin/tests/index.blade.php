@extends('layouts.admin')

@section('title', 'Mock Tests & CBT Engine — Admin')
@section('header_title', 'Computer-Based Test (CBT) Management')

@section('content')
<div class="space-y-6">
    
    <!-- Action Bar & Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white rounded-3xl p-6 border border-gray-100 shadow-xs">
        <div>
            <div class="flex items-center space-x-2 text-xs text-gray-400 font-semibold mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-700">HQ Dashboard</a>
                <span>&bull;</span>
                <span class="text-blue-700">CBT Tests Hub</span>
            </div>
            <h1 class="text-xl font-black text-gray-900 tracking-tight">CBT Mock Test Management</h1>
            <p class="text-xs text-gray-500 mt-0.5">Build full-length timed online tests, configure negative marking ratios, and monitor candidate attempts.</p>
        </div>
        <a href="{{ route('admin.tests.create') }}" 
           class="px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-md shadow-blue-500/20 transition flex items-center space-x-2 flex-shrink-0 self-start sm:self-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            <span>+ Create New CBT Mock Test</span>
        </a>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-2xs">
        <form action="{{ route('admin.tests.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
            
            <!-- Keyword Search -->
            <div class="relative flex-grow min-w-[200px]">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </span>
                <input type="text" name="q" placeholder="Search test title..." value="{{ request('q') }}" 
                       class="w-full pl-9 pr-4 py-2 text-xs bg-gray-50 border border-gray-200 rounded-xl outline-none focus:border-blue-500 focus:bg-white transition">
            </div>

            <!-- Category Filter -->
            <select name="category" onchange="this.form.submit()" class="text-xs bg-gray-50 border border-gray-200 rounded-xl px-3.5 py-2 outline-none focus:border-blue-500">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="px-4 py-2 rounded-xl text-xs font-bold bg-slate-900 hover:bg-slate-800 text-white transition">
                Filter
            </button>

            @if(request()->hasAny(['q', 'category']))
                <a href="{{ route('admin.tests.index') }}" class="text-xs text-gray-500 hover:text-red-600 transition pl-1">
                    Clear Filters ✕
                </a>
            @endif
        </form>
    </div>

    <!-- Tests Table -->
    <div class="bg-white rounded-3xl border border-gray-100 overflow-hidden shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-gray-600">
                <thead class="bg-gray-50/70 text-gray-400 uppercase text-[10px] font-bold border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4">Test Title</th>
                        <th class="px-6 py-4">Target Category</th>
                        <th class="px-6 py-4">Duration</th>
                        <th class="px-6 py-4">Questions</th>
                        <th class="px-6 py-4">Total Marks</th>
                        <th class="px-6 py-4">Attempts</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($tests as $t)
                        <tr class="hover:bg-gray-50/60 transition">
                            <td class="px-6 py-4 font-bold text-gray-900">
                                <a href="{{ route('admin.tests.edit', $t->id) }}" class="hover:text-blue-700 transition font-extrabold text-sm block">
                                    {{ $t->title }}
                                </a>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-blue-50 text-blue-800 border border-blue-200">
                                    {{ $t->examCategory ? $t->examCategory->name : 'All Wings' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 font-semibold text-gray-700">
                                <div class="flex items-center space-x-1">
                                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <span>{{ $t->duration_minutes }} mins</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 font-bold text-gray-800">
                                <span class="px-2 py-0.5 rounded-md bg-gray-100 text-gray-700 text-[11px]">
                                    {{ $t->questions_count }} Items
                                </span>
                            </td>
                            <td class="px-6 py-4 font-mono font-bold text-gray-900">
                                {{ $t->total_marks }} pts
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.tests.attempts', $t->id) }}" class="inline-flex items-center space-x-1.5 px-2.5 py-1 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-700 font-bold transition">
                                    <span>{{ $t->attempts_count }} Submissions</span>
                                    <span>&rarr;</span>
                                </a>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase {{ $t->is_published ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-gray-100 text-gray-500' }}">
                                    {{ $t->is_published ? '● Live' : '● Draft' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right space-x-2 font-bold">
                                <a href="{{ route('admin.tests.edit', $t->id) }}" class="text-blue-700 hover:underline">
                                    Edit
                                </a>
                                <span class="text-gray-300">|</span>
                                <a href="{{ route('student.tests.instructions', $t->slug) }}" target="_blank" class="text-gray-500 hover:text-gray-800" title="Simulate Candidate CBT">
                                    Launch ↗
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-xs text-gray-400">
                                <svg class="w-10 h-10 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                No CBT mock tests found matching your search.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($tests->hasPages())
            <div class="p-4 border-t border-gray-100 bg-gray-50/50">
                {{ $tests->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
