@extends('layouts.admin')

@section('title', 'Mock Tests & CBT Engine — Admin Command')
@section('header_title', 'Mock Test Management')

@section('content')
<div class="space-y-5">
    
    <!-- Action Bar & Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white rounded-xl p-4 sm:p-5 border border-slate-200/80 shadow-2xs">
        <div>
            <div class="flex items-center space-x-1.5 text-xs text-slate-500 font-medium mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-950">Dashboard</a>
                <span>/</span>
                <span class="text-slate-900 font-semibold">CBT Tests Hub</span>
            </div>
            <h1 class="text-lg sm:text-xl font-bold text-slate-900 tracking-tight">CBT Mock Test Management</h1>
            <p class="text-xs text-slate-500 mt-0.5">Build full-length timed online CBT tests, configure negative marking ratios, set pass benchmarks, and monitor attempts.</p>
        </div>
        <div class="flex items-center space-x-2 shrink-0">
            <span class="px-2.5 py-1 rounded-md bg-slate-100 text-slate-700 text-xs font-medium border border-slate-200">
                Total: {{ $tests->total() }}
            </span>
            <a href="{{ route('admin.tests.create') }}" 
               class="px-3.5 py-2 rounded-lg text-xs font-semibold text-white bg-blue-950 hover:bg-blue-900 transition-colors flex items-center space-x-1.5 shadow-2xs">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span>Create CBT Mock Test</span>
            </a>
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="bg-white rounded-xl p-3.5 border border-slate-200/80 shadow-2xs">
        <form action="{{ route('admin.tests.index') }}" method="GET" class="flex flex-wrap items-center gap-2.5">
            
            <!-- Keyword Search -->
            <div class="relative flex-grow min-w-[200px]">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </span>
                <input type="text" name="q" placeholder="Search test title or keywords..." value="{{ request('q') }}" 
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

            <button type="submit" class="px-3.5 py-1.5 rounded-lg text-xs font-semibold bg-slate-900 hover:bg-slate-800 text-white transition-colors shadow-2xs">
                Filter
            </button>

            @if(request()->hasAny(['q', 'category']))
                <a href="{{ route('admin.tests.index') }}" class="text-xs font-semibold text-slate-500 hover:text-red-600 transition-colors pl-1">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Tests Table -->
    <div class="bg-white rounded-xl border border-slate-200/80 overflow-hidden shadow-2xs">
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50/75 text-slate-500 uppercase text-[10px] font-semibold tracking-wider border-b border-slate-200/80">
                    <tr>
                        <th class="px-4 py-2.5 whitespace-nowrap">Test Series Title</th>
                        <th class="px-4 py-2.5 whitespace-nowrap">Category</th>
                        <th class="px-4 py-2.5 whitespace-nowrap">Duration</th>
                        <th class="px-4 py-2.5 whitespace-nowrap">Questions</th>
                        <th class="px-4 py-2.5 whitespace-nowrap">Benchmark</th>
                        <th class="px-4 py-2.5 whitespace-nowrap">Access Fee</th>
                        <th class="px-4 py-2.5 whitespace-nowrap">Attempts</th>
                        <th class="px-4 py-2.5 whitespace-nowrap">Status</th>
                        <th class="px-4 py-2.5 whitespace-nowrap text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($tests as $t)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <!-- Title -->
                            <td class="px-4 py-3 whitespace-nowrap font-medium text-slate-900">
                                <a href="{{ route('admin.tests.edit', $t->id) }}" class="hover:text-blue-950 transition-colors font-semibold text-xs block">
                                    {{ $t->title }}
                                </a>
                            </td>

                            <!-- Category -->
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-medium bg-blue-50 text-blue-950 border border-blue-200/60">
                                    {{ $t->examCategory ? $t->examCategory->name : 'All Wings' }}
                                </span>
                            </td>

                            <!-- Duration -->
                            <td class="px-4 py-3 whitespace-nowrap font-medium text-slate-700">
                                <div class="flex items-center space-x-1.5">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <span>{{ $t->duration_minutes }}m</span>
                                </div>
                            </td>

                            <!-- Questions Count -->
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="px-2 py-0.5 rounded text-[11px] font-medium bg-slate-100 text-slate-700 border border-slate-200">
                                    {{ $t->questions_count }} MCQs
                                </span>
                            </td>

                            <!-- Marks Benchmark -->
                            <td class="px-4 py-3 whitespace-nowrap font-mono">
                                <span class="font-semibold text-slate-900">{{ $t->pass_marks }}</span> / <span class="text-slate-400">{{ $t->total_marks }} pts</span>
                            </td>

                            <!-- Access Fee -->
                            <td class="px-4 py-3 whitespace-nowrap">
                                @if($t->is_free)
                                    <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/60 uppercase">
                                        Free
                                    </span>
                                @else
                                    <span class="font-semibold text-slate-900 font-mono">₹ {{ number_format($t->price > 0 ? $t->price : 499, 2) }}</span>
                                @endif
                            </td>

                            <!-- Attempts Link -->
                            <td class="px-4 py-3 whitespace-nowrap">
                                <a href="{{ route('admin.tests.attempts', $t->id) }}" class="inline-flex items-center space-x-1 px-2.5 py-1 rounded-md bg-blue-50 hover:bg-blue-100 text-blue-950 font-medium transition-colors border border-blue-200/60 shadow-2xs">
                                    <span>{{ $t->attempts_count }} Attempts</span>
                                    <span>&rarr;</span>
                                </a>
                            </td>

                            <!-- Publication Status with 1-Click Toggle -->
                            <td class="px-4 py-3 whitespace-nowrap">
                                <form action="{{ route('admin.tests.toggle', $t->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md text-[10px] font-medium uppercase transition-colors border {{ $t->is_published ? 'bg-emerald-50 text-emerald-700 border-emerald-200/60 hover:bg-emerald-100' : 'bg-slate-100 text-slate-600 border-slate-200 hover:bg-slate-200' }}"
                                            title="Click to toggle live publication status">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $t->is_published ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                        <span>{{ $t->is_published ? 'Live' : 'Draft' }}</span>
                                    </button>
                                </form>
                            </td>

                            <!-- Operations -->
                            <td class="px-4 py-3 whitespace-nowrap text-right space-x-1.5">
                                <a href="{{ route('admin.tests.edit', $t->id) }}" class="px-2.5 py-1 rounded-md bg-blue-50 hover:bg-blue-100 text-blue-950 font-medium text-xs transition-colors border border-blue-200/60 shadow-2xs">
                                    Edit
                                </a>
                                <a href="{{ route('student.tests.instructions', $t->slug) }}" target="_blank" class="px-2 py-1 rounded-md text-slate-500 hover:text-slate-800 hover:bg-slate-100 border border-slate-200 text-xs transition-colors shadow-2xs" title="Launch Candidate Simulator">
                                    Simulate ↗
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-12 text-center text-xs text-slate-400">
                                <svg class="w-8 h-8 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                No CBT mock tests found matching your search.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($tests->hasPages())
            <div class="p-3 border-t border-slate-200/80 bg-slate-50/50">
                {{ $tests->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
