@extends('layouts.admin')

@section('title', 'Mock Tests & CBT Engine — Admin Command')
@section('header_title', 'Computer-Based Test (CBT) Management')

@section('content')
<div class="space-y-6">
    
    <!-- Action Bar & Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white rounded-2xl p-5 sm:p-6 border border-gray-200 shadow-2xs">
        <div>
            <div class="flex items-center space-x-2 text-xs text-gray-400 font-semibold mb-1">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-700">HQ Dashboard</a>
                <span>&bull;</span>
                <span class="text-blue-700">CBT Tests Hub</span>
            </div>
            <h1 class="text-xl font-bold text-gray-900 tracking-tight">CBT Mock Test Management</h1>
            <p class="text-xs text-gray-500 mt-0.5">Build full-length timed online CBT tests, configure negative marking ratios, set pass benchmarks, and monitor candidate attempts.</p>
        </div>
        <div class="flex items-center space-x-2.5">
            <span class="px-3 py-1.5 rounded-xl bg-blue-50 text-blue-800 text-xs font-bold border border-blue-200">
                Total Tests: {{ $tests->total() }}
            </span>
            <a href="{{ route('admin.tests.create') }}" 
               class="px-4 py-2.5 rounded-xl text-xs font-bold text-white bg-blue-700 hover:bg-blue-800 transition flex items-center space-x-2 shrink-0 shadow-xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span>+ Create CBT Mock Test</span>
            </a>
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="bg-white rounded-2xl p-4 border border-gray-200 shadow-2xs">
        <form action="{{ route('admin.tests.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
            
            <!-- Keyword Search -->
            <div class="relative flex-grow min-w-[200px]">
                <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </span>
                <input type="text" name="q" placeholder="Search test title or keywords..." value="{{ request('q') }}" 
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

            <button type="submit" class="px-4 py-2.5 rounded-xl text-xs font-bold bg-slate-900 hover:bg-slate-800 text-white transition shadow-2xs">
                Filter
            </button>

            @if(request()->hasAny(['q', 'category']))
                <a href="{{ route('admin.tests.index') }}" class="text-xs font-bold text-gray-500 hover:text-red-600 transition pl-1">
                    Clear Filters ✕
                </a>
            @endif
        </form>
    </div>

    <!-- Tests Table with strict whitespace-nowrap -->
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-2xs">
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left text-xs text-gray-600">
                <thead class="bg-gray-50 text-gray-500 uppercase text-[10px] font-bold border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 whitespace-nowrap">Test Series Title</th>
                        <th class="px-6 py-4 whitespace-nowrap">Target Category</th>
                        <th class="px-6 py-4 whitespace-nowrap">Duration</th>
                        <th class="px-6 py-4 whitespace-nowrap">Questions</th>
                        <th class="px-6 py-4 whitespace-nowrap">Scoring Benchmark</th>
                        <th class="px-6 py-4 whitespace-nowrap">Access Fee</th>
                        <th class="px-6 py-4 whitespace-nowrap">Attempts</th>
                        <th class="px-6 py-4 whitespace-nowrap">Publication</th>
                        <th class="px-6 py-4 whitespace-nowrap text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($tests as $t)
                        <tr class="hover:bg-gray-50/70 transition">
                            <!-- Title -->
                            <td class="px-6 py-4 whitespace-nowrap font-bold text-gray-900">
                                <a href="{{ route('admin.tests.edit', $t->id) }}" class="hover:text-blue-700 transition font-bold text-sm block">
                                    {{ $t->title }}
                                </a>
                            </td>

                            <!-- Category -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-800 border border-blue-200">
                                    {{ $t->examCategory ? $t->examCategory->name : 'All Wings' }}
                                </span>
                            </td>

                            <!-- Duration -->
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-700">
                                <div class="flex items-center space-x-1.5">
                                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <span>{{ $t->duration_minutes }} mins</span>
                                </div>
                            </td>

                            <!-- Questions Count -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-md bg-gray-100 text-gray-700 font-bold text-[11px]">
                                    {{ $t->questions_count }} MCQs
                                </span>
                            </td>

                            <!-- Marks Benchmark -->
                            <td class="px-6 py-4 whitespace-nowrap font-mono">
                                <span class="font-bold text-gray-900">{{ $t->pass_marks }}</span> / <span class="text-gray-500">{{ $t->total_marks }} pts</span>
                            </td>

                            <!-- Access Fee -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($t->is_free)
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                        FREE
                                    </span>
                                @else
                                    <span class="font-bold text-gray-900 font-mono">₹ {{ number_format($t->price > 0 ? $t->price : 499, 2) }}</span>
                                @endif
                            </td>

                            <!-- Attempts Link -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('admin.tests.attempts', $t->id) }}" class="inline-flex items-center space-x-1 px-2.5 py-1 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-700 font-bold transition">
                                    <span>{{ $t->attempts_count }} Submissions</span>
                                    <span>&rarr;</span>
                                </a>
                            </td>

                            <!-- Publication Status with 1-Click Toggle -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <form action="{{ route('admin.tests.toggle', $t->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase transition border {{ $t->is_published ? 'bg-emerald-50 text-emerald-800 border-emerald-200 hover:bg-emerald-100' : 'bg-gray-100 text-gray-600 border-gray-300 hover:bg-gray-200' }}"
                                            title="Click to toggle live publication status">
                                        {{ $t->is_published ? '● Live' : '● Draft' }}
                                    </button>
                                </form>
                            </td>

                            <!-- Operations -->
                            <td class="px-6 py-4 whitespace-nowrap text-right space-x-2 font-bold">
                                <a href="{{ route('admin.tests.edit', $t->id) }}" class="px-3 py-1.5 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-700 transition">
                                    Edit
                                </a>
                                <a href="{{ route('student.tests.instructions', $t->slug) }}" target="_blank" class="px-2.5 py-1.5 rounded-lg text-gray-500 hover:text-gray-800 hover:bg-gray-100 border border-gray-200 transition" title="Launch Candidate CBT Simulator">
                                    Simulate ↗
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-12 text-center text-xs text-gray-400">
                                <svg class="w-10 h-10 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                No CBT mock tests found matching your search.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($tests->hasPages())
            <div class="p-4 border-t border-gray-200 bg-gray-50/50">
                {{ $tests->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
