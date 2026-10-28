@extends('layouts.app')

@section('title', 'Free Defence Study Resources & Downloads — Nirmaan')

@section('content')
<div class="py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- Header -->
        <div class="pb-4 border-b border-slate-200">
            <nav class="flex text-xs text-slate-500 mb-2">
                <a href="{{ route('home') }}" class="hover:text-blue-950">Home</a>
                <span class="mx-2 text-slate-300">/</span>
                <span class="text-slate-800 font-semibold">Free Resources</span>
            </nav>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Free Defence Preparation Resources</h1>
            <p class="text-sm text-slate-600 mt-1 max-w-2xl">
                Download verified official syllabus copies, high-yield revision sheets, previous year papers, and attempt free simulated mock tests.
            </p>
        </div>

        <!-- Free Mock Tests Highlight -->
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

        <!-- Filter bar -->
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('resources.index') }}" class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition border {{ !request('type') ? 'bg-blue-950 text-white border-blue-950 shadow-2xs' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-50' }}">
                All Materials
            </a>
            <a href="{{ route('resources.index', ['type' => 'syllabus']) }}" class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition border {{ request('type') === 'syllabus' ? 'bg-blue-950 text-white border-blue-950 shadow-2xs' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-50' }}">
                Official Syllabi
            </a>
            <a href="{{ route('resources.index', ['type' => 'pdf']) }}" class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition border {{ request('type') === 'pdf' ? 'bg-blue-950 text-white border-blue-950 shadow-2xs' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-50' }}">
                Revision Notes
            </a>
            <a href="{{ route('resources.index', ['type' => 'previous_paper']) }}" class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition border {{ request('type') === 'previous_paper' ? 'bg-blue-950 text-white border-blue-950 shadow-2xs' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-50' }}">
                Previous Papers
            </a>
        </div>

        <!-- Materials List -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($materials as $material)
                <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-2xs hover:border-slate-300 transition flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-xs font-semibold uppercase tracking-wider text-blue-950 bg-blue-50 px-2.5 py-0.5 rounded border border-blue-200">
                                {{ strtoupper($material->type) }}
                            </span>
                            <span class="text-xs text-slate-400 font-mono">{{ $material->file_size ?? '2.5 MB' }}</span>
                        </div>
                        <h3 class="text-base font-bold text-slate-900 leading-snug mb-2">
                            {{ $material->title }}
                        </h3>
                        <p class="text-xs text-slate-600 line-clamp-3 leading-relaxed mb-4">
                            {{ $material->description }}
                        </p>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                        <span class="text-[11px] text-slate-400">
                            📥 {{ number_format($material->downloads_count) }} downloads
                        </span>
                        <a href="{{ route('resources.download', $material->id) }}" class="inline-flex items-center space-x-1.5 px-3 py-1.5 text-xs font-semibold text-blue-950 bg-blue-50 hover:bg-blue-100 rounded-lg transition border border-blue-200">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            <span>Download</span>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-12 text-center text-slate-500 bg-white rounded-xl border border-slate-200 shadow-2xs">
                    No resources matching the selected filter.
                </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $materials->links() }}
        </div>

    </div>
</div>
@endsection
